<?php

namespace App\Implementations\Message;

use App\Http\Controllers\Controller;
use App\Contracts\RemoveMessageInterface;
use App\Models\MessageVisibility;
use Illuminate\Http\Request;
use App\Repositories\Message\MessageRepository;

class RemoveForMyself implements RemoveMessageInterface
{
    public function remove($request) 
    {
        //update visibility
        $messageVisibility = MessageVisibility::find($request->visibilityID);
        $messageVisibility->visibility = 1;
        $messageVisibility->save();

        //fire an event to refetch messages and conversation
        $messageRepo = new MessageRepository();
        $messageRepo->broadcastMessages();
    }
}