<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    public $timestamps = true;
    protected $table = 'users';
    protected $fillable = [
        'name',
        'family',
    ];


    public function executions()
    {
        return $this->hasMany(Executed::class,'user_id','id');
    }

    public function algorithms()
    {
        return $this->hasMany(AlgorithmUser::class,'user_id','id');
    }

    public function getFullName()
    {
        return $this->name . ' ' . $this->family;
    }
}
