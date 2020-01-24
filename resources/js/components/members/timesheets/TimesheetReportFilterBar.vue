<template>
    <div>
        <div class="flex flex-wrap w-full items-center">
            <div class="w-6/12 md:w-3/12">
                <filtering-dropdown-control :value="filter.selectedProject"
                                            :entries="$store.getters['projects/all']"
                                            @blur="onBlur"
                                            @input="onInputProject" />
            </div>
            <div class="w-6/12 md:w-3/12">
                <existing-task-input-control :project="filter.selectedProject"
                                             :value="filter" @blur="onBlur"
                                             @input="onInputTask" />
            </div>
            <div class="w-6/12 md:w-3/12">
                <date-range-picker :end-date="filter.endDate"
                                   :start-date="filter.startDate"
                                   @input:end-date="onInputEndDate"
                                   @input:start-date="onInputStartDate" />
            </div>
            <div class="w-6/12 md:w-3/12">
                <button type="button" @click="onClickResetFilter">
                    Reset filter
                </button>
                <button type="button" @click="onClickGo">
                    Go
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import ExistingTaskInputControl from "../tasks/ExistingTaskInputControl";
    import FilteringDropdownControl from "../general/FilteringDropdownControl";
    import Vue from "vue";
    import DateRangePicker from "../general/DateRangePicker";

    export default {
        components: {
            DateRangePicker,
            ExistingTaskInputControl,
            FilteringDropdownControl
        },
        methods: {
            onBlur() {
            },
            onClickGo() {
                this.$emit("run-report");
            },
            onClickResetFilter() {
                this.$emit("reset");
            },
            onInputEndDate(endDate) {
                Vue.set(this.filter, "endDate", endDate);
            },
            onInputProject(project) {
                Vue.set(this.filter, "selectedProject", project);
            },
            onInputStartDate(startDate) {
                Vue.set(this.filter, "startDate", startDate);
            },
            onInputTask(newFilter) {
                Vue.set(this.filter, "selectedTask", newFilter.task);
            }
        },
        name: "TimesheetReportFilterBar",
        props: {
            filter: {
                required: true,
                type: Object
            }
        }
    }
</script>

<style scoped>

</style>
