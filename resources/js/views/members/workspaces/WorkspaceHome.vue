<template>
    <div>
        <router-view></router-view>
        <add-project v-if="addProjectVisible"></add-project>
    </div>
</template>

<script>
    import AddProject from "../../../components/members/projects/AddProject";

    export default {
        beforeDestroy() {
            this.$eventBus.$off("add-project", this.onAddProject);

            this.$eventBus.$off("close-modal", this.closeModal);
        },
        components: {
            AddProject
        },
        created() {
            this.$eventBus.$on("add-project", this.onAddProject);

            this.$eventBus.$on("close-modal", this.closeModal);
        },
        data() {
            return {
                addProjectVisible: false
            };
        },
        methods: {
            closeModal() {
                this.addProjectVisible = false;
            },
            onAddProject() {
                this.addProjectVisible = true;
            }
        },
        name: "WorkspaceHome",
        watch: {
            '$route': {
                deep: true,
                handler(newVal) {
                    console.dir(newVal);
                }
            }
        }
    }
</script>

<style scoped>

</style>
