<template>
    <div>
        <div class="level">
            <img :src="avatar" alt="" width="50" height="50" class="mr-1">
            <h2 v-text="user.name"></h2>
        </div>


        <form v-if="canUpdate" enctype="multipart/form-data">
            <input type="file" name="avatar" @change="onFileChange">

        </form>
    </div>


</template>

<script>
    export default {
        name: "avatars-form",

        props: ['user'],

        computed: {
            canUpdate() {
                return this.authorize(user => user.id === this.user.id)
            }
        },

        data() {
            return {
                avatar: this.user.avatar_path
            }
        },

        methods: {
            onFileChange(e) {
                if (! e.target.files.length) return;

                let file = e.target.files[0];

                let reader = new FileReader();

                reader.readAsDataURL(file);

                reader.onload = e => {
                    this.avatar = e.target.result;
                };

                this.upload(file);
            },

            upload(file) {
                let formData = new FormData();
                formData.append('avatar', file);

                axios.post(`/api/users/${this.user.name}/avatars`, formData)
                    .then(() => {
                        flash('Avatar updated!')
                    })
            }
        }
    }


</script>

<style scoped>

</style>