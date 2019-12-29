<?php


namespace App\Utilities;

use Storage;

class ScheduleReader
{
    private $schedules;

    public function __construct($schedulePath = null)
    {
//        $this->schedules = Storage::get($schedulePath);
    }

    public function readSchedules()
    {
        return $this->getSchedule();
    }

    public function getSchedule()
    {
        $sche = new ScheduleGenerator(1000,13);
        $result = $sche->generate();
        $result = $sche->getScheduleObjects();
        return $result;
    }

    public function read()
    {
        $ser = Storage::get('1000.txt');
//        $ser = Storage::get('serial.txt');
        return \Opis\Closure\unserialize($ser);
    }

    public function serial()
    {
//        $sche = new ScheduleGenerator(1000,13);
//        $result = $sche->generate();
//        $result = $sche->getScheduleObjects();
//        $ser = serialize($result);
//        Storage::put('1000.txt', $ser);
    }
}
