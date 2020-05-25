import Conversation                   from '../models/Conversation';
import Message                   	  from '../models/Message';
import { MUTATION_SET_CONVERSATION_SETTINGS } from "../store/conversation_settings/mutations";

export default {
    methods: {
		async fetchConversationSettings() {
			//get conversation setiings and store to vuex
			const conversationSettings = await Conversation.fetchConversationSettings({
				userID             : this.user.id,
				receiverID         : this.receiver.id,
				conversationInfoID : this.$route.params.id,
			})

            this.$store.commit(MUTATION_SET_CONVERSATION_SETTINGS, conversationSettings)
		},
		notifyConversationChange(message, userID, visibility) {
			Message.sendMessage({
						conversationInfoID   : this.$route.params.id,
						userID               : userID,
						message              : message,
						notify               : 1,
						messageVisibility    : visibility
					})
		},
        validateAndCleanNicknames(data) {
			let nicknames = [];
				
			data.users.forEach(val => {
				let userType      = val.userID == this.user.id ? "sender" 
				                                               : "receiver";
				if(val.nickname !== this.conversationSettings[userType].nickname) {
					if(userType == "sender") {
						this.notifyConversationChange(`You set your nickname to ${val.nickname}`, this.user.id, [{userID : this.user.id, visibility : 0}, {userID : this.receiver.id, visibility : 1}])
						this.notifyConversationChange(`${val.name} set his own nickname to ${val.nickname}`, this.receiver.id, [{userID : this.user.id, visibility : 1}, {userID : this.receiver.id, visibility : 0}])
					}else {
						this.notifyConversationChange(`You set the nickname for ${this.receiver.name} to ${val.nickname}`, this.user.id, [{userID : this.user.id, visibility : 0}, {userID : this.receiver.id, visibility : 1}])
						this.notifyConversationChange(`${this.conversationSettings.sender.nickname} set your nickname to ${val.nickname}`, this.receiver.id, [{userID : this.user.id, visibility : 1}, {userID : this.receiver.id, visibility : 0}])
					}
					nicknames.push(val);
				}
			})
			return nicknames;
		},
		updateNickname(data) {
			const cleanNicknames = this.validateAndCleanNicknames(data);

			if(Object.keys(cleanNicknames).length !== 0) {
				//save the nicknames to the database
				Conversation.saveNickname({nicknames : cleanNicknames})
			}
		}, 
		showEditNicknameModal() {
			//show the edit nickname modal and pass the following data
       	 	this.$refs.editNicknameModalRef.showEditNicknameModal({
					users : [
						{
							id       : this.conversationSettings.sender.id,
							nickname : this.conversationSettings.sender.nickname,
							name     : this.user.name,
							userID   : this.user.id
						},
						{
							id       : this.conversationSettings.receiver.id,
							nickname : this.conversationSettings.receiver.nickname,
							name     : this.receiver.name,
							userID   : this.receiver.id
						}
					]
				})
		},
		saveChatColor(color) {
			//set the computed chatcolor to updated color
			this.conversationSettings.chatColor = color;
			//notify chat color changed
			this.notifyConversationChange(`You changed the chat color to ${color}`, this.user.id, [{userID : this.user.id, visibility : 0}, {userID : this.receiver.id, visibility : 1}])
			this.notifyConversationChange(`${this.conversationSettings.sender.nickname} changed the chat color to ${color}`, this.receiver.id, [{userID : this.user.id, visibility : 1}, {userID : this.receiver.id, visibility : 0}])
			//save the chatcolor to the database
			Conversation.saveChatColor({
				conversationInfoID : this.$route.params.id,
				chatColor          : color 
			})
		},
		showChatColorModal(){
			//show chat color modal
       	 	this.$refs.chatColorModalRef.showChatColorModal()
     	},
    }
  }