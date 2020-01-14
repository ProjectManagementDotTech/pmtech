<template>
    <div class="flex items-center">
        <div>
            <date-time-picker v-bind:value="timesheetEntry.started_at" @input="onInputStartedAt" />
        </div>
        <div>
            <date-time-picker v-bind:value="timesheetEntry.ended_at" @input="onInputEndedAt" />
        </div>
        <div class="">
            <button :disabled="manualSaveButtonDisabled" @click="$emit('save')">
                <i class="fas fa-save text-green-500 text-2xl"></i>
            </button>
        </div>
    </div>
</template>

<script>
    import DateTimePicker from "../general/DateTimePicker";

    export default {
        components: {
            DateTimePicker
        },
        computed: {
            manualSaveButtonDisabled() {
                let endedAt = null;
                let startedAt = null;
                try {
                    endedAt = this.timesheetEntry
                        .ended_at
                        .format("YYYY-MM-DD HH:mm:ss");
                    startedAt = this.timesheetEntry
                        .started_at
                        .format("YYYY-MM-DD HH:mm:ss");
                } catch(error) {
                    /* EMPTY */
                }
                return this.timesheetEntry.description.length == 0 ||
                    this.timesheetEntry.ended_at == null ||
                    this.timesheetEntry.started_at == null ||
                    endedAt == startedAt
            }
        },
        methods: {
            onInputEndedAt(newValue) {
                this.$emit("input-ended-at", newValue);
            },
            onInputStartedAt(newValue) {
                this.$emit("input-started-at", newValue);
            }
        },
        name: "TimesheetEntryManualSaveButton",
        props: {
            timesheetEntry: {
                required: true
            }
        }
    }
</script>

<style scoped>

</style>
