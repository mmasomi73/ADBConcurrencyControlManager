<?php


namespace App\Utilities\TimeStampOrdering;



class StrictTO
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
    }

    public function run()
    {
        $s_start = microtime(TRUE);
        foreach ($this->schedules as $schedule) {
            $this->execute($schedule);
            $this->executionCounter++;
        }
        $s_end = microtime(TRUE);
        $this->totalTime = $s_end - $s_start;
    }

    public function execute($schedule)
    {
        $this->executionList = [];
        $timestampManager = new TimeStampManager();
        $s_start = microtime(TRUE);
        foreach ($schedule as $operation) {
            if ($timestampManager->isCompatible($operation)){
                $exec = key_exists($this->executionCounter,$this->execution)? $this->execution[$this->executionCounter] : "";
                $operation->execute();
                $exec .= $operation->toString();
                $this->execution[$this->executionCounter] = $exec;
            }else{
                $this->abortedList[$this->executionCounter][] = $this->executionList[] = $operation->getTransaction();
            }
        }
        $s_end = microtime(TRUE);
        $this->timeList[] = $s_end - $s_start;

    }

    public function getSchedules()
    {
        return $this->schedules;
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
}
