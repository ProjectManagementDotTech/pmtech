import Vue from "vue";

const TimesheetEntryEditorMixin = {
    data() {
        return {
            goldenCopy: undefined,
            editorTimesheetEntry: undefined
        };
    },
    methods: {
        loadRunningTimer() {
            if(this.timesheetEntry == null || this.timesheetEntry == undefined) {
                this.$axios.get("/api/v1/timesheet_entries/running")
                    .then(response => {
                        if (response.data.length > 0) {
                            this.goldenCopy = JSON.parse(
                                JSON.stringify(response.data[0])
                            );
                            this.editorTimesheetEntry = JSON.parse(
                                JSON.stringify(response.data[0])
                            );
                            if(this.editorTimesheetEntry.project_id) {
                                this.editorTimesheetEntry.project =
                                    this.$store.getters['projects/byId'](
                                        this.editorTimesheetEntry.project_id
                                    );
                            }
                            if(this.editorTimesheetEntry.task_id) {
                                this.$axios.get("/api/v1/tasks/" +
                                    this.editorTimesheetEntry.task_id)
                                    .then(response => {
                                        Vue.set(this.editorTimesheetEntry,
                                            "task", response.data);
                                    });
                            }
                        }
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
            if(result.id == null) {
                delete result.id;
            }

            if(anEntry.ended_at == null) {
                delete result.ended_at;
            } else {
                if(typeof anEntry.ended_at == "object") {
                    result.ended_at = anEntry.ended_at
                        .format("YYYY-MM-DD HH:mm:ss");
                }
            }
            if(anEntry.started_at == null) {
                delete result.started_at;
            } else {
                if(typeof anEntry.started_at == "object") {
                    result.started_at = anEntry.started_at
                        .format("YYYY-MM-DD HH:mm:ss");
                }
            }

            return result;
        },
    }
};

export default TimesheetEntryEditorMixin;
