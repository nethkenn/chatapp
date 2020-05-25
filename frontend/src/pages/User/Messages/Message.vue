<template>
	<div>
			<!--HEADER-->
			<div class="row">
				<div class="col-1 col-md-1 col-sm-2 col-xs-2 q-pa-md">
					<q-avatar>
					   <img :src="`https://scontent.fmnl2-1.fna.fbcdn.net/v/t1.0-9/s960x960/56372190_2563424680338680_3386899287072833536_o.jpg?_nc_cat=111&_nc_sid=85a577&_nc_eui2=AeHMnKwucPHNKb2c4MTQlGrDyec-h4WGkjzJ5z6HhYaSPKLcj_6t4v1nLdmYRQSHi9RX4YMGx1KQ4arWafetG8bV&_nc_oc=AQlIHNORZsMORouROrw73odHDoTiaxUXeEqAEjrRkEbEg3RQvTRknMRDAganUxLsoko&_nc_ht=scontent.fmnl2-1.fna&_nc_tp=7&oh=95734e36d8b7d0e4b929b3611931524e&oe=5ECC0DE5`">
					</q-avatar>
				</div>
				<!--RECEIVER NAME-->
				<div class="col-3 col-xs-4 q-pa-md">
					<div class="text-subtitle2">
						{{conversationSettings.receiver.nickname}}
					</div>
					<div class="text-caption text-grey">
						{{receiver.status}}
					</div>
				</div>
				<!--SETTINGS-->
				<div class="row col-6 col-md-7 q-pa-md justify-end">
					<div v-if="search">
						  <q-fab
						    flat
							vertical-actions-align="right"
							:color="conversationSettings.chatColor"
							icon="fas fa-search"
							direction="down"
							/>	
					</div>
					<div>
					       <q-fab
						    flat
							label-class="bg-grey-3 text-purple"
							vertical-actions-align="right"
							:color="conversationSettings.chatColor"
							icon="fas fa-cog"
							direction="left"
							@click="search = !search"
						    >	
								<q-fab-action :color="conversationSettings.chatColor"  
											icon="circle" 
											@click="showChatColorModal"
											>
									<q-tooltip anchor="bottom middle" 
											self="top middle" 
											:offset="[10, 10]">
										Color
									</q-tooltip>
								</q-fab-action>

								<q-fab-action :color="conversationSettings.chatColor"  
											icon="edit" 
											@click="showEditNicknameModal">
									<q-tooltip anchor="bottom middle" 
											self="top middle" 
											:offset="[10, 10]">
										Nickname
									</q-tooltip>
								</q-fab-action>

								<q-fab-action :color="conversationSettings.chatColor"  
											icon="fas fa-trash-alt" 
											@click="showModalConfirmation">
									<q-tooltip anchor="bottom middle" 
											self="top middle" 
											:offset="[10, 10]">
										Delete Conversation
									</q-tooltip>
								</q-fab-action>
						</q-fab>
					</div>
				</div>
			</div>

			<q-separator />

			<div class="col-12 q-pa-md">
			<!--LOADER-->
            <div class="q-pa-md absolute-center" :class="{ hidden : isHidden }">
                <q-spinner-gears :color="conversationSettings.chatColor" size="5.5em"/>
            </div>

			  <q-scroll-area id="scroll" :class="$q.platform.is.desktop ? 'scrollDesktop' : 'scrollMobile'" ref="scrollRef" :thumb-style="thumbStyle">
			   <div style="width: 100%;" class="messageContainer">
				   <!--MESSAGES-->
					<div v-for="(message,index) in messages" :key="index">
						<q-chat-message v-if="message.notify"
										:label="message.text"
										class="text-grey"
										style="height:20px;"
					 		/>
						<g-chat-message v-else
										:message="message" 
										@removeMessage="removeMessage"></g-chat-message>
					</div>		
					 
					    <q-chat-message v-if="typing"
							avatar = "https://cdn.quasar.dev/img/avatar3.jpg"
							bg-color="grey-3"
						 >
							<q-spinner-dots size="2rem" />
						</q-chat-message>	   	
			    </div>
			  </q-scroll-area>
			</div>
			<!--FOOTER-->
			<q-footer>	
		       <q-toolbar class="bg-grey-3 text-black row">
				   <!--MESSAGE BOX-->
		         <q-input rounded outlined dense @click="seenStatus" class="WAL__field col-grow q-mr-sm" :color="conversationSettings.chatColor" bg-color="white" v-model="message" placeholder="Type a message..." @keyup.enter="sendMessage" @keyup="isTyping"/>
		         <q-btn round flat icon="fa fa-paper-plane" @click="sendMessage" :color="conversationSettings.chatColor">
		         	<q-tooltip anchor="top left" self="bottom middle">
		         	  Press Enter to Send
		         	</q-tooltip>
		         </q-btn>
		       </q-toolbar>
	     	</q-footer>
			 
  	  <chat-color-modal ref="chatColorModalRef" 
						:modalSettings="chatColorSettings"
						@saveChatColor="saveChatColor">
	  </chat-color-modal>

	  <edit-nickname-modal ref="editNicknameModalRef" 
	  					   :modalSettings="editNicknameSettings"
	  					   @updateNickname="updateNickname"> 
	  </edit-nickname-modal>
	  
	  <remove-message-confirmation-modal ref="removeMessageConfirmationModalRef" 
	                                     @removeMessage="removeMessage({conversationInfoID : $route.params.id, userID : user.id, type : 'removeAll'})" 
	                                     :modalSettings="removeMessageConfirmationSettings">
      </remove-message-confirmation-modal>

	</div>
