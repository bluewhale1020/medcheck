<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventList extends Model
{
// do not use updated_at
    const UPDATED_AT = null;

    protected $fillable = [
        'name',
        'type',
        'level',
        'notes',
    ]; 
    
    public static function getMostRecentTime(){
        return self::max('created_at');
    }


}
