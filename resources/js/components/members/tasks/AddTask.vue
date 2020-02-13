<template>
    <validation-observer class="needs-validation" novalidate
                         ref="validationObserver" tag="div"
                         v-slot="{ invalid }">
        <pmtech-input label="Task" name="name" rules="required"
                      v-model="name" />
        <div>
            <button :disabled="invalid" @click.stop="onAddTask">Add task</button>
        </div>
    </validation-observer>
</template>

<script>
    import PmtechInput from "../../shared/input/PmtechInput";
    export default {
        components: {PmtechInput},
        data() {
            return {
                name: ""
            };
        },
        methods: {
            onAddTask() {
                this.$axios.post("/api/v1/projects/" + this.$route.params.projectId +
                    "/tasks", {
                        name: this.name
                    })
                    .then(response => {
                        this.name = "";
                        this.$refs.validationObserver.reset();
                        this.$emit("add-task:update-index");
                    })
                    .catch(error => {

                    })
                    .finally(() => {

                    });
            }
        },
        name: "AddTask"
    }
</script>

<style scoped>

</style>
