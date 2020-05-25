<template>
  <div>
      <q-layout view="lHh lpr lFf" style="height: 100vh" class="shadow-2 rounded-borders">
          <div class="row justify-center" style="height:inherit">
            <div class="col-8">
              <div class="row q-px-lg q-mx-lg">
                <div class="col-12">
                      <img src="../assets/mensahe.png" width="100px"/>
                </div>
                <div class="col-12 text-center">
                  <q-img
                      src="../assets/vector.svg"
                      style="max-width: 600px; height: 400px;"
                      contain
                    />
                </div>
                <div class="col-12">
                    <div class="text-caption q-pt-md text-italic text-center">
                      A simple chat app made with Laravel and Vue.js. In this project I've learned to use <br> Broadcasting, Laravel Echo, Pusher.js, and SOLID principles.
                    </div>
                </div>
              </div>
            </div>
            <div class="col-4" style="background-color:#edeff8">
                <div class="row items-center justify-center q-pt-lg q-mt-lg">
                   <div class="col-8 q-pa-md">
                      Sign In 
                  </div>
                  <div class="col-8 q-pa-md">
                      <q-input v-model="form.email" stack-label  dense label="Email" color="indigo-6" />
                  </div>
                  <div class="col-8 q-pa-md">
                      <q-input type="password" v-model="form.password" stack-label  dense label="Password" color="indigo-6" />
                      <div class="float-right">
                        <q-btn flat label="Forgot password ?" no-caps size="sm"/>
                      </div>
                  </div>
                  <div class="col-8"  v-if="error">
                      <div class="text-caption text-red text-center">
                          Couldn't find your account
                      </div>
                  </div>
                  <div class='col-8 q-pa-md'>
                    <q-btn @click="login" style="width: 240px" color="indigo-6" size="md"  label="Login" icon="fas fa-sign-in-alt" no-caps/>
                  </div>
                  <div class='col-8 q-px-md text-caption text-center text-dark'>
                    Don't have an account? <q-btn flat label="Sign up" class="btn-fixed-width text-blue" no-caps size="sm"/>
                  </div>
                </div>
            </div>
          </div>
      </q-layout>

   </div>
</template>


<script>

import {
  MUTATION_SET_USER,
  MUTATION_SET_TOKEN
} from "../store/account/mutations";

export default {
  name: 'LoginLayout',
  data: () => ({
    form : {
        email : '',
        password : '',
    },
    error    : false
  }),
  methods: {
      login () {
        this.m_showPageLoading();
        axios.post('api/login', this.form  )
          .then(res => {
            // commit here
            this.$store.commit(MUTATION_SET_USER, res.data.user);
            this.$store.commit(MUTATION_SET_TOKEN, {
              expires_in : res.data.expires_in,
              token_type : res.data.token_type,
              access_token : res.data.access_token
            });
            //set token for pusher
            window.Echo.options.auth.headers.Authorization = "Bearer "+ localStorage.getItem('access_token');
            //go to user
            this.$router.push({name : 'user'})
        })
        .catch(err => {
          console.log(err)
          this.error = true;
        })
       .finally(()=>{
          this.m_hidePageLoading();
        });
      }
    }
}
</script>
<style scoped>
</style>