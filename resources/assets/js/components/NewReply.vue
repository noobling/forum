<template>
    <div>
        <div v-if="signedIn">
            <div class="form-group">
            <textarea class="form-control"
                      id=""
                      cols="30"
                      rows="10"
                      placeholder="Have something to say?"
                      v-model="body">
            </textarea>
            </div>

            <button type="submit" class="btn btn-default" @click="addReply">Submit</button>
        </div>
        <div v-else>
            <p class="text-center">Please sign in to reply to thread <a href="/login">here</a></p>
        </div>

    </div>
</template>
<script>
    export default {
        props: ['endpoint'],

        data() {
            return {
                body: ''
            }
        },

        computed: {
            signedIn() {
                return window.App.signedIn;
            }
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