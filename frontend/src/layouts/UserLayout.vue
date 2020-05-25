<template>
  <div>
    <!--HEADER-->
    <q-layout view="lHh lpr lFf" class="shadow-2 rounded-borders overflow-hidden">
      <q-header elevated class="bg-dark">
        <q-toolbar>
          <q-toolbar-title>
            <q-btn flat @click="drawer = !drawer" round dense icon="menu" />
            Chats
          </q-toolbar-title>
          <q-btn-dropdown size="sm" flat :label="user.name">
            <q-list>
              <q-item clickable v-close-popup @click.native="showModalConfirmation">
                <q-item-section>
                  <q-item-label><i class="fas fa-sign-out-alt"></i> Logout</q-item-label>
                </q-item-section>
              </q-item>
            </q-list>
          </q-btn-dropdown>
        </q-toolbar>
      </q-header>
      <!--LOAD BAR-->
      <q-ajax-bar
          ref="bar"
          position="top"
          color="dark"
          size="3px"
          skip-hijack
        />

      <!--CONVERSATIONS-->
      <q-drawer
             v-model="drawer"
             show-if-above
             :width="300"
             :breakpoint="500"
             bordered
             content-class="bg-white"
           >
           		<!--LOADER-->
            <div class="q-pa-md absolute-center" :class="{ hidden : isHidden }">
                <q-spinner-gears color="dark" size="5.5em"/>
            </div>
             <q-scroll-area class="fit">
                <q-list bordered>
                    <q-item v-for="conversation in conversations" :key="conversation.conversationInfoId" class="q-my-sm" clickable v-ripple
                    @click="goTo(conversation)"
                    >
                      <q-item-section avatar>
                         <q-avatar>
                            <img :src="`https://scontent.fmnl2-1.fna.fbcdn.net/v/t1.0-9/s960x960/56372190_2563424680338680_3386899287072833536_o.jpg?_nc_cat=111&_nc_sid=85a577&_nc_eui2=AeHMnKwucPHNKb2c4MTQlGrDyec-h4WGkjzJ5z6HhYaSPKLcj_6t4v1nLdmYRQSHi9RX4YMGx1KQ4arWafetG8bV&_nc_oc=AQlIHNORZsMORouROrw73odHDoTiaxUXeEqAEjrRkEbEg3RQvTRknMRDAganUxLsoko&_nc_ht=scontent.fmnl2-1.fna&_nc_tp=7&oh=95734e36d8b7d0e4b929b3611931524e&oe=5ECC0DE5`">

                            <q-icon v-if="conversation.userInfo.status == 'online'"
                                    class="absolute" 
                                    size="11px" 
                                    name="fas fa-circle" 
                                    color="positive" 
                                    style="top: 30px; left: 29px">
                                     <q-tooltip>
                                       Active 
                                     </q-tooltip>
                            </q-icon>
                         </q-avatar>

                      </q-item-section>

                      <q-item-section>
                        <q-item-label class="text-dark" :class="{'text-weight-bold' : conversation.notSeen}">{{ conversation.userInfo.nickname }}</q-item-label>
                        <q-item-label caption lines="1" :class="{'text-weight-bold text-dark' : conversation.notSeen}">{{ conversation.lastMessage }}</q-item-label>
                      </q-item-section>

                      <q-item-section side>
                        <div class="text-caption">
                          {{conversation.timeSent}} 
                        </div>
                      </q-item-section>
                    </q-item>

           <!--          <q-item class="q-my-sm" clickable v-ripple>
                      <q-item-section avatar>
                         <q-avatar>
                           <img :src="`https://scontent.fmnl2-1.fna.fbcdn.net/v/t1.0-9/s960x960/56372190_2563424680338680_3386899287072833536_o.jpg?_nc_cat=111&_nc_sid=85a577&_nc_eui2=AeHMnKwucPHNKb2c4MTQlGrDyec-h4WGkjzJ5z6HhYaSPKLcj_6t4v1nLdmYRQSHi9RX4YMGx1KQ4arWafetG8bV&_nc_oc=AQlIHNORZsMORouROrw73odHDoTiaxUXeEqAEjrRkEbEg3RQvTRknMRDAganUxLsoko&_nc_ht=scontent.fmnl2-1.fna&_nc_tp=7&oh=95734e36d8b7d0e4b929b3611931524e&oe=5ECC0DE5`">
                         </q-avatar>
                      </q-item-section>

                      <q-item-section>
                        <q-item-label class="text-weight-bold">Good Intention</q-item-label>
                        <q-item-label lines="1" class="text-weight-bold ">asdasdsda</q-item-label>
                      </q-item-section>

                      <q-item-section side>
                        <div class="text-caption">
                          Sun
                        </div>
                      </q-item-section>
                    </q-item> -->
                  </q-list>
             </q-scroll-area>
           </q-drawer>

      <!--MESSAGES-->
      <q-page-container>
        <q-page>
          <router-view/>
        </q-page>
      </q-page-container>
    </q-layout>
    <confirmation-modal ref="confirmationModalRef" @confirm="logout"></confirmation-modal>
  </div>
</template>

