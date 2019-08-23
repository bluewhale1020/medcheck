<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    public $timestamps = false;

    public function roles()
    {
        return $this->belongsToMany('App\Role', 'menu_role', 'menu_id', 'role_id');
    }
}
