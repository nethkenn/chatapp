export const MUTATION_SET_USER           = 'account/setUser';
export const MUTATION_SET_TOKEN          = 'account/setToken';
export const MUTATION_REMOVE_USER_TOKEN  = 'account/removeUserToken';


export default {
  [MUTATION_SET_USER]: (state, payload) => {
    localStorage.setItem('user', JSON.stringify(payload));
    state.user = payload;
  },
  [MUTATION_SET_TOKEN]: (state, payload) => {
    state.expires_in   = payload.expires_in;
    state.access_token = payload.access_token;
    state.token_type   = payload.token_type;

    localStorage.setItem('expires_in', payload.expires_in);
    localStorage.setItem('access_token', payload.access_token);
    localStorage.setItem('token_type', payload.token_type);
  },
  [MUTATION_REMOVE_USER_TOKEN]: (state) => {
    state.user         = null;
    state.expires_in   = null;
    state.access_token = null;
    state.token_type   = null;
    localStorage.clear();
  }
}