<script>
  import {mapGetters}                 from 'vuex'
  import ConfirmationModal            from "../pages/User/ConfirmationModal";
  import Conversation                 from '../models/Conversation';
  import {GETTER_USER}                from '../store/account/getters'
  import { MUTATION_SET_RECEIVER }                  from "../store/receiver/mutations";
  import { MUTATION_REMOVE_USER_TOKEN }             from "../store/account/mutations";
  import { MUTATION_REMOVE_CONVERSATION_SETTINGS,
           MUTATION_SET_CONVERSATION_SETTINGS }     from "../store/conversation_settings/mutations";



  export default {
    name: 'UserLayout',
    components : {
      ConfirmationModal
    },
    data: () => ({
		  isHidden  : true,
      isLoading : false,
      drawer    : false,
      rawConversations : {},
    }),
    methods: {
      formatTimeSent(rawDate) {
        let days      = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        //If date is equals to today date set time (00:00) else set to day (Mon, Tue)
        return new Date(rawDate).setHours(0,0,0,0) == new Date().setHours(0,0,0,0) ? 
              new Date(rawDate).toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true }) 
            : days[new Date(rawDate).getDay()];
      },
      myTweak (offset) {
        return { minHeight: offset ? `calc(100vh - ${offset}px)` : '100vh' }
      },
      showModalConfirmation(){
        this.$refs.confirmationModalRef.showConfirmationModal()
      },
      logout() {
        //leave channels
        window.Echo.leave('userStatus');
        window.Echo.leave('messageSent');
        window.Echo.leave('conversationChange');
        //start load
        this.m_showPageLoading();
        //call api
        axios.post('api/logout')
          .then(res => {
            //REMOVE USER AND TOKEN DATA
            this.$store.commit(MUTATION_REMOVE_USER_TOKEN);
            this.$store.commit(MUTATION_REMOVE_CONVERSATION_SETTINGS);

            //change route
            this.$router.push('/')
        }).finally(()=>{
          //hide loading
          this.m_hidePageLoading();
        });
      },
      async fetchConversations(hasLoader) {
			//if has loader : show loader
        if(hasLoader) {
      		this.isHidden = false;
          this.rawConversations = await Conversation.fetchConversations(this.user.id)
          this.isHidden = true;
        }else {
          this.rawConversations = await Conversation.fetchConversations(this.user.id)
        }
      },
      async mutateConversationSettings(userID, conversation) {
        if(conversation.conversationInfoID == 0) {
          //store default to vuex
        	this.$store.commit(MUTATION_SET_CONVERSATION_SETTINGS, {
            chatColor : 'dark',
            receiver :  {
              id                   : 0,
              conversation_info_id : 0,
              nickname             : conversation.userInfo.name,
              user_id              : conversation.userInfo.id
            },
            sender : {
              id                   : 0,
              conversation_info_id : 0,
              nickname             : this.user.name,
              user_id              : userID
            }
          });
        }else {
        //fetch conversation settings and store to vuex
          const conversationSettings = await Conversation.fetchConversationSettings({
              userID             : userID,
              receiverID         : conversation.userInfo.id,
              conversationInfoID : conversation.conversationInfoID
            });

            this.$store.commit(MUTATION_SET_CONVERSATION_SETTINGS, conversationSettings)
        }
	  	},
      async goTo(conversation) {
        //add loading to top
        this.$refs.bar.start();
        //get and set conversationSettings 
        await this.mutateConversationSettings(this.user.id, conversation)
        //stop loading
        this.$refs.bar.stop()
        //store data to vuex
        this.$store.commit(MUTATION_SET_RECEIVER, conversation.userInfo);
        //change route
        this.$router.push({name : 'message', params : {id : conversation.conversationInfoID}})
      }
    },
    async mounted () {
      //fetch conversations
      await this.fetchConversations(true);
	  	//track whether other user logon : listen to userStatus
      window.Echo.private("userStatus").listen("StatusEvent", (e) => {
            this.fetchConversations();
       });
    },
    computed : {
      ...mapGetters({
        user     : GETTER_USER
      }),
      conversations() {
          if(this.rawConversations.length)  
          {
            return this.rawConversations.map(val => (
              {
                    userInfo            : {
                      id          : val.id,
                      status      : val.status,
                      lastActive  : val.lastActive,
                      name        : val.name,
                      nickname    : val.conversations.length ? val.conversations[0].nickname : val.name
                    },
                    notSeen             : val.conversations.length ? ((!val.conversations[0].conversation_info.messages[val.conversations[0].conversation_info.messages.length - 1].message_seen.length) && val.conversations[0].conversation_info.messages[val.conversations[0].conversation_info.messages.length - 1].user_id !== this.user.id ? true : false) : false,
                    conversationInfoID  : val.conversations.length ? val.conversations[0].conversation_info_id : 0,
                    messages            : val.conversations.length ? val.conversations[0].conversation_info.messages : [],
                    lastMessage         : val.conversations.length ? val.conversations[0].conversation_info.messages[val.conversations[0].conversation_info.messages.length - 1].message : '',
                    timeSent            : val.conversations.length ? this.formatTimeSent(val.conversations[0].conversation_info.messages[val.conversations[0].conversation_info.messages.length - 1].timeSent) : ''
              }
            ));
          }
      }
    },
  }
</script>
