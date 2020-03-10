<template>
    <modal-dialog-box ok-button-text="Add workspace"
                      :ok-button-disabled="addWorkspaceButtonDisabled"
                      :show-cancel-button="true" @cancel="onCancel"
                      @ok="onAddWorkspace">
        <template slot="header">Add new workspace</template>
        <validation-observer class="needs-validation" novalidate
                             ref="validationObserver" tag="form"
                             v-slot="{ invalid }">
            <div class="px-2">
                <pmtech-input label="Workspace name" name="name"
                              rules="required" v-model="name" />
            </div>
        </validation-observer>
    </modal-dialog-box>
</template>

<script>
    import ModalDialogBox from "../../shared/ModalDialogBox";
    import PmtechInput from "../../shared/input/PmtechInput";

    export default {
        components: {
            ModalDialogBox,
            PmtechInput
        },
        data() {
            return {
                name: ""
            };
        },
        methods: {
            addWorkspaceButtonDisabled() {
                let result = this.name == "";
                if(!result && this.$refs.validationObserver !== undefined) {
                    return this.$refs.validationObserver.flags.invalid;
                } else {
                    return result;
                }
            },
            onAddWorkspace() {
                this.$axios.post("/api/v1/workspaces", {
                    name: this.name
                })
                    .then(() => {
                        this.$store.dispatch("workspaces/fetchAll");
                        this.$store.commit("flashMessage/push", {
                            text: "The project '" + this.name + "' was " +
                                "created successfully.",
                            timeout: 3000,
                            title: "Project created!",
                            type: "success"
                        });
                        this.$eventBus.$emit("close-modal");
                    })
                    .catch(error => {

                    })
                    .finally(() => {

                    });
            },
            onCancel() {
                this.$eventBus.$emit("close-modal");
            }
        },
        name: "AddWorkspace"
    }
</script>

<style scoped>

</style>
