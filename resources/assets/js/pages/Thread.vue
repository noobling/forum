<script>
    import Replies from '../components/Replies.vue';
    import SubscribeButton from '../components/SubscribeButton.vue';

    export default {
        components: { Replies, SubscribeButton },
        props: ['initialRepliesCount', 'dataLocked'],
        data() {
            return {
                repliesCount: this.initialRepliesCount,
                locked: this.dataLocked
            }
        },

        methods: {
            lock: function () {
                this.locked = true
                window.events.$emit('LockedThread')
            }
        }
    }
</script>