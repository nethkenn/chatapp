<?php

namespace App\Implementations\Message;

use App\Http\Controllers\Controller;
use App\Contracts\RemoveMessageInterface;
use App\Models\MessageVisibility;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Repositories\Message\MessageRepository;

class RemoveAllMessages extends Controller implements RemoveMessageInterface
{
    public function remove($request) 
    {
        //get all message ids
        $message = new MessageRepository();
        $messageIDs = $message->getMessagesID($request->conversationInfoID);
        //update visibility
        MessageVisibility::whereIn('message_id',$messageIDs)->where('user_id',$request->userID)->update(['visibility' => 1]);
        //fire an event to refetch messages and conversation
        $message->broadcastMessages();
    }
}