<template></template>

<script>
    export default {
        mounted() {
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
