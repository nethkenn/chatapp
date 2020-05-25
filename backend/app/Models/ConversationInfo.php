<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConversationInfo extends Model
{
    //
    public $timestamps = false;

    public function messages()
    {
        return $this->hasMany('App\Models\Message');
    }

    public function conversations()
    {
        return $this->hasMany('App\Models\Conversation');
    }

}
    