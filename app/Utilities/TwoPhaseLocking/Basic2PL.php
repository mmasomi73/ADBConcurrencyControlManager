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
                dd("Some Error Happened In Algorithm...!");
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
        foreach ($schedule as $operation) {
            $result = $lockManager->lock($operation);
            if ($result == "wait") {
                $this->executionList[] = $operation;
            } else {
                $exec = key_exists($this->executionCounter,$this->execution)? $this->execution[$this->executionCounter] : "";
                $exec .= $this->lockString($operation);
                $operation->execute();
                $exec .= $operation->toString();
                $lockManager->unLock($operation);
                $exec .= $this->unlockString($operation);
                $this->execution[$this->executionCounter] = $exec;
            }
        }
        $s_end = microtime(TRUE);
        $this->timeList[] = $s_end - $s_start;

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
        foreach ($this->schedules as $key=>$schedule) {
            foreach ($schedule as $operation) {
                $st = key_exists($key, $string) ? $string[$key]: "";
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

    public function getAbortedString()
    {
        return [];
    }
}
