<template>
    <div>
        <article class="border border-red-300 overflow-hidden rounded">
            <main class="w-full flex flex-wrap items-center p-2">
                <div class="font-bold text-center text-red-500 w-full sm:w-1/4 lg:w-1/6">
                    Delete
                </div>
                <div class="w-full sm:w-1/2 lg:w-2/3">
                    When you delete a workspace, it and all attached projects become
                    unavailable forever. This action cannot be reversed, because the
                    data is deleted from our systems.
                </div>
                <div class="text-right w-full sm:w-1/4 lg:w-1/6">
                    <button class="bg-red-500 hover:bg-red-700 border-2 border-red-700 hover:border-red-900 font-weight-bold p-2 rounded text-gray-200"
                            @click="onClickDelete">
                        Delete
                    </button>
                </div>
            </main>
        </article>
        <confirmation-dialog buttons="Yes|No" title="Delete workspace"
                             v-if="confirmationShown"
                             :message="deleteMessage"
                             @cancel="onCancel" @ok="onOk" />
    </div>
</template>

<script>
    import ConfirmationDialog from "../general/ConfirmationDialog";
    export default {
        components: {
            ConfirmationDialog
        },
        computed: {
            deleteMessage() {
                let ownedWorkspaces = this.$store
                    .getters["workspaces/byOwnerUserId"](
                    this.$store.getters["currentUser"].id
                );
                if(ownedWorkspaces.length == 1) {
                    return "You are about to delete the workspace '" +
                        this.$store.getters["workspaces/byId"](
                            this.$route.params.workspaceId).name + "'. This " +
                        "is the last unarchived workspace that you own. Are " +
                        "you sure you want to delete this workspace? You can " +
                        "always create a new workspace.";
                } else {
                    return "Are you sure you want to delete the workspace '" +
                        this.$store.getters['workspaces/byId'](
                            this.$route.params.workspaceId).name + "'?";
                }
            }
        },
        data() {
            return {
                confirmationShown: false
            };
        },
        methods: {
            onCancel() {
                this.confirmationShown = false;
            },
            onClickDelete() {
                this.confirmationShown = true;
            },
            onOk() {
                this.$axios.delete("/api/v1/workspaces/" +
                    this.$route.params.workspaceId)
                    .then(response => {
                        this.$store.dispatch("workspaces/fetchAll")
                            .then(() => {
                                let newLocation = response.headers.location;
                                let toRoute = newLocation.substr(
                                    window.location.protocol.length +
                                    window.location.hostname.length + 9);
                                this.$router.push(toRoute);
                            });
                    })
                    .catch(error => {
                        console.dir(error);
                        debugger;
                        if(error.response.status == 422) {
                            /*
                             * Archive cannot be deleted?!!??!!
                             */
                        }
                    })
                    .finally(() => {
                        this.confirmationShown = false;
                    });
            }
        },
        name: "DeleteWorkspace"
    }
</script>

<style scoped>

</style>
