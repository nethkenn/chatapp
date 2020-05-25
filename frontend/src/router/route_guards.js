export const beforeEnterLogin = (to, from, next) => {
  if(localStorage.getItem('access_token')) {

    next({name: 'user'});
    return 0;
  }

  next();
};

export const beforeEnterUser = (to, from, next) => {
  if(!localStorage.getItem('access_token')) {

    next({name: 'login'});
    return 0;
  }

  next();
};

export default {
  beforeEnterUser,
  beforeEnterLogin
}