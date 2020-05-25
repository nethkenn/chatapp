import Message                        from '../models/Message';

export default {
    computed : {
        messages() {
            if(this.rawMessages.length)
            {
                return this.rawMessages.map((message,index) => { 
                    const data =  {
                        id            : message.id,
                        sender        : message.user_id == this.user.id ? true : false,	 
                        avatar        : "https://cdn.quasar.dev/img/avatar3.jpg",
                        stamp         :  new Date(message.timeSent).toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true }),
                        text          : message.message,
                        visibilityID  : message.message_visibilities[0].id,
                        tools         : false,
                        chatColor     : this.conversationSettings.chatColor,
                        notify        : message.isNotify == 1 ? true : false,
                        reactions     : message.message_reactions,
                        deliverStatus : message.hasOwnProperty('deliverStatus') ? message.deliverStatus : '',
                        messageSeen   : this.rawMessages.length-1 == index ? message.message_seen : []
                    }
    
                    if((this.rawMessages.length-1 == index) && message.user_id == this.user.id && !message.hasOwnProperty('deliverStatus')) {
                        data.deliverStatus = 'fas fa-check-circle';
                    }
    
                    return data
                });
            }
          }
    },
    methods: {
		removeMessage(data) {
			if(data.type == 'removeAll') {
				//remove all messages
				this.rawMessages = {};
			} else {
				//get the index
				let index = this.getKeybyValue(this.rawMessages,data.messageID,'id');
				//remove the object 
				this.rawMessages.splice(index, 1);
				//remove in the database
			}
			Message.removeMessage(data);
		},
		async fetchMessages(hasLoader) {
			//if has loader : show loader
			if(hasLoader){
      			this.isHidden = false;
				this.rawMessages = await Message.fetchMessages(this.user.id, this.$route.params.id)
				this.isHidden = true;
			}else {
				this.rawMessages = await Message.fetchMessages(this.user.id, this.$route.params.id)
			}
			//scroll to the bottom of the chatbox
	    	this.scrolltoBottom();
		},
    	async sendMessage() {
    		if(this.message)
    		{	
				//push message
				this.rawMessages.push({
					id        :  0,
					user_id   :  this.user.id,
					timeSent  :  new Date(),
					message   :  this.message,
					chatColor :  this.conversationSettings.chatColor,
					tools     : false,
					notify    : false,
					message_visibilities : {
						0 : {
							id : 0
						}
					},
					message_seen : [],
					message_reactions : [],
					deliverStatus : "far fa-check-circle"
				})
				//save the message to the database and overwrite the id of the sent message
				this.rawMessages[this.rawMessages.length-1].id = Message.sendMessage({
					conversationInfoID   : this.$route.params.id,
					userID               : this.user.id,
					message              : this.message,
					notify               : 0,
					messageVisibility : [
						{userID : this.user.id, visibility : 0},
						{userID : this.receiver.id, visibility : 0}
					]
				})

	    		//clear message
				this.clearMessage();
				//scroll to bottom
				this.scrolltoBottom();
    		}
		},
		seenStatus() {
			if(this.rawMessages[this.rawMessages.length-1].user_id !== this.user.id && !this.rawMessages[this.rawMessages.length-1].message_seen.length) {
				Message.saveSeen({
					userID : this.user.id,
					messageID : this.rawMessages[this.rawMessages.length-1].id
				})
			}
		},
    	clearMessage() {
    		this.message = ""
    	},
    }
  }