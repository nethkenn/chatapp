<template>
    <div class="q-message"
                           :class="message.sender ? 'q-message-sent' 
                                                  : 'q-message-received'"
                                                  @mouseover="toolsFab = true"
                                                  @mouseleave="closeTools">
        <div class="q-message-container row items-end no-wrap" :style="message.reactions.length ? 'margin-bottom:30px' : ''" :class="message.sender ? 'reverse' 
                                                                                        : ''">
            <!--Deliver icon-->
            <q-icon :name="message.deliverStatus" v-if="message.deliverStatus != '' && !message.messageSeen.length" :color="message.chatColor" style="font-size:10px"/>
            <!--Seen-->
            <q-avatar size="10px" v-if="message.messageSeen.length && message.sender">
                 <img :src="message.avatar">
            </q-avatar>
            <!--Avatar-->
            <img :src="message.avatar" aria-hidden="true" class="q-message-avatar" :class="message.sender ? 'q-message-avatar--sent' 
                                                                                                          : 'q-message-avatar--received'">
            <!--Message-->
            <div>
                <div class="q-message-text" :class="message.sender ? `q-message-text--sent text-${message.chatColor} bg-${message.chatColor}` 
                                                                   : 'q-message-text--received bg-grey-3 text-grey-3'">
                    <div class="q-message-text-content" :class="message.sender ? 'q-message-text-content--sent' 
                                                                               : 'q-message-text-content--received'">
                        <div :class="message.sender ? 'text-white'
                                                    : 'text-dark'">{{message.text}}</div>
						<div class="q-message-stamp" :class="message.sender ? 'text-white' 
                                                                            : 'text-dark'">{{message.stamp}}</div>
                    </div>
                </div>
            </div>
            <!--Reactions-->
            <div v-if="message.reactions.length" @click="showModalReactions">
                <div class="row reverse bg-white shadow-1 absolute reaction" :class="message.sender ? 'senderReaction' : 'receiverReaction'">
                    <div class="col q-py-xs" v-for="(messageReaction,index) in message.reactions.slice(0,2)" :key="index">
                        <vue-reaction-emoji :is-active="isActive" is-disabled width="18px" height="18px" :reaction="messageReaction.reaction"/>
                    </div>
                    <div class="col text-caption q-py-xs text-center">
                        {{message.reactions.length}}
                    </div>
                </div>
            </div>

            <!--Remove tools-->
            <div class="row q-pa-xs" v-if="toolsFab">
                <q-fab color="grey" flat icon="more_vert" :direction="message.sender ? 'left' : 'right'" padding="xs" @click="openTools">
                    <q-fab-action color="grey" text-color="white"  icon="fas fa-user-times" @click="showModalConfirmation('removeForMyself')">
                        <q-tooltip anchor="bottom middle" self="top middle" :offset="[10, 10]">
                            Remove for you
                        </q-tooltip>
                    </q-fab-action>
                    <q-fab-action color="grey" text-color="white" icon="fas fa-trash-alt" v-if="message.sender" @click="showModalConfirmation('removeForAll')">
                        <q-tooltip anchor="bottom middle" self="top middle" :offset="[10, 10]">
                            Remove for everyone
                        </q-tooltip>
                    </q-fab-action>
                </q-fab>
            </div>
            <!--Select Reaction-->
            <div class="row q-pa-xs"  v-if="toolsFab && reactFab">
                <q-fab color="grey" flat icon="fas fa-surprise" direction="down" padding="xs" @click="openReaction">
                    <q-fab-action color="white">
                        <vue-reaction-emoji :is-active="isActive" width="30px" height="30px"  @click="react('hate')"         reaction="hate"/>
                        <vue-reaction-emoji :is-active="isActive" width="30px" height="30px"  @click="react('natural')"      reaction="natural"/>
                        <vue-reaction-emoji :is-active="isActive" width="30px" height="30px"  @click="react('disappointed')" reaction="disappointed"/>
                        <vue-reaction-emoji :is-active="isActive" width="30px" height="30px"  @click="react('good')"         reaction="good"/>
                        <vue-reaction-emoji :is-active="isActive" width="30px" height="30px"  @click="react('excellent')"    reaction="excellent"/>
                    </q-fab-action>
                </q-fab>
            </div>
        </div>
        
        <remove-message-confirmation-modal ref="removeMessageConfirmationModalRef" @confirm="confirm" :modalSettings="removeModalSettings"></remove-message-confirmation-modal>
        <reactions-modal ref="reactionsModalRef" :modalSettings="reactionModalSettings"></reactions-modal>
    </div>
