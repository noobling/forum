<template>
    <ul class="pagination" v-if="shouldPaginate">
        <li v-show="prevUrl">
            <a href="#" rel="prev" @click.prevent="page--">
                <span>&laquo; Previous</span>
            </a>
        </li>
        <li v-show="nextUrl">
            <a href="#" rel="next" @click.prevent="page++">
                <span>Next &raquo;</span>
            </a>
        </li>
    </ul>
</template>
<script>
    export default {
        props: ['data'],

        data() {
            return {
                page: 1,
                prevUrl: false,
                nextUrl: false
            }
        },

        watch: {
            data() {
                this.page = this.data.current_page;
                this.prevUrl = this.data.prev_page_url;
                this.nextUrl = this.data.next_page_url;
            },
            page() {
                this.broadcast().updateUrl();
            }
        },

        computed: {
            shouldPaginate() {
                return !!(this.prevUrl || this.nextUrl);
            }
        },

        methods: {
            broadcast() {
                this.$emit('updated', this.page);

                return this;
            },
            updateUrl() {
                history.pushState(null, null, '?page=' + this.page);
            }
        }
    }
</script>