
import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');


window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '945e24a09495ef1e1409',
    cluster: 'ap1',
    authEndpoint: 'http://chatapp.test/broadcasting/auth',
    auth: {
        headers: {
          Authorization: "Bearer "+ localStorage.getItem('access_token'),
        },
      },
});

