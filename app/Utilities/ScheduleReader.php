<?php


namespace App\Utilities;

use Storage;

class ScheduleReader
{
    private $schedules;

    public function __construct($schedulePath)
    {
//        $this->schedules = Storage::get($schedulePath);
    }

    public function readSchedules()
    {
        return $this->getSchedule();
    }

    public function getSchedule()
    {
        $sche = new ScheduleGenerator(5,20);
        $result = $sche->generate();
        $result = $sche->getScheduleObjects();
        return $result;
    }
}
