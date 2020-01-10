<template>
    <div class="items-center flex flex-wrap timesheet-entry-editor w-full">
        <div class="w-6/12 md:w-3/12">
            <filtering-dropdown-control :value="editorTimesheetEntry.project"
                                        :entries="$store.getters['projects/all']"
                                        @blur="onBlur"
                                        @input="onInputNewProject" />
        </div>
        <div class="w-6/12 md:w-3/12">
            <existing-task-input-control @input="(newValue) => { editorTimesheetEntry = newValue; }"
                                         :project="editorTimesheetEntry.project"
                                         :value="editorTimesheetEntry"
                                         @blur="onBlur" />
        </div>
        <div class="w-6/12 md:w-3/12 xl:w-4/12">
            <input class="block border border-gray-200 focus:outline-none focus:border-indigo-400 p-1 rounded w-full"
                   type="text" v-model="editorTimesheetEntry.description"
                   :id="'description_' + currentTimesheetEntryEditorId"
                   :name="'description_' + currentTimesheetEntryEditorId"
                   @blur="onBlur">
        </div>
        <div class="w-6/12 md:w-3/12 xl:w-2/12">
            <keep-alive>
                <component v-bind:is="visibleComponent" class="float-right p-1"
                           :is-new="timesheetEntry === null"
                           :timesheet-entry="editorTimesheetEntry"
                           @save="onSave" @start="onStart" @stop="onStop" />
            </keep-alive>
        </div>
    </div>
</template>

