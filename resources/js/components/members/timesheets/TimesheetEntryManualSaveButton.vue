<template>
    <div class="flex items-center">
        <div class="">
            Start date/time<br />
            {{ timesheetEntry.started_at }}
        </div>
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
    export default {
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
