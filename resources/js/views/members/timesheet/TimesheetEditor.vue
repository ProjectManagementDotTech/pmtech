<template>
    <div>
        <div class="flex justify-between">
            <h2 class="pl-2 mt-3 text-gray-900">
                Create a new timesheet entry
            </h2>
            <div class="pr-2 mt-3 text-sm">
                <button class="text-sm focus:outline-none hover:font-semibold"
                        @click="visibleComponent = components[visibleComponent].buttonText">
                    {{ components[visibleComponent].buttonText }}
                </button>
            </div>
        </div>
        <timesheet-entry-editor class="px-2"
                                :visible-component="components[visibleComponent].component"
                                @update-timesheet="fetchHistoricTimesheetEntries" />
        <h2 class="pl-2 mb-1 mt-1 pt-3 text-gray-900 border-t border-gray-400">
            Historical timesheet entries
        </h2>
        <template v-if="timesheetEntriesByDay && timesheetEntriesByDay.length > 0">
            <timesheet-index-by-day v-for="(timesheetEntriesDay, index) in timesheetEntriesByDay"
                                    class="px-2" :key="componentKeys[index]"
                                    :timesheet-entries="timesheetEntriesDay"
                                    @update-timesheet="fetchHistoricTimesheetEntries" />
        </template>
    </div>
</template>

<script>
    import TimesheetEntryEditor
        from "../../../components/members/timesheets/TimesheetEntryEditor";
    import TimesheetIndexByDay
        from "../../../components/members/timesheets/TimesheetIndexByDay";
    import Vue from "vue";

    export default {
        components: {
            TimesheetIndexByDay,
            TimesheetEntryEditor
        },
        computed: {
            maxComponentKey() {
                let result = -1;
                for(let i = 0; i < this.componentKeys.length; i++) {
                    if(this.componentKeys[i] > result) {
                        result = this.componentKeys[i];
                    }
                }

                return result;
            }
        },
        data() {
            return {
                components: {
                    Manual: {
                        buttonText: "Timer",
                        component: "timesheet-entry-manual-save-button"
                    },
                    Timer: {
                        buttonText: "Manual",
                        component: 'timesheet-entry-start-button'
                    }
                },
                componentKeys: [],
                timesheetEntriesByDay: [],
                visibleComponent: "Timer"
            }
        },
        methods: {
            fetchHistoricTimesheetEntries() {
                this.$axios.get("/api/v1/workspaces/" +
                    this.$route.params.workspaceId + "/timesheet_entries")
                    .then(response => {
                        this.updateComponentKeys(response.data);
                        this.timesheetEntriesByDay = response.data;
                    });
            },
            updateComponentKeys(anArray) {
                for(var i = 0; i < anArray.length; i++) {
                    var max = this.maxComponentKey;
                    Vue.set(this.componentKeys, i, max + 1);
                }
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
