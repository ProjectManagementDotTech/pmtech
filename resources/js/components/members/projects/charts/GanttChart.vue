<template>
    <div class="flex w-full">
        <div class="w-full">
            <project-toolbar :selected-task-count="selectedRows.length"
                             @properties="onShowTaskProperties" />
            <div class="w-full md:w-1/2">
                <grid-table :data="$store.getters['tasks/all']"
                            :empty-object="newTask" :fields="gridFields"
                            @input="onInput" @selected-rows="onSelectedRows" />
            </div>
            <div class="hidden md:inline-block w-1/2"></div>
        </div>
        <task-properties v-if="showTaskProperties" :tasks="selectedRows"
                         @close="showTaskProperties = false"
                         @reset-and-close="onResetAndClose" />
    </div>
</template>

<script>
    import GridTable from "../../general/GridTable";
    import ProjectToolbar from "../ProjectToolbar";
    import TaskProperties from "../../tasks/TaskProperties";

    export default {
        components: {
            GridTable,
            ProjectToolbar,
            TaskProperties
        },
        data() {
            return {
                gridFields: [
                    {
                        attribute: "name",
                        title: "Name",
                        type: "text"
                    }
                ],
                newTask: {
                    id: undefined,
                    name: "",
                    wbs: "",
                },
                selectedRows: [],
                showTaskProperties: false
            };
        },
        methods: {
            onInput(newTaskObject) {
                if (newTaskObject.name != "") {
                    if (newTaskObject.id !== undefined && newTaskObject.id !== "") {
                        console.dir(newTaskObject);
                        this.$axios.put("/api/v1/tasks/" + newTaskObject.id,
                            newTaskObject);
                        this.$store.commit("tasks/update", newTaskObject);
                    } else {
                        this.$axios.post("/api/v1/projects/" +
                            this.$route.params.projectId + "/tasks",
                            newTaskObject)
                            .then(response => {
                                let loc = response.headers.location;
                                let newTaskId = loc.substring(
                                    loc.lastIndexOf("/") + 1);
                                this.$axios.get("/api/v1/tasks/" + newTaskId)
                                    .then(response => {
                                        this.$store.commit("tasks/add",
                                            response.data);
                                    })
                            });
                    }
                }
            },
            onResetAndClose(payload) {
                for(let i = 0; i < payload.length; i++) {
                    this.$store.commit("tasks/update", payload[i]);
                }

                this.showTaskProperties = false;
            },
            onSelectedRows(payload) {
                this.selectedRows = payload;
            },
            onShowTaskProperties() {
                this.showTaskProperties = true;
            }
        },
        name: "GanttChart"
    }
</script>

<style scoped>

</style>
