<template>
  <g-modal ref="gModalRef" :title="modalSettings.title">
      <span slot="content">
          <div class="row" v-for="(user,index) in users" :key="index">
              <div class="q-pa-xs">
                    <q-input style="width:300px" v-model="user.nickname" :hint="user.name" dense :color="modalSettings.btnColor"/>
              </div>
          </div>
      </span>

      <span slot="actions">
        <div class="q-gutter-xs">
          <q-btn :color="modalSettings.btnColor" outline icon="close"  label="close" style="font-size:12px"
                  @click="hideEditNicknameModal()"/>
          <q-btn :class="'bg-'+modalSettings.btnColor" icon="check"  flat label="Save"  style="font-size:12px"
                  @click="updateNickname()"/>
        </div>
      </span>
  </g-modal>
</template>

<script>
    import GModal                  from "../../../components/GModal";

    export default {
        name: "EditNicknameModal.vue",
        components: {
          GModal
        },
        data: () => ({
            users : []
        }),
        props : {
            modalSettings : {
                title        : {type: String, default: "Are you sure you want to log out?"},
                function     : {type: String, default: 'confirm'},
                btnColor     : {type: String, default: 'bg-dark'}
            }
        },
        methods : {
          updateNickname() {
              this.$emit(this.modalSettings.function, { users : this.users})
              this.$refs.gModalRef.hideModal()
          },
          showEditNicknameModal(data){
            this.users    = data.users
            this.$refs.gModalRef.showModal()
          },
          hideEditNicknameModal(){
            this.$refs.gModalRef.hideModal()
          }
        },
    }
</script>

<style scoped>

</style>
