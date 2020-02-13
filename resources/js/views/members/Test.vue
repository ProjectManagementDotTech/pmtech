<template>
    <div class="bg-indigo-300">
        <h1>Test</h1>
        <div><date-time-picker v-model="started_at" /></div>
        <div>{{ started_at }}</div>
    </div>
</template>

<script>
    import DateTimePicker
        from "../../components/members/general/DateTimePicker";

    export default {
        components: {
            DateTimePicker
        },
        created() {
            this.started_at = this.$moment().utc().local();
        },
        data() {
            return {
                started_at: undefined
            }
        },
        name: "Test"
    }
</script>

<style scoped>

</style>
