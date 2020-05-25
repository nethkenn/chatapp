import {beforeEnterLogin, beforeEnterUser} from "./route_guards";

const routes = [
  {
    path        : '/',
    component   : () => import('layouts/LoginLayout.vue'),
    beforeEnter : beforeEnterLogin,
  },
  {
    path        : '/user',
    component   : () => import('layouts/UserLayout.vue'),
    beforeEnter : beforeEnterUser,
    children    : [
      { path    : '', component: () => import('pages/User/User.vue'), name : 'user'}
    ]
  },
  {
    path        : '/message/:id',
    component   : () => import('layouts/UserLayout.vue'),
    beforeEnter : beforeEnterUser,
    children    : [
      { path    : '', component: () => import('pages/User/Messages/Message.vue'), name : 'message'}
    ]
  }
]

// Always leave this as last one
if (process.env.MODE !== 'ssr') {
  routes.push({
    path: '*',
    component: () => import('pages/Error404.vue')
  })
}

export default routes
