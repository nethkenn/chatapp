<?php

namespace App\Implementations\Message;

use App\Http\Controllers\Controller;
use App\Contracts\RemoveMessageInterface;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Repositories\Message\MessageRepository;

class RemoveForEveryone implements RemoveMessageInterface
{
    public function remove($request) 
    {
        //delete message
        $message = Message::find($request->messageID);
        $message->delete();

        //fire an event to refetch messages and conversation
        $messageRepo = new MessageRepository();
        $messageRepo->broadcastMessages();
    }
}