<script>
    import ExistingTaskInputControl from "../tasks/ExistingTaskInputControl";
    import FilteringDropdownControl from "../general/FilteringDropdownControl";
    import TimesheetEntryManualSaveButton from
            "./TimesheetEntryManualSaveButton";
    import TimesheetEntryStartButton from "./TimesheetEntryStartButton";
    import Vue from "vue";

    export default {
        beforeDestroy() {
            this.$eventBus.$off("reload-running-timer", this.loadRunningTimer)
        },
        components: {
            ExistingTaskInputControl,
            FilteringDropdownControl,
            TimesheetEntryManualSaveButton,
            TimesheetEntryStartButton
        },
        computed: {
            currentTimesheetEntryEditorId() {
                return document
                    .getElementsByClassName("timesheet-entry-editor")
                    .length;
            },
            displayEditorTimesheetEntry() {
                return JSON.stringify(this.editorTimesheetEntry);
            }
        },
        created() {
            if(this.timesheetEntry !== null) {
                this.editorTimesheetEntry = JSON.parse(
                    JSON.stringify(this.timesheetEntry)
                );
                if(this.editorTimesheetEntry.project_id) {
                    this.editorTimesheetEntry.project =
                        this.$store.getters['projects/byId'](
                            this.editorTimesheetEntry.project_id);
                }
                if(this.editorTimesheetEntry.task_id) {
                    this.$axios.get("/tasks/" +
                        this.editorTimesheetEntry.task_id)
                        .then(response => {
                            this.editorTimesheetEntry.task = response.data;
                        })
                        .catch(error => {
                            console.dir(error);
                            alert("TimesheetEntryEditor::created - We " +
                                "need to implement a generic error " +
                                "handler");
                        });
                }
            } else {
                this.editorTimesheetEntry = JSON.parse(
                    JSON.stringify(this.emptyTimesheetEntry)
                );
            }

            this.$eventBus.$on("reload-running-timer", this.loadRunningTimer)
        },
        data() {
            return {
                emptyTimesheetEntry: {
                    description: "",
                    ended_at: null,
                    id: null,
                    project: null,
                    task: null,
                    started_at: null,
                    workspace: null
                },
                editorTimesheetEntry: undefined,
                goldenCopy: undefined
            };
        },
        methods: {
            loadRunningTimer() {
                if(this.timesheetEntry == null) {
                    this.$axios.get("/timesheet_entries/running")
                        .then(response => {
                            if (response.data.length > 0) {
                                this.goldenCopy = JSON.parse(
                                    JSON.stringify(response.data[0])
                                );
                                this.editorTimesheetEntry = JSON.parse(
                                    JSON.stringify(response.data[0])
                                );
                                if (this.editorTimesheetEntry.project_id) {
                                    this.editorTimesheetEntry.project =
                                        this.$store.getters['projects/byId'](
                                            this.editorTimesheetEntry.project_id
                                        );
                                }
                            }
                        })
                        .catch(error => {
                            console.dir(error);
                            alert("TimesheetEntryEditor::loadRunningTimer - " +
                                "We need to implement a generic error handler");
                        });
                }
            },
            normalizeTimesheetEntry(anEntry) {
                let result;
                try {
                    result = JSON.parse(JSON.stringify(anEntry));
                } catch(error) {
                    debugger;
                }

                /*
                 * Copy the project.id into project_id if they are different
                 */
                if(result.project) {
                    if(result.project_id !== result.project.id) {
                        result.project_id = result.project.id;
                    }
                }
                delete result.project;

                if(result.task) {
                    if(result.task_id !== result.task.id) {
                        result.task_id = result.task.id;
                    }
                }
                delete result.task;

                return result;
            },
            onBlur() {
                if(
                    this.goldenCopy !== undefined &&
                    this.editorTimesheetEntry !== undefined
                ) {
                    let data = this.normalizeTimesheetEntry(
                        this.editorTimesheetEntry);
                    let gCData = this.normalizeTimesheetEntry(this.goldenCopy);
                    if(JSON.stringify(data) !== JSON.stringify(gCData)) {
                        if (data.id) {
                            this.$axios.put("/timesheet_entries/" + data.id, data)
                                .then(response => {
                                    this.goldenCopy = JSON.parse(JSON.stringify(
                                        this.editorTimesheetEntry));
                                })
                                .catch(error => {
                                    console.dir(error);
                                    alert("TimesheetEntryEditor::onBlur - " +
                                        "We need to implement a generic " +
                                        "error handler");
                                });
                        }
                    }
                }
            },
            onInputNewProject(newProject) {
                Vue.set(this.editorTimesheetEntry, 'project', newProject);
                Vue.set(this.editorTimesheetEntry, 'project_id', newProject.id);
            },
            onSave() {
                alert("Saving manual entry not yet implemented...");
            },
            onStart(payload) {
                let data = {};

                if(payload !== undefined) {
                    payload.duration = undefined;
                    payload.ended_at = undefined;
                    payload.id = undefined;
                    payload.project = undefined;
                    payload.started_at = undefined;
                    payload.task = undefined;
                    data = JSON.parse(JSON.stringify(payload));
                } else {
                    /*
                     * POST timesheet entry data to API and then:
                     * 1. If successful, load timesheet entry data from API
                     *    a. If successful, store information in
                     *       editorTimesheetEntry
                     *    b. If unsuccessful, I don't know
                     * 2. If unsuccessful, I don't know
                     */
                    data = this.normalizeEditorTimesheetEntry();
                }
                this.$axios.post("/timesheet_entries", data)
                    .then(response => {
                        if(payload !== undefined) {
                            this.$eventBus.$emit("reload-running-timer");
                        } else {
                            let timesheetEntryId = response.headers
                                .location
                                .substr(
                                    response.headers
                                        .location
                                        .lastIndexOf("/") + 1
                                );
                            this.$axios.get("/timesheet_entries/" +
                                timesheetEntryId)
                                .then(response => {
                                    this.editorTimesheetEntry = JSON.parse(
                                        JSON.stringify(response.data)
                                    );
                                })
                                .catch(error => {
                                    console.dir(error);
                                    alert("TimesheetEntryEditor::onStart - " +
                                        "We need to implement a generic " +
                                        "error handler");
                                });
                        }
                    })
                    .catch(error => {
                        console.dir(error);
                        alert("TimesheetEntryEditor::onStart - We need " +
                            "to implement a generic error handler");
                    });
            },
            onStop() {
                let data = this.normalizeEditorTimesheetEntry();
                this.$axios.put("/timesheet_entries/" + data.id, data)
                    .then(response => {
                        if(this.timesheetEntry == null) {
                            this.editorTimesheetEntry = JSON.parse(
                                JSON.stringify(this.emptyTimesheetEntry)
                            );
                        }
                        this.$emit("update-timesheet");
                    })
                    .catch(error => {
                        console.dir(error);
                        alert("TimesheetEntryEditor::onStop - We need to " +
                            "implement a generic error handler");
                    });
            },
        },
        mounted() {
            this.loadRunningTimer();
            if(this.timesheetEntry !== null) {
                this.goldenCopy = JSON.parse(JSON.stringify(
                    this.timesheetEntry));
            }
        },
        name: "TimesheetEntryEditor",
        props: {
            timesheetEntry: {
                default: null,
                required: false,
                type: Object
            },
            visibleComponent: {
                default: "timesheet-entrry-start-button",
                required: false,
                type: String
            }
        }
    }
</script>

<style scoped>

</style>
