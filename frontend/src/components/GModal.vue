<template>
  <!--MODAL-->
  <q-dialog persistent transition-show="flip-down" transition-hide="flip-up"
            :persistent="isPersistent"
            v-model="isShowModal">
    <q-card>
      <!--TITLE-->
      <q-card-section v-if="title != ''">
        <span class="text-weight-medium" style="font-size:12px"><i :class="icon"></i> {{title}}</span>
      </q-card-section>
      <q-separator />
      <!--CONTENT-->
      <q-card-section class="row items-center bg-white text-dark scroll" style="max-height: 70vh">
          <slot name="content"/>
      </q-card-section>

  
      <q-card-actions align="right" class="bg-white text-white">
          <slot name="actions"/>
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script>
  export default {
    name: "RModal.vue",
    props: {
      title        : {type: String, default: ''},
      isPersistent : {type: Boolean, default: true},
      icon         : {type: String, default: ''}
    },
    data: () => ({
      isShowModal : false,
      isLoading   : false,
      thumbStyle: {
        right: '1px',
        borderRadius: '5px',
        backgroundColor: '#027be3',
        width: '5px',
        opacity: 0.75
      },
      barStyle: {
        right: '1px',
        borderRadius: '9px',
        backgroundColor: '#027be3',
        width: '9px',
        opacity: 0.2
      }
    }),
    methods: {
      showModal(){
        this.isShowModal = true;
      },
      hideModal(){
        this.isShowModal = false;
      },
      showLoading() {
        this.isLoading = true;
      },
      hideLoading() {
        this.isLoading = false;
      }
    }
  }
</script>

<style scoped>

</style>
