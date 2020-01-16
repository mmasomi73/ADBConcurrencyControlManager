<?php


namespace App\Utilities;


use App\Utilities\TimeStampOrdering\BasicTO;
use App\Utilities\TwoPhaseLocking\Basic2PL;
use App\Utilities\TwoPhaseLocking\Conservative2PL;
use App\Utilities\TwoPhaseLocking\Strict2PL;

class ExcelHandler
{
    private $schedules;

    public function __construct()
    {
        $sc = new ScheduleReader();
//        $this->schedules = $sc->readSchedules();
        $this->schedules = $sc->read();
//        $this->schedules = $sc->readFile("test");
    }

    public function basic2PL()
    {
        $ccm = new Basic2PL($this->schedules);
        $ccm->run();

        $schedules = $ccm->getScheduleString();
        $executions = $ccm->getExecutionString();
        $times = $ccm->getTimes();
        $aborts = $ccm->getAbortedString();
        $totalTime = $ccm->getTotalTime();
        $algorithm = "Basic 2PL";
        return [$schedules, $executions, $times, $aborts, $totalTime, $algorithm];
    }

    public function conservative2PL()
    {
        $ccm = new Conservative2PL($this->schedules);
        $ccm->run();

        $schedules = $ccm->getScheduleString();
        $executions = $ccm->getExecutionString();
        $times = $ccm->getTimes();
        $aborts = $ccm->getAbortedString();
        $totalTime = $ccm->getTotalTime();
        $algorithm = "Conservative 2PL";
        return [$schedules, $executions, $times, $aborts, $totalTime, $algorithm];
    }

    public function strict2PL()
    {
        $ccm = new Strict2PL($this->schedules);
        $ccm->run();

        $schedules = $ccm->getScheduleString();
        $executions = $ccm->getExecutionString();
        $times = $ccm->getTimes();
        $aborts = $ccm->getAbortedString();
        $totalTime = $ccm->getTotalTime();
        $algorithm = "Strict 2PL";
        return [$schedules, $executions, $times, $aborts, $totalTime, $algorithm];
    }

    public function basicTO()
    {
        $ccm = new BasicTO($this->schedules);
        $ccm->run();

        $schedules = $ccm->getScheduleString();
        $executions = $ccm->getExecutionString();
        $times = $ccm->getTimes();
        $aborts = $ccm->getAbortedString();
        $totalTime = $ccm->getTotalTime();
        $algorithm = "Basic TO";
        return [$schedules, $executions, $times, $aborts, $totalTime, $algorithm];
    }
}
