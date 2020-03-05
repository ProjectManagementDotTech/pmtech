<template>
    <div>
        <validation-observer class="needs-validation" novalidate
                             ref="validationObserver" tag="div"
                             v-slot="{ invalid }">
            <div class="px-2">
                <pmtech-input label="Project name" name="name" rules="required"
                              v-model="name" />
                <div class="flex items-center mb-4 w-full">
                    <label class="mr-6 w-1/4" for="color">Project color</label>
                    <compact id="color" v-model="color" />
                </div>
                <div class="flex items-center w-full">
                    <label class="mr-6 w-1/4">Client</label>
                    <div class="w-3/4">
                        <client-selection-control class="w-3/4"
                                                  v-model="client" />
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
    import PmtechInput from "../../shared/input/PmtechInput";
    import ClientSelectionControl from "../clients/ClientSelectionControl";

    export default {
        components: {
            ClientSelectionControl,
            Compact,
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
                        this.name == currentProject.name);
                console.log("EditProject::updateButtonDisabled == " + result);

                if(!result && this.$refs.validationObserver !== undefined) {
                    return this.$refs.validationObserver.invalid;
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
                client: {
                    id: ""
                },
                color: "",
                name: ""
            };
        },
        methods: {
            onClickUpdateProject() {
                this.$axios.put("/api/v1/projects/" + this.$route.params.projectId, {
                    client_id: this.client.id,
                    color: this.color.hex.substr(1),
                    name: this.name
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
            updateDataFromRouteInformation() {
                let currentProject = this.$store.getters["projects/byId"](
                    this.$route.params.projectId
                );
                if(currentProject) {
                    this.client = this.$store.getters["clients/byId"](
                        currentProject.client_id);
                    this.color = {
                        hex: "#" + currentProject.color
                    };
                    this.name = currentProject.name;
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
