<template>
    <div :id="'reply-'+id" class="panel" :class="isBest? 'panel-success': 'panel-default'">
        <div class="panel-heading">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/'+reply.owner.name">
                        <div v-text="reply.owner.name"></div>
                    </a>
                    said {{ createdTime }}
                </h5>
                <span v-if="signedIn">
                    <favourite :reply="reply"></favourite>
                </span>
            </div>

        </div>
        <div class="panel-body">
            <div v-if="editing">
                <div class="form-group">
                     <textarea class="form-control" v-model="body">

                    </textarea>
                </div>

                <button class="btn btn-primary btn-xs" @click="update">Update</button>
                <button class="btn btn-link btn-xs" @click="editing = false">Cancel</button>

            </div>
            <div v-else>
                <div v-html="body">
                </div>
            </div>
        </div>


        <div class="panel-footer level">
            <div v-if="authorize('owns', reply)">
                <button type="submit" class="btn btn-danger mr-1" @click="destroy">Delete</button>
                <button class="btn btn-warning" @click="editing = true">Update</button>
            </div>

            <button class="btn btn-default ml-a" @click="markBestReply" v-show="!isBest && authorize('owns', reply.thread)">
                Best Reply
            </button>

        </div>
    </div>
</template>

<script>
    import Favourite from './Favourite.vue';
    import moment from 'moment';

    export default {
        props: ['reply'],

        components: {Favourite},

        created () {
            window.events.$on('best-reply-selected', id => {
                this.isBest = (this.id === id)
            })
        },

        data() {
            return {
                editing: false,
                id: this.reply.id,
                body: this.reply.body,
                isBest: this.reply.isBest
            };
        },

        computed: {
            createdTime() {
                return moment(this.reply.created_at).fromNow() + '...';
            }
        },

        methods: {
            update() {
                axios.patch('/replies/' + this.id, {
                        body: this.body
                    })
                    .then(response => {
                        flash('Updated!');
                    })
                    .catch(error => {
                       flash('Failed to update please notify an admin', 'danger');
                    });
                this.editing = false;
            },

            destroy() {
                axios.delete('/replies/' + this.id);

                this.$emit('deleted', this.id);

                $(this.$el).fadeOut(300, () => {
                    flash('Deleted!');
                });

            },

            markBestReply() {
                axios.post('/replies/' + this.reply.id + '/best')
                    .then(() => {
                        window.events.$emit('best-reply-selected', this.id)
                    })
            }
        }
    }
</script>