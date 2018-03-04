<template>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
           aria-expanded="false" aria-haspopup="true">
            <span class="glyphicon glyphicon-bell"></span>
        </a>

        <ul v-for="notification in notifications" class="dropdown-menu" v-if="notifications.length">
            <li>
                <a :href="notification.data.link" @click="markAsRead(notification)">{{ notification.data.message }}</a>
            </li>
        </ul>
    </li>
</template>

<script>
   export default {
       data() {
           return {
               notifications: false
           }
       },

       created() {
           axios.get('/profiles/' + window.App.user.name + '/notifications')
               .then(response => this.notifications = response.data);
       },

       methods: {
           markAsRead(notification) {
               axios.delete('/profiles/' + window.App.user.name + '/notifications/' + notification.id);
           }
       }
   }
</script>