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
        $sche = new ScheduleGenerator(20,13);
        $result = $sche->generate();
        $result = $sche->getScheduleObjects();
        return $result;
    }

    public function read()
    {
        $ser = Storage::get('1000.txt');
//        $ser = Storage::get('serial.txt');
//        $ser = Storage::get('20.txt');
        return \Opis\Closure\unserialize($ser);
    }

    public function serial()
    {
        $sche = new ScheduleGenerator(20,13);
        $result = $sche->generate();
        $result = $sche->getScheduleObjects();
        $ser = serialize($result);
        Storage::put('20.txt', $ser);
    }

    public function readFile($path)
    {
        $schedules = Storage::get($path);
        $schedulesList = [];
        foreach(preg_split("/((\r?\n)|(\r\n?))/", $schedules) as $line){
            $line = str_replace(';', '', $line);
            $regex = '/[c a]{1}\([1-7]{1}\)|[w r]{1}\([1-7]{1},[x y z w v]?\)/m';
            preg_match_all($regex, $line, $matches, PREG_SET_ORDER, 0);
            $schedule = [];
            foreach ($matches as $match) {
                $schedule[] = $this->createOperation($match[0]);
            }
            if (count($schedule) > 0)
                $schedulesList[] = $schedule;
        }
        return $schedulesList;
    }

    private function createOperation($operation)
    {
        $opr = "";
        $tran = "";
        $item = "";
        $operation = str_split($operation);
        $opr = $operation[0];
        $tran = $operation[2];
        if ($opr == 'w' || $opr == 'r'){
            $item = $operation[4];
        }else{
            $item = "";
        }
        $oprObject = new Operation($opr,$tran,$item);
        return $oprObject;
    }
}
