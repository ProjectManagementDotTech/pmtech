<template>
    <div>
        <router-view></router-view>
        <add-client v-if="addClientVisible" />
        <add-project v-if="addProjectVisible" />
        <add-workspace v-if="addWorkspaceVisible" />
    </div>
</template>

<script>
    import AddClient from "../../../components/members/clients/AddClient";
    import AddProject from "../../../components/members/projects/AddProject";
    import AddWorkspace
        from "../../../components/members/workspaces/AddWorkspace";

    export default {
        beforeDestroy() {
            this.$eventBus.$off("add-client", this.onAddClient);
            this.$eventBus.$off("add-project", this.onAddProject);
            this.$eventBus.$off("add-workspace", this.onAddWorkspace);

            this.$eventBus.$off("close-modal", this.closeModal);
        },
        components: {
            AddClient,
            AddProject,
            AddWorkspace
        },
        created() {
            this.$eventBus.$on("add-client", this.onAddClient);
            this.$eventBus.$on("add-project", this.onAddProject);
            this.$eventBus.$on("add-workspace", this.onAddWorkspace);

            this.$eventBus.$on("close-modal", this.closeModal);
        },
        data() {
            return {
                addClientVisible: false,
                addProjectVisible: false,
                addWorkspaceVisible: false,
            };
        },
        methods: {
            closeModal() {
                this.addClientVisible = false;
                this.addProjectVisible = false;
                this.addWorkspaceVisible = false;
            },
            onAddClient() {
                this.addClientVisible = true;
            },
            onAddProject() {
                this.addProjectVisible = true;
            },
            onAddWorkspace() {
                this.addWorkspaceVisible = true;
            }
        },
        name: "WorkspaceHome",
        watch: {
            $route(to, from) {
                if(from.params.workspaceId != to.params.workspaceId) {
                    this.$store.dispatch("workspaceChanged",
                        to.params.workspaceId)
                }
            }
        }
    }
</script>

<style scoped>

</style>
