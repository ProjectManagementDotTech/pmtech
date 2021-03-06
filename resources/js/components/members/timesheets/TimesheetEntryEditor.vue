<template>
    <div class="items-center flex flex-wrap timesheet-entry-editor w-full">
        <div class="w-6/12 md:w-3/12">
            <combo-control :entries="$store.getters['projects/all']"
                           :value="editorTimesheetEntry.project"
                           @blur="onBlur" @input="onInputNewProject" />
        </div>
        <div class="w-6/12 md:w-3/12">
            <existing-task-input-control @input="(newValue) => { editorTimesheetEntry = newValue; }"
                                         :project="editorTimesheetEntry.project"
                                         :value="editorTimesheetEntry"
                                         @blur="onBlur" />
        </div>
        <div class="w-6/12 md:w-3/12">
            <input class="block border border-gray-200 focus:outline-none focus:border-indigo-400 p-1 rounded w-full"
                   type="text" v-model="editorTimesheetEntry.description"
                   :id="'description_' + currentTimesheetEntryEditorId"
                   :name="'description_' + currentTimesheetEntryEditorId"
                   @blur="onBlur">
        </div>
        <div class="w-6/12 md:w-3/12">
            <keep-alive>
                <component v-bind:is="visibleComponent" class="pr-1"
                           :is-new="timesheetEntry === null"
                           :timesheet-entry="editorTimesheetEntry"
                           @blur="onBlur" @input-ended-at="onInputEndedAt"
                           @input-started-at="onInputStartedAt" @save="onSave"
                           @start="onStart" @stop="onStop" />
            </keep-alive>
        </div>
    </div>
</template>

<script>
    import ComboControl from "../general/ComboControl";
    import ExistingTaskInputControl from "../tasks/ExistingTaskInputControl";
    import TimesheetEntryEditorMixin from "./mixins/TimesheetEntryEditorMixin";
    import TimesheetEntryManualSaveButton from
            "./TimesheetEntryManualSaveButton";
    import TimesheetEntryStartButton from "./TimesheetEntryStartButton";
    import Vue from "vue";

    export default {
        beforeDestroy() {
            this.$eventBus.$off("reload-running-timer", this.loadRunningTimer)
        },
        components: {
            ComboControl,
            ExistingTaskInputControl,
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
                    this.$axios.get("/api/v1/tasks/" +
                        this.editorTimesheetEntry.task_id)
                        .then(response => {
                            this.editorTimesheetEntry.task = response.data;
                        });
                }
                if(this.editorTimesheetEntry.ended_at !== null) {
                    this.editorTimesheetEntry.ended_at = this.$moment.utc(
                        this.editorTimesheetEntry.ended_at,
                        "YYYY-MM-DD HH:mm:ss"
                    ).local();
                }
                if(this.editorTimesheetEntry.started_at !== null) {
                    this.editorTimesheetEntry.started_at = this.$moment.utc(
                        this.editorTimesheetEntry.started_at,
                        "YYYY-MM-DD HH:mm:ss"
                    ).local();
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
            };
        },
        methods: {
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
                            this.$axios.put("/api/v1/timesheet_entries/" + data.id, data)
                                .then(response => {
                                    this.goldenCopy = JSON.parse(JSON.stringify(
                                        this.editorTimesheetEntry));
                                })
                                .catch(error => {
                                    console.dir(error);
                                    debugger;
                                });
                        }
                    }
                }
            },
            onInputEndedAt(newEndedAt) {
                Vue.set(this.editorTimesheetEntry, "ended_at", newEndedAt);
            },
            onInputNewProject(newProject) {
                Vue.set(this.editorTimesheetEntry, "project", newProject);
                Vue.set(this.editorTimesheetEntry, "project_id", newProject.id);
            },
            onInputStartedAt(newStartedAt) {
                Vue.set(this.editorTimesheetEntry, "started_at", newStartedAt);
            },
            onSave() {
                let data = this.normalizeTimesheetEntry(
                    this.editorTimesheetEntry);
                let promise = null;
                if(data.id !== null && data.id !== undefined) {
                    promise = this.$axios.put("/api/v1/timesheet_entries/" + data.id,
                        data);
                } else {
                    promise = this.$axios.post("/api/v1/timesheet_entries", data);
                }
                promise
                    .then(response => {
                        if(this.timesheetEntry !== null) {
                            this.editorTimesheetEntry = JSON.parse(
                                JSON.stringify(this.timesheetEntry)
                            );
                        } else {
                            this.editorTimesheetEntry = JSON.parse(
                                JSON.stringify(this.emptyTimesheetEntry)
                            );
                        }
                        this.$emit("update-timesheet");
                    })
                    .catch(error => {
                        console.dir(error);
                        alert("TimesheetEntryEditor::onSave - We need to " +
                            "implement a generic error handler");
                    });
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
                    data = this.normalizeTimesheetEntry(
                        this.editorTimesheetEntry);
                }
                this.$axios.post("/api/v1/timesheet_entries", data)
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
                            this.$axios.get("/api/v1/timesheet_entries/" +
                                timesheetEntryId)
                                .then(response => {
                                    let oldProject = JSON.parse(JSON.stringify(
                                        this.editorTimesheetEntry.project));
                                    let oldTask = JSON.parse(JSON.stringify(
                                        this.editorTimesheetEntry.task));
                                    this.editorTimesheetEntry = JSON.parse(
                                        JSON.stringify(response.data)
                                    );
                                    this.editorTimesheetEntry.project =
                                        oldProject;
                                    this.editorTimesheetEntry.task = oldTask;
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
                let data = this.normalizeTimesheetEntry(
                    this.editorTimesheetEntry);
                this.$axios.put("/api/v1/timesheet_entries/" + data.id, data)
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
                        debugger;
                    });
            },
        },
        mixins: [ TimesheetEntryEditorMixin ],
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
                default: "timesheet-entry-start-button",
                required: false,
                type: String
            }
        }
    }
</script>

<style scoped>

</style>
