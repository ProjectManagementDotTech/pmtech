<template>
    <div v-if="!cloak && workspaceMembers.length > 0" class="mb-4">
        <article class="border border-red-300 rounded">
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
                    <filtering-dropdown-control :value="newOwnerMember"
                                                :entries="workspaceMembers"
                                                @blur="onBlur"
                                                @input="onInputNewOwnerMember" />
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
    import FilteringDropdownControl from "../general/FilteringDropdownControl";
    import Vue from "vue";

    export default {
        components: {
            FilteringDropdownControl
        },
        created() {
            this.$axios.get("/api/v1/workspaces/" +
                this.$route.params.workspaceId + "/members")
                .then(response => {
                    let currentUser = this.$store.getters["currentUser"];
                    this.workspaceMembers = response.data.filter(
                        u => u.id != currentUser.id
                    );
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
                newOwnerMember: {},
                workspaceMembers: []
            };
        },
        methods: {
            onBlur() {
                console.log("blurring from the FilteringDropDown control...");
            },
            onClickTransfer() {
                this.$axios.post("/api/v1/workspaces/" +
                    this.$route.params.workspaceId + "/transfer/" +
                    this.newOwnerMember.id)
                    .then(response => {
                        this.$router.push("/workspaces/" +
                            this.$route.params.workspaceId);
                    })
                    .catch(error => {
                        debugger;
                    });
            },
            onInputNewOwnerMember(newOwner) {
                Vue.set(this.newOwnerMember, "id", newOwner.id);
                Vue.set(this.newOwnerMember, "name", newOwner.name);
            }
        },
        name: "TransferWorkspaceOwnership"
    }
</script>

<style scoped>

</style>
