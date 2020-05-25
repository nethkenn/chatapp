<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageReaction extends Model
{
    //
    public $timestamps = false;

    public function message()
    {
        return $this->belongsTo('App\Models\Message');
    }


    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
