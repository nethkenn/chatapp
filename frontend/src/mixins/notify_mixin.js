
// Default loading configuration
const defaultOptions = {
  color    : 'indigo',
  icon     : 'check',
  message  : '...',
  position : "bottom-right",
};

export default {
  methods: {
    notify(options) {
      const opts = Object.assign({}, defaultOptions, options);

      this.$q.notify({
        icon     : opts.icon,
        message  : opts.message,
        position : opts.position,
        color    : opts.color
      })
    }
  }
}
