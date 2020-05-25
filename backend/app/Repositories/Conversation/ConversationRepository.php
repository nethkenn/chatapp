<?php

namespace App\Repositories\Conversation;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Events\ConversationChange;
use App\Events\StatusEvent;
use App\Models\Conversation;
use App\Models\ConversationInfo;
use App\User;

class ConversationRepository
{

    private $userID;

	public function __construct($userID = 0)
	{
	    $this->userID = $userID;
	}

	public function getConversationInfoIDS()
	{
		return Conversation::select('conversation_info_id')->where('user_id',$this->userID)->get()->toArray();
	}

	public function getConversationSettings($request)
	{
		$receiver     = Conversation::where('user_id',$request->receiverID)->where('conversation_info_id',$request->conversationInfoID)->first();
		$sender       = Conversation::where('user_id',$request->userID)->where('conversation_info_id',$request->conversationInfoID)->first();
		$chatColor    = ConversationInfo::where('id',$request->conversationInfoID)->value('chatColor');

		return [ "receiver"         => $receiver,
				 "sender"           => $sender,
				 "chatColor"        => $chatColor != "" ? $chatColor : "dark"];
	}

    public function getConversations($conversationInfoIDS)
    {
		return User::with(["conversations.conversation_info.messages.message_seen","conversations.conversation_info.messages.message_visibilities" => function($q){
				        		$q->where('message_visibilities.user_id',$this->userID);
    						},
    						"conversations" => function($q) use($conversationInfoIDS)
    						{
				        		$q->whereIn('conversations.conversation_info_id',$conversationInfoIDS);
				 		    }
				])->where('users.id','!=',$this->userID)
    			->get();
	}

	public function saveNickname($request)
	{
		//save nickname
		foreach($request->nicknames as $nickname) {
			$conversation = Conversation::find($nickname["id"]);
			$conversation->nickname = $nickname["nickname"];
			$conversation->save();
		}
		
		//fire an event to refresh conversation settings : chat, nickname
		broadcast(new ConversationChange())->toOthers();
		//fire status event to refetch conversations
		broadcast(new StatusEvent())->toOthers();
	}
	
	public function saveChatColor($request)
	{
		//update
		$conversationInfo = ConversationInfo::find($request->conversationInfoID);
		$conversationInfo->chatColor = $request->chatColor;
		$conversationInfo->save();
		
		//fire an event
		broadcast(new ConversationChange())->toOthers();
	}
}