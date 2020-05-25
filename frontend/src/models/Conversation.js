export default {
    fetchConversations(id) {
      return axios.post('api/conversation/fetchConversations', {userID : id})
        .then(res => {
          return res.data.conversation
      })  
    },
    fetchConversationSettings(data) {
      return axios.post('api/conversation/fetchConversationSettings', data)
      .then(res => {
         return res.data.conversationSettings
     })  
    },
    saveChatColor(data) {
      return axios.post('api/conversation/saveChatColor', data)
      .then(res => {
        return res.data.message
     })  
    },
    saveNickname(data) {
      return axios.post('api/conversation/saveNickname', data)
      .then(res => {
        return res.data.message
     })  
    }
  }
  