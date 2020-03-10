<?php


namespace App\Repositories;


use App\Executed;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ExecutedRepository
{
    private $model;

    public function __construct()
    {
        $this->model = new Executed();
    }

    public function getById($id)
    {
        return Executed::where('id', $id)->with(['user', 'schedule'])->first();
    }

    public function getAllByUser($user, $algorithm = null)
    {
        $result = Executed::where('user_id', $user)
            ->with('schedule','user','algorithm');
        if ($algorithm != null) $result->where('algorithm_id',$algorithm);

        $result = $result->orderBy('schedule_id')->paginate(14);
        return $result;
    }

    public function store($path, $user, $algorithm)
    {
        $algID = 0;
        if ($algorithm == 'basic2PL')  $algID = 1;
        if ($algorithm == 'basicTO')  $algID = 4;
        if ($algorithm == 'conservative2PL')  $algID = 2;
        if ($algorithm == 'strict2PL')  $algID = 3;

        $abortedPath = DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . $algorithm . '-Aborted.txt';
        $abortedNumPath = DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . $algorithm . '-AbortNums.txt';
        $executionsPath = DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . $algorithm . '-Executions.txt';
        $timesPath = DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . $algorithm . '-Times.txt';

        $aborted = \Storage::get($abortedPath);
        $abortedNum = \Storage::get($abortedNumPath);
        $executions = \Storage::get($executionsPath);
        $times = \Storage::get($timesPath);

        $aborted = preg_split("/((\r?\n)|(\r\n?))/", $aborted);
        $abortedNum = preg_split("/((\r?\n)|(\r\n?))/", $abortedNum);
        $executions = preg_split("/((\r?\n)|(\r\n?))/", $executions);
        $times = preg_split("/((\r?\n)|(\r\n?))/", $times);

        $model = new Collection();
        for ($i = 0; $i < 1000; $i++) {
            $model->push([
                'executed' => $executions[$i],
                'time' => $times[$i],
                'aborted' => $abortedNum[$i],
                'aborts' => $aborted[$i],
                'user_id' => $user,
                'schedule_id' => $i + 1,
                'algorithm_id' => $algID,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

        }
        if (count($model) > 0) {
            return Executed::insert($model->toArray());
        }
        return false;
    }


}
