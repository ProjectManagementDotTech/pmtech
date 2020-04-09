<template>
    <div>
        <div class="flex flex-wrap w-full items-center">
            <div class="w-6/12 md:w-2/12">
                <client-selection-control v-model="filter.client" />
            </div>
            <div class="w-6/12 md:w-2/12">
                <combo-control :entries="clientProjects"
                               :value="filter.selectedProject" @blur="onBlur"
                               @input="onInputProject" />
            </div>
            <div class="w-6/12 md:w-2/12">
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
            <div class="w-6/12 md:w-2/12">
                <button class="btn btn-secondary" type="button" @click="onClickResetFilter">
                    Reset filter
                </button>
                <button class="btn btn-primary" type="button" @click="onClickGo">
                    Go
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import ComboControl from "../general/ComboControl";
    import ExistingTaskInputControl from "../tasks/ExistingTaskInputControl";
    import Vue from "vue";
    import DateRangePicker from "../general/DateRangePicker";
    import ClientSelectionControl from "../clients/ClientSelectionControl";

    export default {
        components: {
            ClientSelectionControl,
            ComboControl,
            DateRangePicker,
            ExistingTaskInputControl,
        },
        computed: {
            clientProjects() {
                if(this.filter.client !== undefined) {
                    return this.$store.getters['projects/all'].filter(
                        p => p.client_id == this.filter.client.id
                    );
                } else {
                    return this.$store.getters['projects/all'];
                }
            }
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
