<template>
    <div>
        <div v-if="!registered">
            <register-form @success="onRegistrationSuccess"></register-form>
        </div>
        <div v-else>
            <p>
                {{ $t("Thank you for registering.") }}
            </p>
            <activation-link></activation-link>
        </div>
    </div>
</template>

<script>
    import ActivationLink from "../../components/public/authn/ActivationLink";
    import RegisterForm from "../../components/public/authn/RegisterForm";

    export default {
        components: {
            ActivationLink,
            RegisterForm
        },
        data() {
            return {
                email: "",
                registered: false,
            };
        },
        methods: {
            onRegistrationSuccess(payload) {
                this.email = payload;
                this.registered = true;
            }
        },
        name: "Register"
    }
</script>

<style scoped>

</style>
