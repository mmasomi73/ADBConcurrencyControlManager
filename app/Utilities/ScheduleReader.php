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
        $sche = new ScheduleGenerator(50,12);
        $result = $sche->generate();
        $result = $sche->getScheduleObjects();
        return $result;
    }

    public function read()
    {
        $ser = Storage::get('serial.txt');
        return \Opis\Closure\unserialize($ser);
    }
}
