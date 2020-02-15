<template>
    <panel type="danger" :has-footer="false">
        <template slot="header">Danger Zone</template>
        <archive-workspace />
        <delete-workspace class="mt-4" />
    </panel>
</template>

<script>
    import ArchiveWorkspace
        from "../../../components/members/workspaces/ArchiveWorkspace";
    import DeleteWorkspace
        from "../../../components/members/workspaces/DeleteWorkspace";
    import Panel from "../../../components/shared/Panel";

    export default {
        components: {
            ArchiveWorkspace,
            DeleteWorkspace,
            Panel
        },
        name: "WorkspaceSettings",
    }
</script>

<style scoped>

</style>
