<template>
    <div class="w-full flex">
        <div class="hidden xl:inline-block xl:mx-auto" style="width: 1280px;">
            <component v-bind:is="components[type]" :filter="filter" :timesheet-entries="timesheetEntries" />
        </div>
    </div>
</template>

<script>
    import TimesheetReportLineGraph from "./TimesheetReportLineGraph";

    export default {
        components: {
            TimesheetReportLineGraph
        },
        data() {
            return {
                components: {
                    bar: "",
                    donut: "",
                    line: "TimesheetReportLineGraph",
                    pie: ""
                }
            }
        },
        name: "TimesheetReportGraph",
        props: {
            filter: {
                required: true,
                type: Object
            },
            timesheetEntries: {
                required: true,
                type: Array
            },
            type: {
                default: "line", /* bar, donut, line, pie */
                required: false,
                type: String
            }
        }
    }
</script>

<style scoped>

</style>
