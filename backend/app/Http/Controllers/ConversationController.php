<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Conversation\ConversationRepository;

class ConversationController extends Controller
{
    //
    
    public function fetchConversations(Request $request)
    {	
    	$conversation 			 = new ConversationRepository($request->userID);
    	$conversationInfoIDS     = $conversation->getConversationInfoIDS();
    	return response(["conversation" => $conversation->getConversations($conversationInfoIDS)]);
    }

    public function saveChatColor(Request $request, ConversationRepository $conversation)
    {
        $conversation->saveChatColor($request);
    	return response(["message" => "Successfully Saved"]);
    }

    public function saveNickname(Request $request, ConversationRepository $conversation)
    {
        $conversation->saveNickname($request);
    	return response(["message" => "Successfully Saved"]);
    }

    public function fetchConversationSettings(Request $request, ConversationRepository $conversation)
    {
    	return response(["conversationSettings" => $conversation->getConversationSettings($request)]);
    }
}
