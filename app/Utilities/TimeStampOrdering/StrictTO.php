<?php


namespace App\Utilities\TimeStampOrdering;


use App\Utilities\Operation;
use App\Utilities\TwoPhaseLocking\LockManager;

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
            $start = microtime(TRUE);
            $this->execute($schedule);
            $this->executionCounter++;
            $end = microtime(TRUE);
            $this->timeList[] = $end - $start;
        }
        $s_end = microtime(TRUE);
        $this->totalTime = $s_end - $s_start;
    }

    public function execute($schedule)
    {

        $this->executor($schedule);
        if (count($this->executionList) > 0) {
            $result = !(count($this->executionList) > 0);
            $tooMany = false;
            $doSerial = 0;
            foreach ($schedule as $operation) {
                $result = $this->reExecute($schedule, ++$doSerial == 1);
                $tooMany = !$result;
                if ($result) break;
            }
            if ($tooMany) {
                $exec = key_exists($this->executionCounter, $this->execution) ? $this->execution[$this->executionCounter] : "";
                $exec .= "=====> TOO MANY ITERATION DETECT ...";
                $this->execution[$this->executionCounter] = $exec;
            }
        }

    }

    private function executor($schedule)
    {
        $exec = key_exists($this->executionCounter, $this->execution) ? $this->execution[$this->executionCounter] : "";
        $this->executionList = [];
        $timestampManager = new TimeStampManager();
        $preventExecute = [];
        foreach ($schedule as $operation) {
            if (!in_array($operation->getTransaction(), $preventExecute)) {
                if ($timestampManager->isCompatible($operation)) {
                    $exec = key_exists($this->executionCounter, $this->execution) ? $this->execution[$this->executionCounter] : "";
                    $operation->execute();
                    $exec .= $operation->toString();
                    $this->execution[$this->executionCounter] = $exec;
                } else {
                    $this->abortedList[$this->executionCounter][] = $this->executionList[] = $operation->getTransaction();
                    $exec .= $this->abortString($operation);
                    $this->execution[$this->executionCounter] = $exec;

                    $rollbacks = $timestampManager->getCascadingRollback($operation);
                    $this->cascadeRollback($rollbacks);
                    $preventExecute = array_merge($preventExecute, $this->abortedList[$this->executionCounter]);
                }
            }
        }
    }

    private function reExecute($schedule, $doSerial = false)
    {
        $this->executionList = array_unique($this->executionList);
        $reExecution = $this->executionList;
        $newSchedule = [];
        foreach ($schedule as $operation) {
            if (in_array($operation->getTransaction(), $reExecution)) {
                $newSchedule[] = $operation;
            }
        }
        if ($doSerial) {
            $schedule = $newSchedule;
            $newSchedule = [];
            $unique = [];
            foreach ($schedule as $k1 => $op1) {
                foreach ($schedule as $k2 => $op2) {
                    if ($op2->getTransaction() == $op1->getTransaction()) {
                        if (in_array($op2->getTransaction(), $reExecution)) {
                            if (!in_array(spl_object_hash($op2), $unique)) {
                                $unique[] = spl_object_hash($op2);
                                $newSchedule[] = $op2;
                            }
                        }
                    }
                }
            }
        }
        $this->executor($newSchedule);
        if (count($reExecution) <= count($this->executionList)) return false;
        return true;
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

    public function getAbortedString()
    {
        return $this->abortedList;
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

    private function cascadeRollback($rollbacks)
    {
        $exec = key_exists($this->executionCounter, $this->execution) ? $this->execution[$this->executionCounter] : "";
        foreach ($rollbacks as $rollback) {
            $this->abortedList[$this->executionCounter][] = $this->executionList[] = $rollback;
            $exec .= "a[" . $rollback . "]";
        }
        $this->execution[$this->executionCounter] = $exec;
    }

    private function abortString(Operation $operation)
    {
        return "a[" . $operation->getTransaction() . "]";
    }
}
