<template>
    <div :id="'reply-'+id" class="panel panel-default">
        <div class="panel-heading">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/'+data.owner.name">
                        <div v-text="data.owner.name"></div>
                    </a>
                    said {{ data.created_at }}
                </h5>
                <!--@can('update', $reply)-->
                <!--<favourite :reply="{{ $reply }}"></favourite>-->
                <!--@endcan-->
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
                <div v-text="body">
                </div>
            </div>
        </div>


        <div class="panel-footer level">
            <button type="submit" class="btn btn-danger mr-1" @click="destroy">Delete</button>
            <button class="btn btn-warning" @click="editing = true">Update</button>
        </div>
    </div>
</template>

<script>
    import Favourite from './Favourite.vue';

    export default {
        props: ['data'],

        components: { Favourite },

        data() {
            return {
                editing: false,
                id: this.data.id,
                body: this.data.body,
            };
        },

        methods: {
            update() {
                axios.patch('/replies/' + this.id,
                    {
                        body: this.body
                    });
                this.editing = false;

                flash('Updated!');
            },

            destroy () {
                axios.delete('/replies/' + this.id);

                this.$emit('deleted', this.data.id);

                $(this.$el).fadeOut(300, () => {
                    flash('Deleted!');
                });

            }
        }
    }
</script>