<template>
    <div v-if="!loading" id="app">
        <navigation></navigation>
        <main>
            <router-view></router-view>
        </main>
        <pmtech-footer></pmtech-footer>
    </div>
    <div v-else id="app">
        Loading...
    </div>
</template>

<script>
    import Navigation from "../components/shared/Navigation";
    import Footer from "../components/shared/Footer";
    import Vue from "vue";

    export default {
        components: {
            PmtechFooter: Footer,
            Navigation
        },
        created() {
            this.loading = true;
            this.$store.dispatch("authenticated")
                .finally(() => {
                    this.loading = false;
                })
        },
        data() {
            return {
                loading: false,
            };
        },
        mounted() {
            const appEl = document.getElementById("app");
            appEl.onclick = () => {
                this.$eventBus.$emit("blur");
            };
        },
        name: "App"
    }
</script>

<style scoped>

</style>
