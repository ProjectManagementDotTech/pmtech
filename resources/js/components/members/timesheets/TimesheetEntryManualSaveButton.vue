<template>
    <div class="flex items-center">
        <date-time-picker v-bind:value="timesheetEntry.started_at" />
        <div class="">
            End date/time<br />
            {{ timesheetEntry.ended_at }}
        </div>
        <div class="">
            <button class="">
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
