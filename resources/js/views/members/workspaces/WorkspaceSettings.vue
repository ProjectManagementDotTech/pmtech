<template>
    <div>
        <panel :has-footer="false">
            <template slot="header">Edit settings</template>
            <edit-workspace />
        </panel>
        <panel class="mt-4" type="danger" :has-footer="false">
            <template slot="header">Danger zone</template>
            <archive-workspace />
            <delete-workspace class="mt-4" />
        </panel>
    </div>
</template>

<script>
    import ArchiveWorkspace
        from "../../../components/members/workspaces/ArchiveWorkspace";
    import DeleteWorkspace
        from "../../../components/members/workspaces/DeleteWorkspace";
    import EditWorkspace
        from "../../../components/members/workspaces/EditWorkspace";
    import Panel from "../../../components/shared/Panel";

    export default {
        components: {
            ArchiveWorkspace,
            DeleteWorkspace,
            EditWorkspace,
            Panel
        },
        name: "WorkspaceSettings",
    }
</script>

<style scoped>

</style>
