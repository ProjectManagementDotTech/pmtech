<template>
    <div v-if="!cloak && workspaceMembers.length > 0" class="mb-4">
        <article class="border border-red-300 overflow-hidden rounded">
            <main class="w-full flex flex-wrap items-center p-2">
                <div class="font-bold text-center text-red-500 w-full sm:w-1/4 lg:w-1/6">
                    Transfer ownership
                </div>
                <div class="w-full sm:w-1/4 lg:w-1/3">
                    You can transfer ownership of this workspace to any member
                    of the workspace. Please select the new owner and click
                    "Transfer".
                </div>
                <div class="w-full sm:1/4 lg:w-1/3">
                    Select member...
                    {{ $store.getters["workspaces/byId"]($route.params.workspaceId) }}
                </div>
                <div class="text-right w-full sm:w-1/4 lg:w-1/6">
                    <button class="bg-red-500 hover:bg-red-700 border-2 border-red-700 hover:border-red-900 font-weight-bold p-2 rounded text-gray-200"
                            @click="onClickTransfer">
                        Transfer
                    </button>
                </div>
            </main>
        </article>
    </div>
</template>

<script>
    export default {
        created() {
            this.$axios.get("/api/v1/workspaces/" +
                this.$route.params.workspaceId + "/members")
                .then(response => {
                    let currentUser = this.$store.getters["currentUser"];
                    console.log("currentUser");
                    console.dir(currentUser);
                    console.log("response.data");
                    console.dir(response.data);
                    this.workspaceMembers = response.data.filter(
                        u => u.id != currentUser.id
                    );
                    console.log("this.workspaceMembers");
                    console.dir(this.workspaceMembers);
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
                workspaceMembers: []
            };
        },
        methods: {
            onClickTransfer() {
                alert("Transferring ownership...");
            }
        },
        name: "TransferWorkspaceOwnership"
    }
</script>

<style scoped>

</style>
