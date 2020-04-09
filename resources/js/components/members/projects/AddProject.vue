<template>
    <modal-dialog-box ok-button-text="Add project"
                      :ok-button-disabled="addProjectButtonDisabled"
                      :show-cancel-button="true" @cancel="onCancel"
                      @ok="onAddProject">
        <template slot="header">Add new project</template>
        <validation-observer class="needs-validation" novalidate
                             ref="validationObserver" tag="div"
                             v-slot="{ invalid }">
            <div class="px-2">
                <pmtech-input label="Project name" name="name" rules="required"
                              v-model="name" />
                <pmtech-input label="Project abbreviation" name="abbreviation"
                              rules="required|max:5" v-model="abbreviation" />
                <div class="mb-4 w-full flex items-center">
                    <label class="w-1/4 mr-6" for="color">Project color</label>
                    <compact class="w-3/4" v-model="colors" />
                </div>
                <div class="flex items-center mb-4 w-full">
                    <label class="mr-6 w-1/4">Client</label>
                    <div class="w-3/4">
                        <client-selection-control class="w-3/4"
                                                  v-model="client" />
                    </div>
                </div>
                <div class="flex items-center w-full">
                    <label class="mr-6 w-1/4">Start date</label>
                    <div class="w-3/4">
                        <date-time-picker class="w-3/4"
                                          :user-config="{ format: 'DD MMM YYYY', pickTime: false }"
                                          :value="startDate" @input="onUpdateStartDate" />
                    </div>
                </div>
            </div>
        </validation-observer>
    </modal-dialog-box>
</template>

<script>
    import ClientSelectionControl from "../clients/ClientSelectionControl";
    import { Compact } from "vue-color";
    import DateTimePicker from "../general/DateTimePicker";
    import ModalDialogBox from "../../shared/ModalDialogBox";
    import PmtechInput from "../../shared/input/PmtechInput";

    export default {
        components: {
            ClientSelectionControl,
            Compact,
            DateTimePicker,
            ModalDialogBox,
            PmtechInput
        },
        data() {
            return {
                abbreviation: "",
                colors: {
                    hex: "#194d33"
                },
                client: {
                    id: "",
                    name: ""
                },
                name: "",
                startDate: this.$moment()
            };
        },
        methods: {
            addProjectButtonDisabled() {
                let result = this.abbreviation == "" || this.name == "";
                if(!result && this.$refs.validationObserver !== undefined) {
                    return this.$refs.validationObserver.flags.invalid;
                } else {
                    return result;
                }
            },
            onAddProject() {
                if(this.$route.params !== undefined) {
                    let data = {
                        client_id: this.client.id,
                        abbreviation: this.abbreviation,
                        color: this.colors.hex.substr(1),
                        name: this.name,
                        start_date: this.startDate.format("DD/MM/YYYY")
                    };
                    this.$axios.post("/api/v1/workspaces/" +
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
            onUpdateStartDate(newValue) {
                this.startDate = newValue;
            }
        },
        name: "AddProject"
    }
</script>

<style scoped>

</style>