</template>
<script>
import { VueFeedbackReaction, VueReactionEmoji  }   from 'vue-feedback-reaction';
import RemoveMessageConfirmationModal               from "../pages/User/Messages/RemoveMessageConfirmationModal";
import ReactionsModal                               from "../pages/User/Messages/ReactionsModal";
import {mapGetters}                                 from 'vuex'
import {GETTER_USER}            	                from '../store/account/getters'
import Message                                      from '../models/Message';


export default {
    name: "GChatMessage.vue",
    props: {
      message         : {type: Object, default: {}}
    },
    components : {
      RemoveMessageConfirmationModal,
      VueFeedbackReaction,
      VueReactionEmoji,
      ReactionsModal
    },
    computed : {
        ...mapGetters({
            user                 : GETTER_USER,
        }), 
        removeModalSettings() {
            return {
                title    : this.type == 'removeForAll' ? 'Remove for everyone' : 'Remove for you',
                btnColor : this.message.chatColor,
                function : 'confirm',
                content  : this.type == 'removeForAll' ? "You'll permanently remove this message for all chat members." : 'This message will be removed for you. Other chat members will still be able to see it.',
            }
        },
        reactionModalSettings() {
            return {
                title        : "Message Reactions",
          	    btnColor     : this.message.chatColor,
            }
        }
    },
    data: () => ({
        isActive: true,
        feedback : '',
        toolsFab : false,
        reactionIsOpen : false,
        toolsIsOpen : false,
        reactFab : true,
        type : '',
    }),
    methods : {
        react(reaction) {
            //get the index
			let index = this.getKeybyValue(this.message.reactions,this.user.id,'user_id');
            
            if(index == -1) { //if user doesn't have a reaction 
                //push the reaction 
                this.message.reactions.push({
                    user_id : this.user.id,
                    reaction : reaction,
                    user : {
                        name : this.user.name
                    }
                })
                //change reaction
                Message.changeReaction({
                    message_id : this.message.id,
                    user_id    : this.user.id,
                    reaction   : reaction,
                    type       : 'save'
                })
            }else { //if user have a reaction
                const messageReaction = {
                    id       : this.message.reactions[index].id,
                    reaction : reaction,
                    type     : ''
                }

                if(this.message.reactions[index].reaction == reaction) { //if reaction is same with the selected reaction, remove it
                    this.message.reactions.splice(index, 1);
                    messageReaction.type = 'remove';
                }else { // if reaction is not the same, overwrite it
                    this.message.reactions[index].reaction = reaction;
                    messageReaction.type = 'update';
                }
                //change reaction
                Message.changeReaction(messageReaction)
            }

        },
        confirm() {
            this.$emit('removeMessage', { messageID : this.message.id, type : this.type, visibilityID : this.message.visibilityID})
        },  
        showModalConfirmation(type){
            this.type = type;
            this.$refs.removeMessageConfirmationModalRef.showRemoveMessageConfirmationModal()
        },
        showModalReactions() {
            this.$refs.reactionsModalRef.showReactionsModal(this.message.reactions)
        },
        openTools () {
            this.toolsIsOpen    = !this.toolsIsOpen;
            this.reactionIsOpen = false;
            this.reactFab       = !this.reactFab
        },
        openReaction() {
            this.reactionIsOpen = !this.reactionIsOpen;
        },
        closeTools() {
            this.toolsFab = this.toolsIsOpen || this.reactionIsOpen ? this.toolsFab : !this.toolsFab
        }
    }
}
</script>
<style scoped>
.senderReaction { 
    left : 55px;
}
.receiverReaction {
    right : 55px;
}
.reaction { 
    border-radius:10px;
    top:25px;
    width : 55px;
    position:relative;
}
</style>