</template>

<script>
  import RemoveMessageConfirmationModal from "../../../pages/User/Messages/RemoveMessageConfirmationModal";
  import ChatColorModal                 from "./ChatColorModal";
  import EditNicknameModal              from "./EditNicknameModal";
  import {mapGetters}                   from 'vuex'
  import {GETTER_RECEIVER}              from '../../../store/receiver/getters'
  import {GETTER_CONVERSATION_SETTINGS} from '../../../store/conversation_settings/getters'
  import {GETTER_USER}            	    from '../../../store/account/getters'
  import GChatMessage                   from "../../../components/GChatMessage";
  import messageMixin                   from "../../../mixins/message_mixin.js";
  import conversationMixin              from "../../../mixins/conversation_mixin.js";



  export default {
	components : {
		ChatColorModal,
		EditNicknameModal,
		GChatMessage,
		RemoveMessageConfirmationModal
	},
	name: 'Message',
	mixins: [messageMixin,conversationMixin],
    data: () => ({
		search : true,
		isHidden  : true,
		typing : false,
		rawMessages : {},
		message : '',
		thumbStyle: {
			right: '2px',
			borderRadius: '5px',
			backgroundColor: 'grey',
			width: '5px',
			opacity: 0.75
		},
	}),
	 computed : {
      ...mapGetters({
		receiver             : GETTER_RECEIVER,
		user                 : GETTER_USER,
        conversationSettings : GETTER_CONVERSATION_SETTINGS,
	  }), 
	  removeMessageConfirmationSettings () {
		  return {
			title        : "Delete Conversation",
			btnColor     : this.conversationSettings.chatColor,
			function     : 'removeMessage',
			content      : 'This message will be removed for you. Other chat members will still be able to see it.'
		  }
	  },
	  editNicknameSettings () {
		  return {
			title        : "Edit Nicknames",
			btnColor     : this.conversationSettings.chatColor,
			function     : 'updateNickname',
		  }
      },
	  chatColorSettings() {
		  return {
			title        : "Pick a color for this conversation",
          	btnColor     : this.conversationSettings.chatColor,
          	function     : 'saveChatColor',
		  }
	  },
	},
	async mounted () {
		//fetch all messages 
		await this.fetchMessages(true);
		//scroll to the bottom of the chatbox
		this.scrolltoBottom();

		window.Echo.private("messageSent")
		//track whether the user has a new message : listen to messageSent channel
		.listen("MessageSent", async (e) => { 
			await this.fetchMessages();
	    })
		//track whether the receiver is typing : listen to whisper 
	   .listenForWhisper('typing', (e) => {
		    //show typing message
			this.typing = e.typing;
			// remove is typing indicator after 1.5s
			setTimeout(() => {
				this.typing = false
			}, 1500);
		});

		window.Echo.private("conversationChange")
		//track whether the nickname or chatcolor changed : listen to conversationChange
		.listen("ConversationChange", async (e) => { 
			this.fetchConversationSettings();
	    })
	},
	watch: {
		//if route change fetch messages
		$route(to, from) {
			this.fetchMessages(true)
		}
	},
    methods : {
		showModalConfirmation(){
            this.$refs.removeMessageConfirmationModalRef.showRemoveMessageConfirmationModal()
        },
		isTyping() {
			//initialize messagechannel
			let channel = Echo.private('messageSent');
			//add a timer 
			setTimeout(() => {
				channel.whisper('typing', {
					typing: true
				});
			}, 300);
		},
    	scrolltoBottom(){
    		this.$refs.scrollRef.setScrollPosition(document.querySelector(".messageContainer").clientHeight,1);
    	}
    }	
}
</script>

<style scoped>
.scrollDesktop {
	height:61vh;max-width: 100vw;
}
.scrollMobile {
	height:70vh;max-width: 100vw;
}
</style>