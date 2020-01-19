<template>
    <div>
        <div v-if="!failed">
            <login-form @fail="onLoginFail" @success="onLoginSuccess">
            </login-form>
        </div>
        <div v-else>
            <activation-link :email="email"></activation-link>
        </div>
    </div>
</template>

<script>
    import ActivationLink from "../../components/public/authn/ActivationLink";
    import LoginForm from "../../components/public/authn/LoginForm";

    export default {
        components: {
            ActivationLink,
            LoginForm
        },
        data() {
            return {
/*
                user: {},
*/
                email: "",
                failed: false,
            };
        },
        methods: {
            onLoginFail(payload) {
                this.failed = true;
                this.email = payload;
            },
            onLoginSuccess() {
                this.$store.commit("loggedIn");
                this.$store.dispatch("authenticated")
                    .then(() => {
                        let user = this.$store.getters["currentUser"];
                        if(user.settings.last_visited_view != null) {
                            this.$router.push(user.settings.last_visited_view);
                        } else {
                            let workspaces =
                                this.$store.getters['workspaces/all'];
                            if(workspaces.length == 1) {
                                this.$router.push("/workspaces/" +
                                    workspaces[0].id);
                            } else {
                                let defaultWorkspace = workspaces.find(
                                    w => w.name == "Default"
                                );
                                if(defaultWorkspace) {
                                    this.$router.push("/workspaces/" +
                                        defaultWorkspace.id);
                                } else {
                                    this.$router.push("/workspaces/" +
                                        workspaces[0].id);
                                }
                            }
                        }
                    });
            }
        },
/*
        mounted() {
            console.log('Component mounted.');
            this.$axios.get("/airlock/csrf-cookie")
                .then(response => {
                    console.log("  We got a Laravel CSRF Cookie via Laravel " +
                        "Airlock...");
                    this.$axios.post("/api/v1/login", {
                        email: "user0001@test.com",
                        password: "Welcome123"
                    })
                        .then(response => {
                            console.log("  We are logged in as well now...");
                            console.log("  Token: '" + response.data + "'");
                            this.$axios.defaults.headers.common["Authorization"] = "Bearer " + response.data;
                            this.$axios.get("/api/v1/user")
                                .then(response => {
                                    this.user = response.data
                                });
                        })
                        .catch(error => {
                            console.log("  There was an error logging in:");
                            console.dir(error);
                        });
                })
                .catch(error => {
                    console.log("  There was an error getting a Laravel CSRF " +
                        "Cookie");
                    console.dir(error);
                });
        },
*/
        name: "Login",
    }
</script>

<style scoped>

</style>
