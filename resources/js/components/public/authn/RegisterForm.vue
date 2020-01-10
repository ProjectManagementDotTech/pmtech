<template>
    <validation-observer class="needs-validation" novalidate
                         ref="validationObserver" tag="div"
                         v-slot="{ invalid }">
        <panel>
            <template v-slot:header>
                <h1>{{ $t("Register") }}</h1>
            </template>
            <div>
                <label for="name">{{ $t("Name") }}</label>
                <validation-provider name="name" rules="required" v-slot="{ errors }">
                    <input name="name" id="name" type="text" v-model="name" autofocus />
                    <div>{{ $t(errors[0]) }}</div>
                </validation-provider>
            </div>
            <div>
                <label for="email">{{ $t("Email") }}</label>
                <validation-provider name="email" rules="required|email" v-slot="{ errors }">
                    <input name="email" id="email" type="email" v-model="email" />
                    <div>{{ $t(errors[0]) }}</div>
                </validation-provider>
            </div>
            <div>
                <label for="password">{{ $t("Password") }}</label>
                <validation-provider name="password" rules="required|confirmed:passwordConfirmation" v-slot="{ errors }">
                    <input name="password" id="password" type="password" v-model="password" />
                    <div>{{ $t(errors[0]) }}</div>
                </validation-provider>
            </div>
            <div>
                <label for="password-confirmation">{{ $t("Confirm password") }}</label>
                <validation-provider name="password-confirmation" v-slot="{ errors }" vid="passwordConfirmation">
                    <input name="password-confirmation" id="password-confirmation" type="password" v-model="passwordConfirmation" />
                    <div>{{ $t(errors[0]) }}</div>
                </validation-provider>
            </div>
            <template v-slot:footer>
                <button :disabled="invalid"
                        @click="onClickRegister">
                    {{ $t("Register") }}
                </button>
            </template>
        </panel>
    </validation-observer>
</template>

<script>
    import Panel from "../../shared/Panel";

    export default {
        components: {
            Panel
        },
        data() {
            return {
                email: "",
                name: "",
                password: "",
                passwordConfirmation: "",
            };
        },
        methods: {
            onClickRegister() {
                this.$axios.post("register", {
                    email: this.email,
                    name: this.name,
                    password: this.password,
                    password_confirmation: this.passwordConfirmation,
                })
                .then(response => {
                    this.$emit("success", this.email);
                })
                .catch(error => {
                    console.log("Error in RegisterForm")
                    console.dir(error);
                })
                .finally(() => {
                });
            }
        },
        name: "RegisterForm"
    }
</script>

<style scoped>

</style>

<i18n>
{
    "en": {
        "Confirm password": "Confirm password",
        "Name": "Name",
        "Register": "Register",
        "The email field is required": "The email field is required",
        "The name field is required": "The name field is required",
        "The password field confirmation does not match":
            "The password field confirmation does not match",
        "The password field is required": "The password field is required"
    }
}
</i18n>
