<template>
    <button @click="subscribe" :class="classes">Subscribe</button>
</template>

<script>
    export default {
        props: ['active'],

        computed: {
            classes() {
                return ['btn', this.active ? 'btn-primary': 'btn-default'];
            }
        },

        methods: {
            subscribe() {
                let requestType = this.active ? 'delete': 'post';

                axios[requestType](location.pathname + '/subscribe')
                    .then(response => {
                        this.active = !this.active;
                        flash(this.active ? 'Subscribed': 'Unsubscribed');
                    })
            }
        }
    }
</script>