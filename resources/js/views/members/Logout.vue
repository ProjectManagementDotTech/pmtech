<template></template>

<script>
    export default {
        mounted() {
            window.localStorage.removeItem("access_token");
            window.localStorage.removeItem("token_type");
            this.$axios.defaults.headers.common["Authorization"] = "";
            this.$store.dispatch("loggedOut");
            this.$router.push("/");
        },
        name: "Logout"
    }
</script>

<style scoped>

</style>
