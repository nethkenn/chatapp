<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    //
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function conversation_info()
    {
        return $this->belongsTo('App\Models\ConversationInfo');
    }

    public function messages()
    {
        return $this->hasMany('App\Models\Message','conversation_info_id');
    }
}
