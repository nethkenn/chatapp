export const MUTATION_SET_RECEIVER     = 'receiver/setReceiver';


export default {
  [MUTATION_SET_RECEIVER]: (state, payload) => {
    state.receiver = payload;
  },
}