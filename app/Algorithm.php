<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Algorithm extends Model
{
    public $timestamps = true;
    protected $table = 'algorithms';
    protected $fillable = [
        'name',
    ];

    public function executions()
    {
        return $this->hasMany(Executed::class,'algorithm_id','id');
    }

    public function users()
    {
        return $this->hasMany(AlgorithmUser::class,'algorithm_id','id');
    }
}
