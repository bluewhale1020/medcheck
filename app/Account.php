<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'kana',
        'name',
        'id_number',
        'birthdate',
        'age', 
        'sex', 
        'department',
        'school_building',
    ];

    public function reserve_infos()
    {
        return $this->hasMany('App\ReserveInfo');
    }
}
