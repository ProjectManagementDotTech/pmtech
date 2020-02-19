<template>
    <div>
        <panel :has-footer="false">
            <template slot="header">Edit settings</template>
            <edit-workspace />
        </panel>
        <panel :has-footer="false" class="mt-4">
            <template slot="header">Invite people</template>
            <invite-workspace-member />
        </panel>
        <panel class="mt-4" type="danger" :has-footer="false">
            <template slot="header">Danger zone</template>
            <transfer-workspace-ownership />
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
    import InviteWorkspaceMember
        from "../../../components/members/workspaces/InviteWorkspaceMember";
    import Panel from "../../../components/shared/Panel";
    import TransferWorkspaceOwnership
        from "../../../components/members/workspaces/TransferWorkspaceOwnership";

    export default {
        components: {
            ArchiveWorkspace,
            DeleteWorkspace,
            EditWorkspace,
            InviteWorkspaceMember,
            Panel,
            TransferWorkspaceOwnership
        },
        name: "WorkspaceSettings",
    }
</script>

<style scoped>

</style>
