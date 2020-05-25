export default {
  fetchMessages(userID,conversationInfoID) {
    return axios.post('api/message/fetchMessages', {userID, conversationInfoID})
      .then(res => {
        return res.data.messages
    })  
  },
  sendMessage(data) {
    return axios.post('api/message/sendMessage', data)
    .then(res => {
      return res.data.message
    })  
  },
  removeMessage(data) {
    return axios.post('api/message/removeMessage', data)
    .then(res => {
      return res.data.message
    })  
  },
  changeReaction(data) {
    return axios.post('api/message/changeReaction', data)
    .then(res => {
      return res.data.message
    })  
  },
  saveSeen(data) {
    return axios.post('api/message/saveSeen', data)
    .then(res => {
      return res.data.message
    })  
  },
}
