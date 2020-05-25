<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    //
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function conversation()
    {
        return $this->belongsTo('App\Models\Conversation');
    }

    public function message_visibilities()
    {
        return $this->hasMany('App\Models\MessageVisibility');
    }

    public function message_reactions()
    {
        return $this->hasMany('App\Models\MessageReaction');
    }

    public function message_seen()
    {
        return $this->hasMany('App\Models\MessageSeen');
    }

}
