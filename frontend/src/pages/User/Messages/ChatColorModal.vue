<template>
  <g-modal ref="gModalRef" :title="modalSettings.title">
      <span slot="content">
          <div class="row" v-for="(color,index) in colors" :key="index">
                  <q-btn v-for="n in 14" :key="n" flat round :color="color+'-'+n" icon="fas fa-circle" size="xs" @click="selectColor(color+'-'+n)"/>
          </div>		
      </span>

      <span slot="actions">
        <div class="q-gutter-xs">
          <q-btn :color="modalSettings.btnColor" outline icon="close"  label="close" style="font-size:12px"
                  @click="hideChatColorModal()"/>
        </div>
      </span>
  </g-modal>
</template>

<script>
    import GModal                  from "../../../components/GModal";

    export default {
        name: "ChatColorModal.vue",
        components: {
          GModal
        },
        data: () => ({
            colors : ["red",
                      "pink",
                      "purple",
                      "deep-purple",
                      "indigo",
                      "blue",
                      "light-blue",
                      "cyan",
                      "teal",
                      "green",
                      "light-green",
                      "lime",
                      "yellow",
                      "amber",
                      "orange",
                      "deep-orange",
                      "brown",
                      "grey",
                      "blue-grey"]
        }),
        props : {
            modalSettings : {
                title        : {type: String, default: "Are you sure you want to log out?"},
                function     : {type: String, default: 'confirm'},
                btnColor     : {type: String, default: 'bg-dark'}
            }
        },
        methods : {
          selectColor(color) {
              this.$emit(this.modalSettings.function,color)
              this.$refs.gModalRef.hideModal()
          },
          confirm(){
              this.$emit(this.function)
              this.$refs.gModalRef.hideModal()
          },
          showChatColorModal(){
            this.$refs.gModalRef.showModal()
          },
          hideChatColorModal(){
            this.$refs.gModalRef.hideModal()
          }
        },
    }
</script>

<style scoped>

</style>
