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
        name: "Login",
    }
</script>

<style scoped>

</style>
