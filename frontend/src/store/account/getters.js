export const GETTER_USER     		= 'account/getUser';
export const GETTER_EXPIRES_IN      = 'account/getExpiresIn';
export const GETTER_ACCESS_TOKEN    = 'account/getAccessToken';
export const GETTER_TOKEN_TYPE      = 'account/getTokenType';

export default {
  [GETTER_USER] 		: (state) => state.user,
  [GETTER_EXPIRES_IN]   : (state) => state.expires_in,
  [GETTER_ACCESS_TOKEN] : (state) => state.access_token,
  [GETTER_TOKEN_TYPE]   : (state) => state.token_type,
}	