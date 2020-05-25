<?php

namespace App\Implementations\Message;

use App\Http\Controllers\Controller;
use App\Contracts\ReactionInterface;
use App\Models\MessageReaction;
use Illuminate\Http\Request;
use App\Events\MessageSent;

class DeleteReaction implements ReactionInterface
{
    public function change($request) 
    {
        //delete
        $reaction = MessageReaction::find($request->id);
        $reaction->delete();
                
        //fire an event to refetch messages
        broadcast(new MessageSent())->toOthers();
    }
}