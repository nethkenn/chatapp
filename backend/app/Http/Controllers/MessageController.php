<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Factories\Message\RemoveMessageFactory;
use App\Repositories\Message\MessageRepository;
use App\Factories\Message\ReactionFactory;

class MessageController extends Controller
{

    public function fetchMessages(Request $request)
    {
        $message = new MessageRepository($request->userID);
    	return response(["messages" => $message->getMessagesByID($request->conversationInfoID)]);
    }

    public function sendMessage(Request $request)
    {
        $message = new MessageRepository(request('userID'));
        return response(["id" => $message->saveMessage($request)]);
    }   

    public function removeMessage(Request $request)
    {
        $removeMessageFactory = new RemoveMessageFactory();
        $removeMessage = $removeMessageFactory->initializeRemoveMessage($request->type);
        $removeMessage->remove($request);
    	return response(["message" => "Successfully Removed"]);
    }

    public function changeReaction(Request $request) 
    {
        $reactionFactory = new ReactionFactory();
        $changeReaction = $reactionFactory->initializeReaction($request->type);
        $changeReaction->change($request);
    	return response(["message" => "Successfully Changed"]);
    }

    public function saveSeen(Request $request) 
    {
        $message = new MessageRepository(request('userID'));
    	return response(["message" => $message->saveSeen($request)]);
    }
}
	