<template>
    <div>
        <div v-if="$store.getters['projects/all'].length == 0" class="p-2">
            You currently have no projects in this workspace.
            <button class="btn btn-primary" @click="onAddProject">
                Add project
            </button>
        </div>
        <div v-else>
            <index-projects />
        </div>
    </div>
</template>

<script>
    import IndexProjects from
        "../../../components/members/projects/IndexProjects";

    export default {
        components: {
            IndexProjects
        },
        methods: {
            onAddProject() {
                this.$eventBus.$emit("add-project");
            }
        },
        name: "WorkspaceDashboard"
    }
</script>

<style scoped>

</style>
