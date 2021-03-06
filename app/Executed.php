<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Executed extends Model
{

    public $timestamps = true;
    protected $table = 'executions';
    protected $fillable = [
        'executed',
        'time',
        'aborted',
        'aborts',
        'user_id',
        'schedule_id',
        'algorithm_id',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class,'schedule_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function algorithm()
    {
        return $this->belongsTo(Algorithm::class,'algorithm_id');
    }
}
