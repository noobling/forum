<template>
    <div>
        <div v-if="signedIn">
            <div class="form-group">
            <textarea class="form-control"
                      id="body"
                      cols="30"
                      rows="10"
                      placeholder="Have something to say?"
                      v-model="body">
            </textarea>
            </div>

            <p v-if="threadLocked">Sorry Thread has been locked you cannot reply!</p>
            <button type="submit" class="btn btn-default" @click="addReply" v-else>Submit</button>
        </div>
        <div v-else>
            <p class="text-center">Please sign in to reply to thread <a href="/login">here</a></p>
        </div>

    </div>
</template>
<script>
    import 'at.js';
    import 'jquery.caret';

    export default {
        props: ['endpoint'],

        data() {
            return {
                body: '',
                threadLocked: this.$parent.locked
            }
        },

        computed: {
            signedIn() {
                return window.App.signedIn;
            }
        },

        created () {
            window.events.$on('LockedThread', () => {
                this.threadLocked = true
            })
        },

        mounted() {
            $('#body').atwho({
                at: '@',
                delay: 750,
                callbacks: {
                    remoteFilter: function(query, callback) {
                        $.getJSON('/api/users', {name: query}, function(users) {
                            callback(users);
                        })
                    }
                }
            })
        },

        methods: {
            addReply() {
                axios.post(this.endpoint, { body: this.body })
                    .catch(error => {
                        flash(error.response.data, 'danger');
                    })
                    .then(response=> {
                        this.body = '';
                        this.$emit('created', response.data);

                        flash('Created reply');
                    })

            }
        }

    }
</script>