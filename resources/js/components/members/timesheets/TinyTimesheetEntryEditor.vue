<template>
    <div class="absolute bg-gray-600 border border-gray-900 bottom-0 right-0 p-4 rounded w-1/5 text-gray-100 z-40"
         v-if="showTinyTimesheetEntryEditor">
        <div class="flex w-full">
            <div class="font-semibold w-1/3">Project:</div>
            <div class="w-2/3">{{ editorTimesheetEntry.project.name }}</div>
        </div>
        <div class="flex w-full">
            <div class="font-semibold w-1/3">Task:</div>
            <div class="truncate w-2/3">
                <span v-if="editorTimesheetEntry.task == undefined">...</span>
                <span v-else>{{ editorTimesheetEntry.task.name }}</span>
            </div>
        </div>
        <div class="flex w-full">
            <div class="font-semibold w-1/3">Description:</div>
            <div class="truncate w-2/3">{{ editorTimesheetEntry.description }}</div>
        </div>
        <div class="flex w-full">
            <div class="font-semibold w-1/3">Started at:</div>
            <div class="w-2/3">{{ $moment.utc(editorTimesheetEntry.started_at).local().format("DD MMM YYYY HH:mm:ss") }}</div>
        </div>
        <div class="flex w-full">
            <div class="font-semibold w-1/3">Duration:</div>
            <div class="w-2/3">{{ elapsedTime }}</div>
        </div>
        <div class="flex w-full">
            <div class="text-center w-full">
                <button class="bg-red-500 focus:outline-none px-3 py-2 rounded-full" @click.stop="onClickStop">
                    <i class="fas fa-stop text-white" />
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import TimesheetEntryEditorMixin from "./mixins/TimesheetEntryEditorMixin";
    import Vue from "vue";

    export default {
        computed: {
            elapsedTime() {
                if(this.elapsedTimeInSeconds < 60) {
                    return "00:00:" + this.elapsedTimeInSeconds
                        .toString()
                        .padStart(2, "0");
                } else if(this.elapsedTimeInSeconds < 3600) {
                    let seconds = this.elapsedTimeInSeconds;
                    let minutes = Math.floor(seconds / 60);
                    seconds -= (minutes * 60);
                    return "00:" + minutes.toString().padStart(2, "0") + ":" +
                        seconds.toString().padStart(2, "0");
                } else {
                    let seconds = this.elapsedTimeInSeconds;
                    let hours = Math.floor(seconds / 3600);
                    seconds -= (hours * 3600);
                    let minutes = Math.floor(seconds / 60);
                    seconds -= (minutes * 60);
                    return hours.toString().padStart(2, "0") + ":" +
                        minutes.toString().padStart(2, "0") + ":" +
                        seconds.toString().padStart(2, "0");
                }
            },
            showTinyTimesheetEntryEditor() {
                return this.editorTimesheetEntry !== undefined;
            }
        },
        data() {
            return {
                elapsedTimeInSeconds: 0,
                intervalHandle: undefined
            };
        },
        methods: {
            calculateElapsedTimeInSeconds() {
                if(this.editorTimesheetEntry) {
                    this.elapsedTimeInSeconds =
                        this.$moment.utc().diff(
                            this.$moment.utc(this.editorTimesheetEntry.started_at,
                                "YYYY-MM-DD HH:mm:ss"
                            ), "seconds");
                    if(this.intervalHandle == undefined) {
                        this.intervalHandle = window.setInterval(() => {
                            this.elapsedTimeInSeconds++;
                        }, 1000);
                    }
                } else {
                    window.setTimeout(() => {
                        this.calculateElapsedTimeInSeconds();
                    }, 1000);
                }
            },
            onClickStop() {
                if(this.intervalHandle !== undefined) {
                    window.clearInterval(this.intervalHandle);
                }
                Vue.set(this.editorTimesheetEntry, "ended_at",
                    this.$moment.utc().format("YYYY-MM-DD HH:mm:ss"));
                let data = this.normalizeTimesheetEntry(
                    this.editorTimesheetEntry);
                this.$axios.put("/api/v1/timesheet_entries/" + data.id, data)
                    .then(response => {
                        this.editorTimesheetEntry = undefined;
                    })
                    .catch(error => {
                        console.dir(error);
                        debugger;
                    });

            }
        },
        mixins: [ TimesheetEntryEditorMixin ],
        mounted() {
            this.loadRunningTimer();
            this.calculateElapsedTimeInSeconds();
        },
        name: "TinyTimesheetEntryEditor"
    }
</script>

<style scoped>

</style>
