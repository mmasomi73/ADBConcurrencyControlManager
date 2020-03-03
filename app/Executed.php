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
        'user_id',
        'schedule_id',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class,'schedule_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
