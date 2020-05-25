import {QSpinnerGears} from 'quasar'

export default {
  methods: {
    m_showPageLoading(options = {
      spinner      : QSpinnerGears,
      message      : 'Please wait...',
      messageColor : 'white',
      spinnerSize  : 80, // in pixels
      spinnerColor : 'dark'
    }){
      this.$q.loading.show({
        spinner     : options.spinner,
        message     : options.message,
        messageColor: options.messageColor,
        spinnerSize : options.spinnerSize,
        spinnerColor: options.spinnerColor
      });
    },
    m_hidePageLoading(){
      this.$q.loading.hide();
    }
  }
}
