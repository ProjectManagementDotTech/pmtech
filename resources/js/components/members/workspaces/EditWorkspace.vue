<template>
    <div>
        <validation-observer class="needs-validation" novalidate
                             ref="validationObserver" tag="form"
                             v-slot="{ invalid }">
            <div class="px-2">
                <pmtech-input label="Workspace name" name="name"
                              rules="required" v-model="workspaceName" />
            </div>
        </validation-observer>
        <div class="mb-2 text-right w-full">
            <button class="btn btn-primary" :disabled="updateButtonDisabled"
                    @click="onClickUpdateWorkspace">
                Update workspace
            </button>
        </div>
    </div>
</template>

<script>
    import PmtechInput from "../../shared/input/PmtechInput";

    export default {
        components: {
            PmtechInput
        },
        computed: {
            updateButtonDisabled() {
                let result = (
                    this.workspaceName == "" ||
                    this.workspaceName == this.$store
                        .getters["workspaces/byId"](
                            this.$route.params.workspaceId).name
                );
                if(!result && this.$refs.validationObserver !== undefined) {
                    return this.$refs.validationObserver.invalid;
                } else {
                    return result;
                }
            }
        },
        created() {
            this.workspaceName =
                this.$store.getters["workspaces/byId"](
                    this.$route.params.workspaceId
                ).name;
        },
        data() {
            return {
                workspaceName: 'n/a'
            };
        },
        methods: {
            onClickUpdateWorkspace() {
                this.$axios.put("/api/v1/workspaces/" + this.$route.params.workspaceId, {
                    name: this.workspaceName
                })
                    .then(() => {
                        this.$store.dispatch("workspaces/fetchAll")
                    })
                    .catch(error => {
                        console.dir(error.response.data.errors);
                        debugger;
                        this.$refs.validationObserver.setErrors(
                            error.response.data.errors
                        );
                    });
            }
        },
        name: "EditWorkspace"
    }
</script>

<style scoped>

</style>
