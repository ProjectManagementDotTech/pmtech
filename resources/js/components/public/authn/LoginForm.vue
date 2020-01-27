<template>
    <validation-observer class="needs-validation" novalidate
                         ref="validationObserver" tag="form"
                         method="POST" action="javascript:void(0);"
                         v-slot="{ invalid }">
        <panel>
            <template v-slot:header>
                <h1>{{ $t("Login") }}</h1>
            </template>
            <pmtech-input label="Email address" name="email"
                          rules="required|email" v-model="email" />
            <pmtech-input label="Password" name="password" rules="required"
                          type="password" v-model="password" />
            <template v-slot:footer>
                <button :disabled="invalid"
                        @click="onClickLogin">
                    {{ $t("Login") }}
                </button>
            </template>
        </panel>
    </validation-observer>
</template>

<script>
    import Panel from "../../shared/Panel";
    import PmtechInput from "../../shared/input/PmtechInput";

    export default {
        components: {
            PmtechInput,
            Panel
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
                            .then(response => {
                                window.localStorage.setItem("access_token",
                                    response.data.access_token);
                                window.localStorage.setItem("token_type",
                                    response.data.token_type);
                                this.$axios.defaults.headers.common["Authorization"] =
                                    response.data.token_type + " " +
                                    response.data.access_token;
                                this.$axios.defaults.baseURL = "https://" +
                                    window.location.hostname + "/api/v1/";
                                this.$emit("success");
                            })
                            .catch(error => {
                                if(error.response) {
                                    if(error.response.status == 422) {
                                        window.alert("Invalid credentials!");
                                    } else if(error.response.status == 403) {
                                        this.$emit("fail", this.email);
                                    } else {
                                        window.alert("Need to implement error mixin!");
                                    }
                                }
                            })
                            .finally(() => {
                            });
                    })
                    .catch(error => {
                        console.log("LoginForm::onClickLogin");
                        console.dir(error);
                        window.alert("Need to implement global error handling!");
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
