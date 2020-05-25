<?php

namespace App\Repositories\Message;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;
use App\Events\StatusEvent;
use App\Events\ConversationChange;
use App\Models\Message;
use App\Models\MessageVisibility;
use App\Models\MessageSeen;
use App\User;

class MessageRepository
{

    private $userID;

	public function __construct($userID = 0)
	{
	    $this->userID = $userID;
	}

	public function getMessagesID($conversationInfoID)
	{
		return Message::select('id')->where('conversation_info_id',$conversationInfoID)->get();
	}
	
	public function getMessagesByID($conversationInfoID)
	{
		return Message::with(["message_seen.user","message_reactions.user","message_visibilities" => function($q) {
			$q->where('message_visibilities.user_id',$this->userID);
		}])->where('conversation_info_id',$conversationInfoID)
		->get()
		->filter(function ($value, $key) {
			return $value->message_visibilities[0]["visibility"] == 0;
		})->values();
	}

	public function broadcastMessages()
	{
        //fire an event to refetch messages
        broadcast(new MessageSent())->toOthers();
        
        //fire an event to refetch conversation
        broadcast(new StatusEvent())->toOthers();
	}

	public function saveSeen($request)
	{
		//save seen status
		$messageSeen = new MessageSeen;
		$messageSeen->user_id    = $this->userID;
		$messageSeen->message_id = $request->messageID;
		$messageSeen->seen       = now(); 
		$messageSeen->save();

		//fire an event
		$this->broadcastMessages();

		return "Successfully Saved";
	}

	public function saveMessage($request)
	{
		//save message
		$message = new Message;
		$message->conversation_info_id = $request->conversationInfoID;
		$message->user_id              = $request->userID;
		$message->message              = $request->message;
		$message->timeSent             = now();
		$message->isNotify             = $request->notify;
		$message->save();
		//save message visibility
		foreach($request->messageVisibility as $visibility) {
			$messageVisibility = new MessageVisibility;
			$messageVisibility->user_id    	  = $visibility["userID"];
			$messageVisibility->message_id    = $message->id;
			$messageVisibility->visibility    = $visibility["visibility"];
			$messageVisibility->save();
		}

		//fire an event
		$this->broadcastMessages();
		
		return $message->id;

	}
}