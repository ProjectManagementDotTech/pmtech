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
                showTaskProperties: false,
                updateTaskTimeoutHandle: undefined
            };
        },
        methods: {
            onInput(newTaskObject) {
                if(newTaskObject.name != "") {
                    if(
                        newTaskObject.id !== undefined &&
                        newTaskObject.id !== ""
                    ) {
                        if(this.updateTaskTimeoutHandle !== undefined) {
                            window.clearTimeout(this.updateTaskTimeoutHandle);
                        }
                        /*
                         * Delay sending the update for 1 second, so that if the
                         * user is making multiple changes in quick succession,
                         * we only send the update to the API after the user
                         * stopped typing for a second. This makes sure that we
                         * do not run over the standard Laravel API Rate Limit.
                         * --glj
                         */
                        this.updateTaskTimeoutHandle = window.setTimeout(() => {
                            let eTag = this.$store.getters["tasks/eTag"](
                                newTaskObject.id);
                            this.$axios.put("/api/v1/tasks/" + newTaskObject.id,
                                newTaskObject, {
                                    headers: { "If-Match": eTag }
                            });
                        }, 1000);
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
