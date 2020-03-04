<?php


namespace App\Utilities\TwoPhaseLocking;


use App\Utilities\Operation;

class Strict2PL
{
    private $schedules;
    private $timeList = [];
    private $totalTime;

    private $execution = [];
    private $executionCounter = 0;
    private $executionList = [];

    private $abortedList = [[]];

    public function __construct($schedules)
    {
        $this->schedules = $schedules;
        $this->schedules = $schedules;
    }

    public function run()
    {
        $s_start = microtime(TRUE);
        foreach ($this->schedules as $schedule) {
            $start = microtime(TRUE);
            $lockManager = new LockManager();
            $this->execute($schedule, $lockManager);
            $this->executionCounter++;
            $end = microtime(TRUE);
            $this->timeList[] = $end - $start;

        }
        $s_end = microtime(TRUE);
        $this->totalTime = $s_end - $s_start;
    }

    private function execute($schedule, LockManager $lockManager)
    {

       $result = $this->growingShrinking($schedule, $lockManager);
       if (!$result){
           foreach ($schedule as $operation) {
               $result = $this->reExecute($schedule,$lockManager);
               if ($result) break;
           }

           if (!$result){
               $exec = key_exists($this->executionCounter, $this->execution) ? $this->execution[$this->executionCounter] : "";
               $exec .= "=====> DEADLOCK DETECTED...";
               $this->execution[$this->executionCounter] = $exec;
           }
       }

    }

    private function growingShrinking($schedule, LockManager $lockManager)
    {
        /*
         *  1. check execution list
         *  2. if operation is Exclusive Lock: If can Give Execute and Continue Else abort and ReExecute
         *  2. if operation is Shared check executability: If can, Execute and Continue Else abort and ReExecute
         *  4. if operation is Commit or Abort Unlock all Items
         */
        $this->executionList = [];
        $reExecList = $this->executionList;
        $preventExecute = [];
        foreach ($schedule as $key => $operation) {
            if (! in_array($operation->getTransaction(),$preventExecute)){
                $exec = key_exists($this->executionCounter, $this->execution) ? $this->execution[$this->executionCounter] : "";
                if ($operation->getOperation() == "w") {
                    $result = $lockManager->lock($operation);
                    if ($result == "locked") {
                        $exec .= $this->lockString($operation);
                        $operation->execute();
                        $exec .= $operation->toString();
                    } elseif ($result == "wait") {
                        $this->abort();
                        $preventExecute[] = $this->abortedList[$this->executionCounter][] = $this->executionList[] = $operation->getTransaction();
                        $exec .= $this->abortString($operation);
                        $exec .= $this->unlockAllString2($lockManager->unlockAllString($operation->getTransaction()), $operation);
                    }
                }

                if ($operation->getOperation() == "r") {
                    if ($lockManager->isCompatible($operation)) {
                        $operation->execute();
                        $exec .= $operation->toString();
                    } else {
                        $this->abort();
                        $preventExecute[] = $this->abortedList[$this->executionCounter][] = $this->executionList[] = $operation->getTransaction();
                        $exec .= $this->abortString($operation);
                        $exec .= $this->unlockAllString2($lockManager->unlockAllString($operation->getTransaction()), $operation);
                    }
                }
                if ($operation->getOperation() == "c" || $operation->getOperation() == "a") {
                    $lockList = $this->unlockAllString($lockManager->unlockAll($operation->getTransaction()),$operation);
                    $exec .= $lockList;
                    $operation->execute();
                    $exec .= $operation->toString();

                }
                $this->execution[$this->executionCounter] = $exec;
            }
        }

        if (count($reExecList) < count($this->executionList)){
            return false;//This means should be reExecute Again
        }
        return true;
    }

    private function reExecute($schedule, LockManager $lockManager)
    {
        $reExecution = $this->executionList;
        $this->executionList = [];
        $newSchedule = [];
        foreach ($schedule as $operation) {
            if (in_array($operation->getTransaction(), $reExecution)) {
                $newSchedule[] = $operation;
            }
        }
        $result = $this->growingShrinking($newSchedule, $lockManager);
        if (count($reExecution) <= count($this->executionList)) return false;
        return $result;
    }

    public function getTimes()
    {
        return $this->timeList;
    }

    public function getTotalTime()
    {
        return $this->totalTime;
    }

    public function getExecutionString()
    {
        return $this->execution;
    }

    public function getScheduleString()
    {
        $string[] = "";
        foreach ($this->schedules as $key => $schedule) {
            foreach ($schedule as $operation) {
                $st = key_exists($key, $string) ? $string[$key] : "";
                $st .= $operation->toString();
                $string[$key] = $st;
            }
        }

        return $string;
    }

    private function lockString(Operation $operation)
    {
        if ($operation->getOperation() == "w" || $operation->getOperation() == "r") {
            return $operation->getOperation() . "l(" . $operation->getTransaction() . "," . $operation->getItem() . ")";
        }
        return "";
    }

    private function abortString(Operation $operation)
    {
        return "a[" . $operation->getTransaction() . "]";
    }

    private function unlockString(Operation $operation)
    {
        if ($operation->getOperation() == "w" || $operation->getOperation() == "r") {
            return $operation->getOperation() . "u(" . $operation->getTransaction() . "," . $operation->getItem() . ")";
        }
        return "";
    }

    private function unlockAllString($lockList,Operation $operation)
    {
        $string = "";
        foreach ($lockList as $item) {
            $string .= "wu(" . $operation->getTransaction() . "," . $item . ")";
        }
        return $string;
    }

    public function getAbortedString()
    {
        return $this->abortedList;
    }

    private function unlockAllString2($lockList, Operation $operation)
    {
        $string = "";
        foreach ($lockList as $item) {
            if ($item[1] == 'w')
                $string .= "wu(" . $operation->getTransaction() . "," . $item[0] . ")";
            else
                $string .= "ru(" . $operation->getTransaction() . "," . $item[0] . ")";
        }
        return $string;
    }

    private function abort()
    {
        return usleep(5 * 1000);
    }

}
