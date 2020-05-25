"use strict";

import Vue from 'vue';
import axios from "axios";

// Full config:  https://github.com/axios/axios#request-config
var AUTH_TOKEN = localStorage.getItem('token_type') + " " + localStorage.getItem('access_token') || '';

axios.defaults.baseURL = 'http://chatapp.test/';
axios.defaults.headers.common['Authorization'] = AUTH_TOKEN;
axios.defaults.headers.post['Content-Type'] = 'application/json';

let config = {
  // baseURL: process.env.baseURL || process.env.apiUrl || ""
  // timeout: 60 * 1000, // Timeout
  // withCredentials: true, // Check cross-site Access-Control
};

const _axios = axios.create(config);

_axios.interceptors.request.use(
  config => {
    config.headers['Authorization'] =  "Bearer "+ localStorage.getItem('access_token');
    return config;
  },
  error => {
    console.log(error)
    return Promise.reject(error);
  }
);

_axios.interceptors.response.use((response) => {
  return response;
}, (error) => {
  if(error.response.status == 401) {
    localStorage.clear();
    window.location.reload();
  }
  return Promise.reject(error);
});

Plugin.install = function(Vue, options) {
  Vue.axios = _axios;
  window.axios = _axios;
  Object.defineProperties(Vue.prototype, {
    axios: {
      get() {
        return _axios;
      }
    },
    $axios: {
      get() {
        return _axios;
      }
    },
  });
};


export default ({ Vue }) => {
  Vue.use(Plugin);
}