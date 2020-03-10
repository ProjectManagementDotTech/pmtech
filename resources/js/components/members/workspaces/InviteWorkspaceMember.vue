<template>
    <div class="flex flex-wrap w-full">
        <div class="w-full">
            <h2>Invite new member</h2>
            <validation-observer class="needs-validation" novalidate
                                 ref="validationObserver" tag="div"
                                 v-slot="{ invalid }">
                <div class="px-2">
                    <pmtech-input label="Email address" name="email"
                                  rules="required|email"
                                  v-model="emailAddress" />
                </div>
                <div class="mb-2 text-right w-full">
                    <button class="btn btn-primary"
                            :disabled="emailAddress == '' || invalid"
                            @click="onClickInvite">
                        Invite
                    </button>
                </div>
            </validation-observer>
        </div>
        <div class="border-gray-200 border-t flex flex-wrap mt-2 pt-2 w-full"
             v-if="!cloak && workspaceMembers.length > 0">
            <div class="w-full mb-2">
                <h2>Current members</h2>
            </div>
            <div class="border-2 border-gray-200 flex mb-2 last:mb-4 p-2 rounded-lg shadow-md w-full"
                 v-for="workspaceMember in workspaceMembers">
                <div class="w-11/12">
                    {{ workspaceMember.name }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import PmtechInput from "../../shared/input/PmtechInput";

    export default {
        components: {
            PmtechInput
        },
        computed: {
            inviteButtonDisabled() {
                let result = this.emailAddress == "";
                debugger;
                if(!result && this.$refs.validationObserver !== undefined) {
                    return this.$refs.validationObserver.flags.invalid;
                } else {
                    return result;
                }
            }
        },
        created() {
            this.$axios.get("/api/v1/workspaces/" +
                this.$route.params.workspaceId + "/members")
                .then(response => {
                    this.workspaceMembers = response.data;
                })
                .catch(() => {
                    this.workspaceMembers = [];
                })
                .finally(() => {
                    this.cloak = false;
                });
        },
        data() {
            return {
                cloak: true,
                emailAddress: "",
                workspaceMembers: []
            };
        },
        methods: {
            onClickInvite() {
                this.$axios.post("/api/v1/workspaces/" + this.$route.params.workspaceId + "/invite", {
                    email: this.emailAddress
                })
                    .then(response => {
                        this.workspaceMembers.push({
                            name: this.emailAddress + " (invitation pending " +
                                "acceptance)"
                        });
                        this.emailAddress = "";
                    })
                    .catch(error => {

                    });
            }
        },
        name: "InviteWorkspaceMember"
    }
</script>

<style scoped>

</style>
