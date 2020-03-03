<?php


namespace App\Repositories;


use App\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ScheduleRepository
{
    private $model;

    public function __construct()
    {
        $this->model = new Schedule();
    }

    public function getById($id)
    {
        return Schedule::where('id', $id)->with('executions.user')->first();
    }

    public function getAllString()
    {
        $models = Schedule::all();
        return implode("\n", $models);
    }

    public function store($schedules)
    {
        $model = new Collection();
        foreach ($schedules as $schedule) {
            $model->push(['schedule' => $schedule,'created_at'=>Carbon::now(),'updated_at'=>Carbon::now()]);
        }
        if (count($model) > 0){
            return Schedule::insert($model->toArray());
        }
        return false;
    }
}
