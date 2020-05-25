export const MUTATION_SET_CONVERSATION_SETTINGS           = 'conversation/setConversationSettings';
export const MUTATION_REMOVE_CONVERSATION_SETTINGS        = 'conversation/removeConversationSettings';


export default {
  [MUTATION_SET_CONVERSATION_SETTINGS]: (state, payload) => {
    state.conversationSettings = payload;
  },
  [MUTATION_REMOVE_CONVERSATION_SETTINGS]: (state) => {
    state.conversationSettings         = null;
  }
}