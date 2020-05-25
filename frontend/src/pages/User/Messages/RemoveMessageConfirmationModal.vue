<template>
  <g-modal ref="gModalRef" :title="modalSettings.title">
      <span slot="content">
          <div class="text-caption">
              {{modalSettings.content}}
          </div>
      </span>

      <span slot="actions">
        <div class="q-gutter-xs">
          <q-btn :color="modalSettings.btnColor" outline icon="close"  label="close" style="font-size:12px"
                  @click="hideRemoveMessageConfirmationModal()"/>
          <q-btn :class="'bg-'+modalSettings.btnColor" icon="check"  flat label="Confirm"  style="font-size:12px"
                  @click="confirm()"/>
        </div>
      </span>
  </g-modal>
</template>

<script>
    import GModal                  from "../../../components/GModal";

    export default {
        name: "RemoveMessageConfirmationModal.vue",
        components: {
          GModal
        },
        props : {
            modalSettings : {
                title        : {type: String, default: "Remove for you"},
                function     : {type: String, default: 'confirm'},
                content      : {type: String, default: ''},
                btnColor     : {type: String, default: 'bg-dark'}
            }
        },
        methods : {
          confirm(){
              this.$emit(this.modalSettings.function)
              this.$refs.gModalRef.hideModal()
          },
          showRemoveMessageConfirmationModal(){
            this.$refs.gModalRef.showModal()
          },
          hideRemoveMessageConfirmationModal(){
            this.$refs.gModalRef.hideModal()
          }
        },
    }
</script>

<style scoped>

</style>
