<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{

    protected $table = 'schedules';
    public $timestamps = true;

    protected $fillable = [
        'schedule',
    ];

    public function executions()
    {
        return $this->hasMany(Executed::class,'schedule_id','id');
    }

    public function getObject()
    {

    }
}
