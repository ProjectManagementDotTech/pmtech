<template>
    <div>
        <timesheet-report-filter-bar :filter="filter" @reset="onReset"
                                     @run-report="onRunReport" />
        <div class="flex w-full">
            <div class="w-1/2">
                <!--
                        Flick types: bar | donut | line | pie
                -->
            </div>
            <div class="w-1/2 pr-5 text-right">
                <button class="btn btn-primary" @click="onClickExport">
                    Export
                </button>
            </div>
        </div>
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
    import { mapGetters } from "vuex";
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
            ...mapGetters(["currentUser"]),
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
            onClickExport() {
                this.$axios.get("timesheet_entries/export" +
                    this.queryParamsFromFilter)
                    .then(response => {
                        let filename = this.$moment().format("YYYYMMDDHHmmss") +
                            "_TimesheetExport_" +
                            this.currentUser.name.replace(/ /gi, '') + ".xls";
                        let bin = atob(response.data);
                        let ab = this.s2ab(bin);
                        if(
                            window.navigator &&
                            window.navigator.msSaveOrOpenBlob) {
                            let blob = new Blob([ab], {
                                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                            });
                            window.navigator.msSaveOrOpenBlob(blob, filename);
                        } else {
                            let blob = new Blob([ab], {
                                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                            });
                            let link = document.createElement("a");
                            link.href = window.URL.createObjectURL(blob);
                            link.download = filename;
                            link.click();
                        }
                    });
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
                    });
            },
            s2ab(s) {
                var buf = new ArrayBuffer(s.length);
                var view = new Uint8Array(buf);
                for(var i = 0; i != s.length; ++i) {
                    view[i] = s.charCodeAt(i) & 0xFF;
                }
                return buf;
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
