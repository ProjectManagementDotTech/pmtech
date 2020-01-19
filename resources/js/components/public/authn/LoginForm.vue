<template>
    <validation-observer class="needs-validation" novalidate
                         ref="validationObserver" tag="form"
                         method="POST" action="javascript:void(0);"
                         v-slot="{ invalid }">
        <panel>
            <template v-slot:header>
                <h1>{{ $t("Login") }}</h1>
            </template>
            <div>
                <label for="email">{{ $t("Email") }}</label>
                <validation-provider name="email" rules="required|email"
                                     v-slot="{ errors }">
                    <input id="email" name="email" type="email"
                           v-model="email" />
                    <div>{{ $t(errors[0]) }}</div>
                </validation-provider>
            </div>
            <div>
                <label for="password">{{ $t("Password") }}</label>
                <validation-provider name="password"
                                     rules="required"
                                     v-slot="{ errors }">
                    <input id="password" name="password" type="password"
                           v-model="password" />
                    <div>{{ $t(errors[0]) }}</div>
                </validation-provider>
            </div>
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

    export default {
        components: {
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
