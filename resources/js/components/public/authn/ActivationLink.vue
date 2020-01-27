<template>
    <div>
        <p>
            You should have received an email with an account activation link. Please click that link in order to activate your account.
        </p>
        <p v-if="email">
            If you want to generate a new email with the link, please click <button @click="onSendNewActivationLink">here</button>.
        </p>
    </div>
</template>

<script>
    export default {
        methods: {
            onSendNewActivationLink() {
                this.$axios.post("/api/v1/verification/resend", {
                    email: this.email
                });
            },
        },
        name: "ActivationLink",
        props: [ "email" ]
    }
</script>

<style scoped>

</style>
