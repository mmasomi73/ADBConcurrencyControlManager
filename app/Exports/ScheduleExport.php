<?php

namespace App\Exports;

use App\Utilities\ScheduleReader;
use App\Utilities\TimeStampOrdering\StrictTO;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ScheduleExport implements FromView
{

    //TODO: create Constructor and pass parameter

    /**
     * @inheritDoc
     */
    public function view(): View
    {
        set_time_limit(3600);
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '3600');

        $sc = new ScheduleReader("Schedule.txt");
        $strictTO = new StrictTO($sc->readSchedules());
        $strictTO->run();

        $schedules = $strictTO->getScheduleString();
        $executions = $strictTO->getExecutionString();
        $times = $strictTO->getTimes();
        $aborts = $strictTO->getAbortedString();
        $totalTime = $strictTO->getTotalTime();
        $algorithm = "Basic TO";

        return view("excel",compact('totalTime','schedules','executions','times','aborts','algorithm'));
    }
}
