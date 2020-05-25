<?php

namespace App\Implementations\Message;

use App\Http\Controllers\Controller;
use App\Contracts\ReactionInterface;
use App\Models\MessageReaction;
use Illuminate\Http\Request;
use App\Events\MessageSent;
    
class SaveReaction implements ReactionInterface
{
    public function change($request) 
    {
        //save to database
        $reaction = new MessageReaction;
        $reaction->user_id    = $request->user_id;
        $reaction->reaction   = $request->reaction;
        $reaction->message_id = $request->message_id;
        $reaction->save();

        //fire an event to refetch messages
        broadcast(new MessageSent())->toOthers();
    }
}