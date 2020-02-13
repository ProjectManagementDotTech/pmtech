<template>
    <div>
        <router-view></router-view>
        <add-project v-if="addProjectVisible" />
        <add-workspace v-if="addWorkspaceVisible" />
    </div>
</template>

<script>
    import AddProject from "../../../components/members/projects/AddProject";
    import AddWorkspace
        from "../../../components/members/workspaces/AddWorkspace";

    export default {
        beforeDestroy() {
            this.$eventBus.$off("add-project", this.onAddProject);
            this.$eventBus.$off("add-workspace", this.onAddWorkspace);

            this.$eventBus.$off("close-modal", this.closeModal);
        },
        components: {
            AddProject,
            AddWorkspace
        },
        created() {
            this.$eventBus.$on("add-project", this.onAddProject);
            this.$eventBus.$on("add-workspace", this.onAddWorkspace);

            this.$eventBus.$on("close-modal", this.closeModal);
        },
        data() {
            return {
                addProjectVisible: false,
                addWorkspaceVisible: false,
            };
        },
        methods: {
            closeModal() {
                this.addProjectVisible = false;
                this.addWorkspaceVisible = false;
            },
            onAddProject() {
                this.addProjectVisible = true;
            },
            onAddWorkspace() {
                this.addWorkspaceVisible = true;
            }
        },
        name: "WorkspaceHome",
        // watch: {
        //     '$route': {
        //         deep: true,
        //         handler(newVal) {
        //             console.dir(newVal);
        //         }
        //     }
        // }
    }
</script>

<style scoped>

</style>
