<template>
    <div>
        <h2 class="pl-2 mb-1 mt-3 text-gray-900 font-semibold">Create a new timesheet entry:</h2>
        <timesheet-entry-editor class="px-2" @update-timesheet="fetchHistoricTimesheetEntries" />
        <h2 class="pl-2 mb-1 mt-1 pt-3 text-gray-900 border-t border-gray-400 font-semibold">Historical timesheet entries:</h2>
        <template v-if="timesheetEntriesByDay && timesheetEntriesByDay.length > 0">
            <timesheet-index-by-day v-for="(timesheetEntriesDay, index) in timesheetEntriesByDay"
                                    class="px-2" :key="index" :timesheet-entries="timesheetEntriesDay" />
        </template>
    </div>
</template>

<script>
    import TimesheetEntryEditor
        from "../../../components/members/timesheets/TimesheetEntryEditor";
    import TimesheetIndexByDay
        from "../../../components/members/timesheets/TimesheetIndexByDay";

    export default {
        components: {
            TimesheetIndexByDay,
            TimesheetEntryEditor
        },
        data() {
            return {
                timesheetEntriesByDay: []
            }
        },
        methods: {
            fetchHistoricTimesheetEntries() {
                this.$axios.get("/timesheet_entries")
                    .then(response => {
                        this.timesheetEntriesByDay = response.data;
                    })
                    .catch(error => {
                        console.dir(error);
                        alert("TimesheetEditor::" +
                            "fetchHistoricTimesheetEntries - We need a " +
                            "central generic error handling mechanism.");
                    })
            }
        },
        mounted() {
            this.fetchHistoricTimesheetEntries();
        },
        name: "TimesheetEditor"
    }
</script>

<style scoped>

</style>
