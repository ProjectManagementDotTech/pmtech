<template>
    <div>
        <router-view></router-view>
        <add-client v-if="addClientVisible" />
        <add-project v-if="addProjectVisible" />
        <add-workspace v-if="addWorkspaceVisible" />
        <new-version-loader v-if="isNewVersionAvailable" />
    </div>
</template>

<script>
    import { mapGetters } from "vuex";
    import AddClient from "../../../components/members/clients/AddClient";
    import AddProject from "../../../components/members/projects/AddProject";
    import AddWorkspace
        from "../../../components/members/workspaces/AddWorkspace";
    import NewVersionLoader
        from "../../../components/members/general/NewVersionLoader";

    export default {
        beforeDestroy() {
            this.$eventBus.$off("add-client", this.onAddClient);
            this.$eventBus.$off("add-project", this.onAddProject);
            this.$eventBus.$off("add-workspace", this.onAddWorkspace);

            this.$eventBus.$off("close-modal", this.closeModal);
        },
        components: {
            NewVersionLoader,
            AddClient,
            AddProject,
            AddWorkspace
        },
        computed: {
            ...mapGetters([ "isNewVersionAvailable" ])
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
