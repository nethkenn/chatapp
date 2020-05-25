<template>
  <g-modal ref="gModalRef" :title="modalSettings.title">
      <span slot="content">
        <q-tabs
          v-model="tab"
          dense
          class="text-grey"
          :active-color="modalSettings.btnColor"
          :indicator-color="modalSettings.btnColor"
          align="justify"
          narrow-indicator
        >
          <q-tab v-for="(reaction,key) in reactions" :key="key" :name="key">
              <div class="text-caption absolute" style="left:35px">{{reactions[key].length}}</div>
              <vue-reaction-emoji v-if="key !== 'all'" is-active is-disabled width="18px" height="18px" :reaction="key"/>
              <div class="text-caption" v-else>All </div>
          </q-tab>
        </q-tabs>
        <q-separator/>

        
        <q-tab-panels v-model="tab" animated transition-prev="fade" transition-next="fade" style="overflow:hidden">
          <q-tab-panel v-for="(reaction,key) in reactions" :key="key" :name="key" style="width:250px">
            <div class="row items-center" v-for="(react,index) in reaction" :key="index">
              <div class="col-4">
                <q-avatar>
                  <img :src="`https://scontent.fmnl2-1.fna.fbcdn.net/v/t1.0-9/s960x960/56372190_2563424680338680_3386899287072833536_o.jpg?_nc_cat=111&_nc_sid=85a577&_nc_eui2=AeHMnKwucPHNKb2c4MTQlGrDyec-h4WGkjzJ5z6HhYaSPKLcj_6t4v1nLdmYRQSHi9RX4YMGx1KQ4arWafetG8bV&_nc_oc=AQlIHNORZsMORouROrw73odHDoTiaxUXeEqAEjrRkEbEg3RQvTRknMRDAganUxLsoko&_nc_ht=scontent.fmnl2-1.fna&_nc_tp=7&oh=95734e36d8b7d0e4b929b3611931524e&oe=5ECC0DE5`">
                </q-avatar> 
                <vue-reaction-emoji class="absolute vuereaction" is-active is-disabled width="18px" height="18px" :reaction="react.reaction"/>
              </div>
              <div class="col">
                {{react.user.name}}
              </div>
            </div>
          </q-tab-panel>
        </q-tab-panels>
      </span>

      <span slot="actions">
        <div class="q-gutter-xs">
          <q-btn :color="modalSettings.btnColor" outline icon="close"  label="close" style="font-size:12px"
                  @click="hideReactionsModal()"/>
        </div>
      </span>
  </g-modal>
</template>

<script>
    import GModal                                       from "../../../components/GModal";
    import { VueFeedbackReaction, VueReactionEmoji  }   from 'vue-feedback-reaction';


    export default {
        name: "ReactionsModal.vue",
        components: {
          GModal,
          VueReactionEmoji
        },
        data: () => ({
            tab: 'all',
            rawReactions : []
        }),
        props : {
            modalSettings : {
                title        : {type: String, default: "Are you sure you want to log out?"},
                function     : {type: String, default: 'confirm'},
                btnColor     : {type: String, default: 'bg-dark'}
            }
        },
        computed : {
          reactions() {
            if(this.rawReactions.length) {
                const distinctReactions =  this.rawReactions.reduce((res,val) => {
                  res[val.reaction] = [...res[val.reaction] || [], val];
                  return res
                }, {})

                const all = this.rawReactions.map(v => {
                  return {
                    reaction : v.reaction,
                    user     : v.user
                  }
                })
                
                return {...{all},...distinctReactions };
            }
          }
        },
        methods : {
          showReactionsModal(reactions){
            this.rawReactions = reactions;
            this.$refs.gModalRef.showModal()
          },
          hideReactionsModal(){
            this.$refs.gModalRef.hideModal()
          }
        },
    }
</script>

<style scoped>
.vuereaction { 
  left:30px;
  bottom:15px;
  position:relative;
  background-color : white;
  border-radius: 15px;;
}
</style>
