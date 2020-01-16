<?php


namespace App\Utilities\TwoPhaseLocking;


use App\Utilities\Operation;

class Conservative2PL
{
    private $schedules; //List of Schedules
    private $timeList = []; //Execution Times for each schedule
    private $totalTime; // Total execution Time

    private $execution = []; // reExecution Time
    private $executionCounter = 0;
    private $executionList = [];

    public function __construct($schedules)
    {
        $this->schedules = $schedules;
    }

    public function run()
    {
        $s_start = microtime(TRUE);
        foreach ($this->schedules as $schedule) {
            $start = microtime(TRUE);
            $lockManager = new LockManager();
            $this->execute($schedule, $lockManager);

            $counter = count($schedule);
            while (count($this->executionList) > 0 && $counter-- > 0) {
                $this->reExecute($schedule,$lockManager);
                if (count($this->executionList) == 0)break;
            }
            $this->executionCounter++;
            $end = microtime(TRUE);
            $this->timeList[] = $end - $start;

        }
        $s_end = microtime(TRUE);
        $this->totalTime = $s_end - $s_start;
    }

    private function execute($schedule, LockManager $lockManager)
    {
        $preDeclaration = $this->preDeclaring($schedule);
        $this->growing($schedule, $preDeclaration, $lockManager);
        $this->shrinking($schedule, $preDeclaration, $lockManager);
    }

    private function growing($schedule, $preDeclaration, LockManager $lockManager)
    {
        /*
         *  1. predestination of items for each transaction
         *  2. give lock for all that items
         *  3. if a transaction can give all locks execute()
         *  4. if a transaction can't give all locks Add To Wait list
         */
//        dd($preDeclaration,$schedule);
        $this->executionList = [];
        foreach ($preDeclaration as $key => $transaction) {
            if (count($transaction) > 0) {
                $can_give = true;
                $list = [];
                foreach ($transaction as $operation) {
                    $result = $lockManager->lock($operation);
                    $list[] = [$operation, $result];
                    if ($result == 'wait') $can_give = false;
                }
                if (!$can_give) {
                    $lockManager->unlockAll($key);
                    $this->executionList[] = $key;
                } else {
                    foreach ($list as $operation) {
                        $exec = key_exists($this->executionCounter, $this->execution) ? $this->execution[$this->executionCounter] : "";
                        if ($operation[1] != 'deny')
                            $exec .= $this->lockString($operation[0]);
                        $this->execution[$this->executionCounter] = $exec;
                    }
                }
            }
        }
    }

    private function shrinking($schedule, $preDeclaration, LockManager $lockManager)
    {
        foreach ($schedule as $operation) {
            if (!in_array($operation->getTransaction(), $this->executionList)) {
                if ($operation->getOperation() == "w" || $operation->getOperation() == "r") {
                    if ($lockManager->hasLocked($operation)) {
                        $exec = key_exists($this->executionCounter, $this->execution) ? $this->execution[$this->executionCounter] : "";
                        $operation->execute();
                        $exec .= $operation->toString();
                        $result = $lockManager->unLock($operation);
                        if ($result == 'ok')
                            $exec .= $this->unlockString($operation);
                        $this->execution[$this->executionCounter] = $exec;
                    }
                } else {
                    $exec = key_exists($this->executionCounter, $this->execution) ? $this->execution[$this->executionCounter] : "";
                    $operation->execute();
                    $exec .= $operation->toString();
                    $this->execution[$this->executionCounter] = $exec;
                }
            }
        }
    }

    public function reExecute($schedule, LockManager $lockManager)
    {
        $reExecution = $this->executionList;
        $newSchedule = [];
        foreach ($schedule as $operation) {
            if (in_array($operation->getTransaction(), $reExecution)) {
                $newSchedule[] = $operation;
            }
        }
        $this->execute($newSchedule, $lockManager);
    }

    public function getTimes()
    {
        return $this->timeList;
    }

    public function getAbortedString()
    {
        return [];
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

    private function preDeclaring($schedule)
    {
        $list = [[]];
        $unique = [];
        foreach ($schedule as $k1 => $op1) {
            if (!in_array($op1->getTransaction(), $unique)) {
                $unique[] = $op1->getTransaction();
                foreach ($schedule as $k2 => $op2) {
                    if ($op1->getTransaction() == $op2->getTransaction()) {
                        if (($op2->getOperation() == 'w' || $op2->getOperation() == 'r'))
                            $list[$op1->getTransaction()][] = $op2;
                    }
                }
            }
        }
        return $list;
    }

    private function abort()
    {
        return usleep(5 * 1000);
    }

}
