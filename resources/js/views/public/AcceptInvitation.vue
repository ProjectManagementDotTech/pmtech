<template>
    <validation-observer class="needs-validation" novalidate
                         ref="validationObserver" tag="form"
                         method="POST" action="javascript:void(0);"
                         v-slot="{ invalid }">
        <article class="w-2/3 md:w-1/3 mx-auto my-4 border rounded-lg shadow-lg overflow-hidden">
            <header class="border-b border-indigo-500 bg-indigo-400 px-4 py-2 text-white">
                <h2>Accept Invitation</h2>
            </header>
            <main class="px-2 py-1">
                <pmtech-input label="Name" name="name"
                              rules="required" v-model="name" />
                <pmtech-input label="Password" name="password"
                              rules="required|confirmed:confirmation"
                              type="password" v-model="password" />
                <pmtech-input label="Confirm Password"
                              name="password_confirmation" rules="required"
                              type="password" vid="confirmation"
                              v-model="passwordConfirmation" />
            </main>
            <footer class="border-t border-gray-200 px-4 py-2">
                <button class="btn btn-primary" :disabled="invalid"
                        @click="onClickAcceptInvitation">
                    {{ $t("Accept Invitation") }}
                </button>
            </footer>
        </article>
    </validation-observer>
</template>

<script>
    import PmtechInput from "../../components/shared/input/PmtechInput";

    export default {
        components: {
            PmtechInput
        },
        data() {
            return {
                name: "",
                password: "",
                passwordConfirmation: ""
            };
        },
        methods: {
            onClickAcceptInvitation() {
                this.$axios.post("/invitation/details/" +
                    this.$route.params.invitationNonce + "/" +
                    this.$route.params.cacheNonce, {
                    name: this.name,
                    password: this.password,
                    password_confirmation: this.passwordConfirmation
                })
                    .then(response => {
                        this.$router.push("/login");
                    })
                    .catch(error => {
                        console.dir(error);
                        debugger;
                    });
            }
        },
        name: "AcceptInvitation"
    }
</script>

<style scoped>

</style>
