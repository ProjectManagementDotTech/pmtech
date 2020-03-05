<template>
    <modal-dialog-box ok-button-text="Add client"
                      :ok-button-disabled="addClientButtonDisabled"
                      :show-cancel-button="true" @cancel="onCancel"
                      @ok="onAddClient">
        <template slot="header">Add new client</template>
        <validation-observer class="needs-validation" novalidate
                             ref="validationObserver" tag="form"
                             v-slot="{ invalid }">
            <div class="px-2">
                <pmtech-input label="Client name" name="name" rules="required"
                              v-model="name" />
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
            addClientButtonDisabled() {
                let result = this.name == "";
                if(!result && this.$refs.validationObserver !== undefined) {
                    return this.$refs.validationObserver.invalid;
                } else {
                    return result;
                }
            },
            onAddClient() {
                if(this.$route.params !== undefined) {
                    let data = {
                        name: this.name
                    };
                    this.$axios.post("/api/v1/workspaces/" +
                        this.$route.params.workspaceId + "/clients", data)
                        .then(() => {
                            this.$eventBus.$emit("update-client-index");
                            this.$store.dispatch("clients/fetchAll",
                                this.$route.params.workspaceId);
                            this.$store.commit("flashMessage/push", {
                                text: "The client '" + this.name + "' was " +
                                    "created successfully.",
                                timeout: 3000,
                                title: "client created!",
                                type: "success"
                            });
                            this.$eventBus.$emit("close-modal");
                        })
                        .catch(error => {
                        })
                        .finally(() => {
                        });
                }
            },
            onCancel() {
                this.$eventBus.$emit("close-modal");
            }
        },
        name: "AddClient"
    }
</script>

<style scoped>

</style>
