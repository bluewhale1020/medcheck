<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageBoard extends Model
{
    protected $fillable = [
        'title','message','user_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
