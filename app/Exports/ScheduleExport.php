<?php

namespace App\Exports;

use App\Utilities\ExcelHandler;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ScheduleExport implements FromView
{

    private $handler = [];

    public function __construct(ExcelHandler $handler,$type = 'basic2PL')
    {
        try {
            $this->handler = $handler->$type();
        }catch (\Exception $exception){
            dd("Dears, Please insert Algorithm name correctly please");
        }
    }

    /**
     * @inheritDoc
     */
    public function view(): View
    {
        $schedules = $this->getScheduleString();
        $executions = $this->getExecutionString();
        $times = $this->getTimes();
        $aborts = $this->getAbortedString();
        $totalTime = $this->getTotalTime();
        $algorithm = $this->getAlgorithm();

        return view("excel", compact('totalTime', 'schedules', 'executions', 'times', 'aborts', 'algorithm'));
    }

    private function getScheduleString()
    {
        return $this->handler[0];
    }

    private function getExecutionString()
    {
        return $this->handler[1];
    }

    private function getTimes()
    {
        return $this->handler[2];
    }

    private function getAbortedString()
    {
        return $this->handler[3];
    }

    private function getTotalTime()
    {
        return $this->handler[4];
    }

    private function getAlgorithm()
    {
        return $this->handler[5];
    }
}
