<template></template>

<script>
    export default {
        mounted() {
            window.localStorage.removeItem("access_token");
            window.localStorage.removeItem("token_type");
            this.$axios.defaults.baseURL = "https://" +
                window.location.hostname;
            this.$axios.defaults.headers.common["Authorization"] = "";
            this.$axios.get("airlock/csrf-cookie")
                .then(() => {
                    this.$axios.post("logout");
                });
            this.$store.dispatch("loggedOut");
            this.$router.push("/");
        },
        name: "Logout"
    }
</script>

<style scoped>

</style>
