<template>
    <modal-dialog-box ok-button-text="Add project"
                      :ok-button-disabled="addProjectButtonDisabled"
                      :show-cancel-button="true" @cancel="onCancel"
                      @ok="onAddProject">
        <template slot="header">Add new project</template>
        <validation-observer class="needs-validation" novalidate
                             ref="validationObserver" tag="form"
                             v-slot="{ invalid }">
            <div>
                <div>
                    <label for="name">Project name</label>
                </div>
                <div>
                    <validation-provider name="name" rules="required"
                                         v-slot="{ errors }">
                        <input class="input" id="name" name="name" required
                               type="text" v-model="name" />
                        <div>{{ errors[0] }}</div>
                    </validation-provider>
                </div>
                <div>
                    <div>
                        <label for="color">Project color</label>
                    </div>
                    <div>
                        <compact v-model="colors" />
                    </div>
                </div>
            </div>
        </validation-observer>
    </modal-dialog-box>
</template>

<script>
    import { Compact } from "vue-color";
    import { Swatches } from "vue-color";
    import ModalDialogBox from "../../shared/ModalDialogBox";

    export default {
        components: {
            Compact,
            ModalDialogBox,
            Swatches
        },
        data() {
            return {
                colors: {
                    hex: "#194d33"
                },
                name: ""
            };
        },
        methods: {
            addProjectButtonDisabled() {
                let result = this.name == "";
                if(!result && this.$refs.validationObserver !== undefined) {
                    return this.$refs.validationObserver.invalid;
                } else {
                    return result;
                }
            },
            onAddProject() {
                if(this.$route.params !== undefined) {
                    let data = {
                        color: this.colors.hex.substr(1),
                        name: this.name
                    };
                    this.$axios.post("/workspaces/" +
                        this.$route.params.workspaceId + "/projects", data)
                        .then(() => {
                            this.$eventBus.$emit("update-project-index");
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
                }
            },
            onCancel() {
                this.$eventBus.$emit("close-modal");
            },
        },
        name: "AddProject"
    }
</script>

<style scoped>

</style>
