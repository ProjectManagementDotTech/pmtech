<template>
    <div>
        <panel :has-footer="false">
            <template slot="header">Edit settings</template>
            <edit-project />
        </panel>
    </div>
</template>

<script>
    import EditProject from "../../../components/members/projects/EditProject";
    import Panel from "../../../components/shared/Panel";

    export default {
        components: {
            EditProject,
            Panel
        },
        name: "ProjectSettings"
    }
</script>

<style scoped>

</style>
