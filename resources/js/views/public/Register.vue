<template>
    <div>
        <div v-if="!registered">
            <register-form @success="onRegistrationSuccess"></register-form>
        </div>
        <div v-else>
            <panel>
                <p class="font-semibold">
                    {{ $t("Thank you for registering.") }}
                </p>
                <activation-link></activation-link>
            </panel>
        </div>
    </div>
</template>

<script>
    import ActivationLink from "../../components/public/authn/ActivationLink";
    import Panel from "../../components/shared/Panel";
    import RegisterForm from "../../components/public/authn/RegisterForm";

    export default {
        components: {
            ActivationLink,
            Panel,
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
