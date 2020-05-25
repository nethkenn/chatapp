<?php

namespace App\Implementations\Message;

use App\Http\Controllers\Controller;
use App\Contracts\ReactionInterface;
use App\Models\MessageReaction;
use Illuminate\Http\Request;
use App\Events\MessageSent;

class UpdateReaction implements ReactionInterface
{
    public function change($request) 
    {
        //update 
        $reaction = MessageReaction::find($request->id);
        $reaction->reaction = $request->reaction;
        $reaction->save();
        
        //fire an event to refetch messages
        broadcast(new MessageSent())->toOthers();
    }
}