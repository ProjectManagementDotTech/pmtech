<template>
    <div>
        Dashboard
        <hr />
        {{ $t("message" )}}
    </div>
</template>

<script>
    export default {
        data() {
            return {
                user: {}
            };
        },
        mounted() {
            this.$axios.get("/api/v1/user")
            .then(response => {
                console.dir(response);
                this.user = response.data;
            })
            .catch(error => {
                console.dir(error);
            })
            .finally(() => {
                console.dir(this.user);
            });
        },
        name: "Dashboard"
    }
</script>

<style scoped>

</style>
