<template>
    <div>
        <timesheet-report-filter-bar :filter="filter" @reset="onReset"
                                     @run-report="onRunReport" />
        <template v-if="timesheetEntries && timesheetEntries.length > 0">
            <timesheet-report-graph :filter="filter"
                                    :timesheet-entries="timesheetEntries" />
            <timesheet-index-by-day v-for="(timesheetEntriesDay, index) in timesheetEntries"
                                    class="px-2" :key="index"
                                    :timesheet-entries="timesheetEntriesDay" />
        </template>
        <template v-else>
            Alert: Cannot find timesheet entries matching the selected criteria!
        </template>
    </div>
</template>

<script>
    import TimesheetIndexByDay
        from "../../../components/members/timesheets/TimesheetIndexByDay";
    import TimesheetReportFilterBar
        from "../../../components/members/timesheets/TimesheetReportFilterBar";
    import TimesheetReportGraph
        from "../../../components/members/timesheets/TimesheetReportGraph";

    export default {
        components: {
            TimesheetIndexByDay,
            TimesheetReportFilterBar,
            TimesheetReportGraph
        },
        computed: {
            queryParamsFromFilter() {
                let result = "?";
                if(this.filter.endDate !== undefined) {
                    result += "end_date=" + this.filter.endDate.format("YYYY-MM-DD") + "&"
                }
                if(this.filter.selectedProject !== undefined) {
                    result += "project_id=" + this.filter.selectedProject.id + "&";
                }
                if(this.filter.selectedTask !== undefined) {
                    result += "task_id=" + this.filter.selectedTask.id + "&";
                }
                if(this.filter.startDate !== undefined) {
                    result += "start_date=" + this.filter.startDate.format("YYYY-MM-DD") + "&";
                }

                return result.substr(0, result.length - 1);
            }
        },
        data() {
            return {
                filter: {
                    endDate: undefined,
                    selectedProject: undefined,
                    selectedTask: undefined,
                    startDate: undefined
                },
                timesheetEntries: {}
            }
        },
        methods: {
            initializeFilter() {
                this.filter.startDate = this.$moment().startOf("week");
                this.filter.endDate = this.$moment().endOf("week");
            },
            onReset() {
                this.filter = {
                    endDate: undefined,
                    selectedProject: undefined,
                    selectedTask: undefined,
                    startDate: undefined
                };
                this.initializeFilter();
            },
            onRunReport() {
                this.$axios.get("timesheet_entries" + this.queryParamsFromFilter)
                    .then(response => {
                        this.timesheetEntries = response.data;
                    })
                    .catch(error => {
                        console.dir(error);
                        alert("TimesheetReport::onRunReport - We " +
                            "need to implement a generic error " +
                            "handler");
                    });
            }
        },
        mounted() {
            this.initializeFilter();
            this.$nextTick(() => {
                this.onRunReport();
            })
        },
        name: "TimesheetReport"
    }
</script>

<style scoped>

</style>
