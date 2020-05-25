<template>
  <g-modal ref="gModalRef">
      <span slot="content">
        <q-avatar :icon="icon" :color="color" text-color="white" />
        <span class="q-ml-sm text-caption">{{title}}</span>
      </span>

      <span slot="actions">
        <div class="q-gutter-xs">
          <q-btn class="bg-white text-dark" outline icon="close"  label="close" style="font-size:12px"
                  @click="hideConfirmationModal()"/>
          <q-btn :class="btnColor" icon="check"  flat label="Confirm"  style="font-size:12px"
                  @click="confirm()"/>
        </div>
      </span>
  </g-modal>
</template>

<script>
    import GModal                  from "../../components/GModal";

    export default {
        name: "ConfirmationModal.vue",
        components: {
          GModal
        },
        props : {
          title        : {type: String, default: "Are you sure you want to log out?"},
          icon         : {type: String, default: "fas fa-sign-out-alt"},
          color        : {type: String, default: 'dark'},
          btnColor     : {type: String, default: 'bg-dark'},
          function     : {type: String, default: 'confirm'}
        },
        methods : {
          confirm(){
              this.$emit(this.function)
              this.$refs.gModalRef.hideModal()
          },
          showConfirmationModal(){
            this.$refs.gModalRef.showModal()
          },
          hideConfirmationModal(){
            this.$refs.gModalRef.hideModal()
          }
        },
    }
</script>

<style scoped>

</style>
