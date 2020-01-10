<template>
    <validation-observer class="needs-validation" novalidate
                         ref="validationObserver" tag="div"
                         v-slot="{ invalid }">
        <div>
            <label for="name">Task</label>
        </div>
        <div>
            <validation-provider name="name" rules="required"
                                 v-slot="{ errors }">
                <input id="name" name="name" required type="text"
                       v-model="name" />
                <div>{{ errors[0] }}</div>
            </validation-provider>
        </div>
        <div>
            <button :disabled="invalid" @click.stop="onAddTask">Add task</button>
        </div>
    </validation-observer>
</template>

<script>
    export default {
        data() {
            return {
                name: ""
            };
        },
        methods: {
            onAddTask() {
                this.$axios.post("/projects/" + this.$route.params.projectId +
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
