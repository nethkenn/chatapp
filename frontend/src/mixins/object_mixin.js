 export default {
    methods: {
      getKeybyValue(object, val, key) {
        return object.findIndex(v => v[key] === val);
      }
    }
  }
  