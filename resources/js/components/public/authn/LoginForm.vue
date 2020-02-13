<template>
    <validation-observer class="needs-validation" novalidate
                         ref="validationObserver" tag="form"
                         method="POST" action="javascript:void(0);"
                         v-slot="{ invalid }">
        <article class="w-2/3 md:w-1/3 mx-auto my-4 border rounded-lg shadow-lg overflow-hidden">
            <header class="border-b border-indigo-500 bg-indigo-400 px-4 py-2 text-white">
                <h2>Login</h2>
            </header>
            <main class="px-2 py-1">
                <pmtech-input label="Email address" name="email"
                              rules="required|email" v-model="email" />
                <pmtech-input label="Password" name="password" rules="required"
                              type="password" v-model="password" />
            </main>
            <footer class="border-t border-gray-200 px-4 py-2">
                <button class="btn btn-primary" :disabled="invalid"
                        @click="onClickLogin">
                    {{ $t("Login") }}
                </button>
            </footer>
        </article>
    </validation-observer>
</template>

<script>
    import PmtechInput from "../../shared/input/PmtechInput";

    export default {
        components: {
            PmtechInput
        },
        data() {
            return {
                email: "",
                password: "",
            };
        },
        methods: {
            onClickLogin() {
                this.$axios.get("airlock/csrf-cookie")
                    .then(response => {
                        this.$axios.post("api/v1/login", {
                            email: this.email,
                            password: this.password,
                        })
                            .then(() => {
                                this.$emit("success");
                            })
                            .catch(error => {
                                if(error.response) {
                                    if(error.response.status == 422) {
                                        window.alert("Invalid credentials!");
                                    } else if(error.response.status == 403) {
                                        this.$emit("fail", this.email);
                                    } else {
                                        console.log("LoginForm::onClickLogin" +
                                            "::catch");
                                        console.dir(error);
                                    }
                                }
                            })
                            .finally(() => {
                            });
                    })
                    .catch(error => {
                        console.log("LoginForm::onClickLogin");
                        console.dir(error);
                    });
            }
        },
        name: "LoginForm"
    }
</script>

<style scoped>

</style>

<i18n>
{
    "en": {
        "Login": "Login"
    }
}
</i18n>
