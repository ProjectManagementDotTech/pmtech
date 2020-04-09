<template>
    <div>
        <validation-observer class="needs-validation"
                             ref="validationObserver" tag="div"
                             v-slot="{ invalid }">
            <div class="px-2">
                <pmtech-input label="Project name" name="name" rules="required"
                              v-model="name" />
                <pmtech-input label="Project abbreviation" name="abbreviation"
                              rules="required|max:5" v-model="abbreviation" />
                <div class="flex items-center mb-4 w-full">
                    <label class="mr-6 w-1/4" for="color">Project color</label>
                    <compact id="color" v-model="color" />
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
        <div class="mb-2 text-right w-full">
            <button class="btn btn-primary" :disabled="updateButtonDisabled"
                    @click="onClickUpdateProject">
                Update project
            </button>
        </div>
    </div>
</template>

<script>
    import { Compact } from "vue-color";
    import DateTimePicker from "../general/DateTimePicker";
    import PmtechInput from "../../shared/input/PmtechInput";
    import ClientSelectionControl from "../clients/ClientSelectionControl";

    export default {
        components: {
            ClientSelectionControl,
            Compact,
            DateTimePicker,
            PmtechInput
        },
        computed: {
            updateButtonDisabled() {
                let currentProject = this.$store.getters["projects/byId"](
                    this.$route.params.projectId);
                if(!currentProject || !this.client) {
                    return true;
                }

                let result =
                    this.client.id == currentProject.client_id &&
                    this.color.hex.substr(1) == currentProject.color &&
                    (this.name == "" ||
                        this.name == currentProject.name) &&
                    this.abbreviation == currentProject.abbreviation &&
                    this.startDate.format("YYYY-MM-DD") ==
                        this.$moment(currentProject.start_date).format("YYYY-MM-DD");

                if(!result && this.$refs.validationObserver !== undefined) {
                    return this.$refs.validationObserver.flags.invalid;
                } else {
                    return result;
                }
            }
        },
        created() {
            this.updateDataFromRouteInformation();
        },
        data() {
            return {
                abbreviation: "",
                client: {
                    id: ""
                },
                color: "",
                name: "",
                startDate: this.$moment()
            };
        },
        methods: {
            onClickUpdateProject() {
                this.$axios.put("/api/v1/projects/" + this.$route.params.projectId, {
                    abbreviation: this.abbreviation,
                    client_id: this.client.id,
                    color: this.color.hex.substr(1),
                    name: this.name,
                    start_date: this.startDate.format("DD/MM/YYYY")
                })
                    .then(() => {
                        /*
                         * TODO Show flash message with success...
                         */
                        this.$store.dispatch("projects/fetchAll",
                            this.$route.params.workspaceId)
                        .then(() => {
                            this.updateDataFromRouteInformation();
                        });
                    })
                    .catch(error => {
                        console.dir(error.response.data.errors);
                        debugger;
                        this.$refs.validationObserver.setErrors(
                            error.response.data.errors
                        );
                    });
            },
            onUpdateStartDate(newValue) {
                this.startDate = newValue;
            },
            updateDataFromRouteInformation() {
                let currentProject = this.$store.getters["projects/byId"](
                    this.$route.params.projectId
                );
                if(currentProject) {
                    this.abbreviation = currentProject.abbreviation;
                    this.client = this.$store.getters["clients/byId"](
                        currentProject.client_id);
                    this.color = {
                        hex: "#" + currentProject.color
                    };
                    this.name = currentProject.name;
                    if(currentProject.start_date != null) {
                        this.startDate = this.$moment(currentProject.start_date);
                    } else {
                        this.startDate = this.$moment();
                    }
                }
            }
        },
        name: "EditProject",
        watch: {
            $route(to, from) {
                if(to.params.projectId != from.params.projectId) {
                    this.updateDataFromRouteInformation();
                }
            }
        }
    }
</script>

<style scoped>

</style>
