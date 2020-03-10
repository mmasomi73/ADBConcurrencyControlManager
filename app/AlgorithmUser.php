<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlgorithmUser extends Model
{
    public $timestamps = false;
    protected $table = 'algorithm_user';
    protected $fillable = [
        'user_id',
        'algorithm_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function algorithm()
    {
        return $this->belongsTo(Algorithm::class,'algorithm_id');
    }
}
