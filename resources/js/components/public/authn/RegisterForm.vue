<template>
    <validation-observer class="needs-validation" novalidate
                         ref="validationObserver" tag="div"
                         v-slot="{ invalid }">
        <article class="w-2/3 md:w-1/3 mx-auto my-4 border rounded-lg shadow-lg overflow-hidden">
            <header class="border-b border-indigo-500 bg-indigo-400 px-4 py-2 text-white">
                <h2>Register</h2>
            </header>
            <main class="px-2 py-1">
                <pmtech-input name="name" rules="required" v-model="name"
                              :label="$t('Name')" />
                <pmtech-input name="email" rules="required|email" type="email"
                              v-model="email" :label="$t('Email')" />
                <pmtech-input name="password"
                              rules="required|confirmed:passwordConfirmation"
                              type="password" v-model="password"
                              :label="$t('Password')" />
                <pmtech-input name="password-confirmation" rules="required"
                              type="password" vid="passwordConfirmation"
                              v-model="passwordConfirmation"
                              :label="$t('Confirm password')"/>
            </main>
            <footer class="border-t border-gray-200 px-4 py-2">
                <button class="btn btn-primary" :disabled="invalid"
                        @click="onClickRegister">
                    {{ $t("Register") }}
                </button>
            </footer>
        </article>
    </validation-observer>
</template>

<script>
    import Panel from "../../shared/Panel";
    import PmtechInput from "../../shared/input/PmtechInput";

    export default {
        components: {
            Panel,
            PmtechInput,
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
                this.$axios.get("airlock/csrf-token")
                    .then(response => {
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
