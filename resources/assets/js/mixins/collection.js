export default {
    data () {
      return {
          items: []
      }
    },

    methods: {
        add(item) {
            this.items.push(item);
            this.$emit('added');
        },
        remove(index) {
            this.$emit('removed');
            this.items.splice(index, 1);
        }
    }
}