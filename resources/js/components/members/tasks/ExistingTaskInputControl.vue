<template>
    <div>
        <div v-show="false">{{ displayValue }}</div>
        <combo-control :entries="taskList"
                       :no-selection-text="project == null ? 'Please select a project first' : 'Please select'"
                       :value="value.task" @blur="onBlur"
                       @input="onInputNewTask" />
    </div>
</template>

<script>
    import ComboControl from "../general/ComboControl";
    import Vue from "vue";

    export default {
        components: {
            ComboControl
        },
        computed: {
            displayValue() {
                return JSON.stringify(this.value);
            }
        },
        data() {
            return {
                taskList: []
            }
        },
        methods: {
            onBlur() {
                this.$emit("input", this.value);
                this.$nextTick(() => {
                    this.$emit("blur");
                })
            },
            onInputNewTask(newTask) {
                Vue.set(this.value, 'task', newTask);
                Vue.set(this.value, 'task_id', newTask.id);
                this.$emit('input', this.value);
            },
            updateTaskList() {
                if(this.project) {
                    this.$axios.get("/api/v1/projects/" + this.project.id + "/tasks")
                        .then(response => {
                            if(response.data == undefined) {
                                this.taskList = [];
                            } else {
                                this.taskList = response.data;
                            }
                            this.value.task = this.taskList.find(
                                t => t.id == this.value.task_id
                            );
                        })
                        .finally(() => {
                            /* Empty */
                        });
                } else {
                    this.taskList = [];
                    this.value.task = null;
                }
            }
        },
        mounted() {
            this.updateTaskList();
        },
        name: "ExistingTaskInputControl",
        props: {
            project: {
                required: true,
            },
            value: {
                required: true
            }
        },
        watch: {
            project: {
                deep: true,
                handler(newVal, oldVal) {
                    if(newVal !== undefined && newVal !== null) {
                        this.updateTaskList();
                    } else {
                        this.taskList = [];
                    }
                }
            },
        }
    }
</script>

<style scoped>

</style>
