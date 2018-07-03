<template>
    <div :id="'reply-'+id" class="panel" :class="isBest? 'panel-success': 'panel-default'">
        <div class="panel-heading">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/'+data.owner.name">
                        <div v-text="data.owner.name"></div>
                    </a>
                    said {{ createdTime }}
                </h5>
                <span v-if="signedIn">
                    <favourite :reply="data"></favourite>
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


        <div class="panel-footer level" v-if="canUpdate">
            <button type="submit" class="btn btn-danger mr-1" @click="destroy">Delete</button>
            <button class="btn btn-warning" @click="editing = true">Update</button>
            <button class="btn btn-default ml-a" @click="markBestReply" v-show="!isBest">Best Reply</button>
        </div>
    </div>
</template>

<script>
    import Favourite from './Favourite.vue';
    import moment from 'moment';

    export default {
        props: ['data'],

        components: {Favourite},

        data() {
            return {
                editing: false,
                id: this.data.id,
                body: this.data.body,
                isBest: false
            };
        },

        computed: {
            signedIn() {
                return window.App.signedIn;
            },
            canUpdate() {
                return this.authorize(user => user.id === this.data.user_id);
            },
            createdTime() {
                return moment(this.data.created_at).fromNow() + '...';
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

                this.$emit('deleted', this.data.id);

                $(this.$el).fadeOut(300, () => {
                    flash('Deleted!');
                });

            },

            markBestReply() {
                this.isBest = true;
            }
        }
    }
</script>