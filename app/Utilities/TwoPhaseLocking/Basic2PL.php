<?php


namespace App\Utilities\TwoPhaseLocking;


use App\Utilities\Operation;

class Basic2PL
{

    /*
     *  This algorithm incorrect , Should be Change
     *  TODO: change and correct this algorithm
     */
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
    }

    public function getSchedules()
    {
        return $this->schedules;
    }

    public function run()
    {
        $s_start = microtime(TRUE);
        foreach ($this->schedules as $schedule) {
            $this->execute($schedule);
            $this->executionCounter++;
            if (count($this->executionList) > 0) {
//                dd("Some Error Happened In Algorithm...!");
            }
        }
        $s_end = microtime(TRUE);
        $this->totalTime = $s_end - $s_start;
    }

    public function execute($schedule)
    {
        $this->executionList = [];
        $lockManager = new LockManager();
        $s_start = microtime(TRUE);
        $finishGrowing = false;
        $reExecList = $this->executionList;
        $preventExecute = [];
        foreach ($schedule as $key => $operation) {
            // Growing
            if ($key < count($schedule) && !$finishGrowing) {
                $finishGrowing = $finishGrowing ? $finishGrowing : !($key + 1 < count($schedule));
                $this->growing($operation, $schedule, $lockManager, $finishGrowing, $preventExecute, $key);
            }
            //Shrinking
            if ($key > count($schedule) || $finishGrowing) {
                $this->shrinking($operation, $lockManager, $preventExecute);
            }
        }
        $reExecList = $preventExecute;
        $result = false;
        if (count($preventExecute) > 0) {
            foreach ($schedule as $operation) {
                $result = $this->reExecute($schedule) <= 0;
                if ($result) break;
            }

            if (!$result) {
                $exec = key_exists($this->executionCounter, $this->execution) ? $this->execution[$this->executionCounter] : "";
                $exec .= "=====> DEADLOCK DETECTED...";
                $this->execution[$this->executionCounter] = $exec;
            }
        }
        $s_end = microtime(TRUE);
        $this->timeList[] = $s_end - $s_start;
    }

    public function growing(Operation $operation, $schedule, LockManager $lockManager, &$finishGrowing, &$preventExecute, $keyOrigin)
    {
        /*
         *  1. Check Operation: if Commit or Abort => get All Locks & Finish Growing
         *  2. Check Prevention list: if not prevent Transaction Try to get lock & Execute
         *  3. if Transaction is Prevented Add to Prevention List for ReExecute
         */
        if ($operation->getOperation() == 'c' || $operation->getOperation() == 'a')
            $finishGrowing = true;
        if (!$finishGrowing) {
            if (!in_array($operation->getTransaction(), $preventExecute)) {
                $exec = key_exists($this->executionCounter, $this->execution) ? $this->execution[$this->executionCounter] : "";
                if ($operation->getOperation() == "w" || $operation->getOperation() == "r") {
                    $result = $lockManager->lock($operation);
                    if ($result == "locked") {
                        $exec .= $this->lockString($operation);
                        $operation->execute();
                        $exec .= $operation->toString();
                    } elseif ($result == "wait") {
                        $preventExecute[] = $this->abortedList[$this->executionCounter][] = $this->executionList[] = $operation->getTransaction();
                        $exec .= $this->abortString($operation);
                    } elseif ($result == "deny") {
                        $operation->execute();
                        $exec .= $operation->toString();
                    }
                }
                $this->execution[$this->executionCounter] = $exec;
            }
        } else {
            foreach ($schedule as $key => $opr) {
                if ($keyOrigin <= $key) {
                    if (!in_array($opr->getTransaction(), $preventExecute)) {
                        $exec = key_exists($this->executionCounter, $this->execution) ? $this->execution[$this->executionCounter] : "";
                        if ($opr->getOperation() == "w" || $opr->getOperation() == "r") {
                            $result = $lockManager->lock($opr);
                            if ($result == "locked") {
                                $exec .= $this->lockString($opr);
                            } elseif ($result == "wait") {
                                $preventExecute[] = $this->abortedList[$this->executionCounter][] = $this->executionList[] = $opr->getTransaction();
                                $exec .= $this->abortString($opr);
                            }
                        }
                        $this->execution[$this->executionCounter] = $exec;
                    }
                }
            }
        }
    }

    public function shrinking(Operation $operation, LockManager $lockManager, &$preventExecute)
    {
        if (!in_array($operation->getTransaction(), $preventExecute)) {
            $exec = key_exists($this->executionCounter, $this->execution) ? $this->execution[$this->executionCounter] : "";
            if ($operation->getOperation() == "w" || $operation->getOperation() == "r") {
                $operation->execute();
                $exec .= $operation->toString();
                $result = $lockManager->unLock($operation);
                if ($result == 'ok')
                    $exec .= $this->unlockString($operation);
            }
            if ($operation->getOperation() == "c" || $operation->getOperation() == "a") {
                $lockList = $this->unlockAllString($lockManager->unlockAllString($operation->getTransaction()), $operation);
                $exec .= $lockList;
                $operation->execute();
                $exec .= $operation->toString();
            }
            $this->execution[$this->executionCounter] = $exec;
        }
    }

    private function reExecute($schedule)
    {
        $lockManager = new LockManager();
        $reExecution = $this->executionList;
        $newSchedule = [];
        foreach ($schedule as $operation) {
            if (in_array($operation->getTransaction(), $reExecution)) {
                $newSchedule[] = $operation;
            }
        }
        $schedule = $newSchedule;
        $finishGrowing = false;
        $preventExecute = [];
        foreach ($schedule as $key => $operation) {
            // Growing
            if ($key < count($schedule) && !$finishGrowing) {
                $finishGrowing = $finishGrowing ? $finishGrowing : !($key + 1 < count($schedule));
                $this->growing($operation, $schedule, $lockManager, $finishGrowing, $preventExecute, $key);
            }
            //Shrinking
            if ($key > count($schedule) || $finishGrowing) {
                $this->shrinking($operation, $lockManager, $preventExecute);
            }
        }
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

    private function unlockString(Operation $operation)
    {
        if ($operation->getOperation() == "w" || $operation->getOperation() == "r") {
            return $operation->getOperation() . "u(" . $operation->getTransaction() . "," . $operation->getItem() . ")";
        }
        return "";
    }

    private function abortString(Operation $operation)
    {
        return "a[" . $operation->getTransaction() . "]";
    }

    public function getAbortedString()
    {
        return $this->abortedList;
    }

    private function unlockAllString($lockList, Operation $operation)
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
}
