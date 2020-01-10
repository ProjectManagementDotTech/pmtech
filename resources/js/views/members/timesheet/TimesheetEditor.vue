<template>
    <div>
        <timesheet-entry-editor @update-timesheet="fetchHistoricTimesheetEntries" />
        <template v-if="timesheetEntriesByDay && timesheetEntriesByDay.length > 0">
            <timesheet-index-by-day v-for="(timesheetEntriesDay, index) in timesheetEntriesByDay"
                                    :key="index" :timesheet-entries="timesheetEntriesDay" />
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
