<template>
    <div>
        <div class="flex w-full" :class="{ 'mb-1': displayShortcutButtons }">
            <div class="w-1/2">
                <date-time-picker :value="startDate" @input="onUpdateStartDate" />
            </div>
            <div class="w-1/2">
                <date-time-picker :value="endDate" @input="onUpdateEndDate" />
            </div>
        </div>
        <template v-if="displayShortcutButtons">
            <div class="flex w-full mb-1">
                <div class="w-1/3 text-center">
                    <button class="bg-green-400 py-1 px-2 rounded focus:outline-none text-xs"
                            :disabled="thisWeekPicked" @click="onClickThisWeek">
                        This week
                    </button>
                </div>
                <div class="w-1/3 text-center">
                    <button class="bg-green-400 py-1 px-2 rounded focus:outline-none text-xs"
                            :disabled="thisMonthPicked" @click="onClickThisMonth">
                        This month
                    </button>
                </div>
                <div class="w-1/3 text-center">
                    <button class="bg-green-400 py-1 px-2 rounded focus:outline-none text-xs"
                            :disabled="thisYearPicked" @click="onClickThisYear">
                        This year
                    </button>
                </div>
            </div>
            <div class="flex w-full">
                <div class="w-1/3 text-center">
                    <button class="bg-green-400 py-1 px-2 rounded focus:outline-none text-xs"
                            :disabled="lastWeekPicked" @click="onClickLastWeek">
                        Last week
                    </button>
                </div>
                <div class="w-1/3 text-center">
                    <button class="bg-green-400 py-1 px-2 rounded focus:outline-none text-xs"
                            :disabled="lastMonthPicked" @click="onClickLastMonth">
                        Last month
                    </button>
                </div>
                <div class="w-1/3 text-center">
                    <button class="bg-green-400 py-1 px-2 rounded focus:outline-none text-xs"
                            :disabled="lastYearPicked" @click="onClickLastYear">
                        Last year
                    </button>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import DateTimePicker from "./DateTimePicker";

    export default {
        components: {
            DateTimePicker
        },
        computed: {
            lastMonthPicked() {
                if(this.startDate !== undefined && this.endDate !== undefined) {
                    let startOfMonth = this.$moment().subtract(1, "month").startOf("month").format("YYYY-MM-DD");
                    let thisStartDate = this.startDate.format("YYYY-MM-DD");
                    let endOfMonth = this.$moment().subtract(1, "month").endOf("month").format("YYYY-MM-DD");
                    let thisEndDate = this.endDate.format("YYYY-MM-DD");

                    if(
                        startOfMonth === thisStartDate &&
                        endOfMonth === thisEndDate
                    ) {
                        return true;
                    }
                }

                return false;
            },
            lastWeekPicked() {
                if(this.startDate !== undefined && this.endDate !== undefined) {
                    let startOfWeek = this.$moment().subtract(7, "days").startOf("week").format("YYYY-MM-DD");
                    let thisStartDate = this.startDate.format("YYYY-MM-DD");
                    let endOfWeek = this.$moment().subtract(7, "days").endOf("week").format("YYYY-MM-DD");
                    let thisEndDate = this.endDate.format("YYYY-MM-DD");

                    if(
                        startOfWeek === thisStartDate &&
                        endOfWeek === thisEndDate
                    ) {
                        return true;
                    }
                }

                return false;
            },
            lastYearPicked() {
                if(this.startDate !== undefined && this.endDate !== undefined) {
                    let startOfYear = this.$moment().subtract(1, "year").startOf("year").format("YYYY-MM-DD");
                    let thisStartDate = this.startDate.format("YYYY-MM-DD");
                    let endOfYear = this.$moment().subtract(1, "year").endOf("year").format("YYYY-MM-DD");
                    let thisEndDate = this.endDate.format("YYYY-MM-DD");

                    if(
                        startOfYear === thisStartDate &&
                        endOfYear === thisEndDate
                    ) {
                        return true;
                    }
                }

                return false;
            },
            thisMonthPicked() {
                if(this.startDate !== undefined && this.endDate !== undefined) {
                    let startOfMonth = this.$moment().startOf("month").format("YYYY-MM-DD");
                    let thisStartDate = this.startDate.format("YYYY-MM-DD");
                    let endOfMonth = this.$moment().endOf("month").format("YYYY-MM-DD");
                    let thisEndDate = this.endDate.format("YYYY-MM-DD");

                    if(
                        startOfMonth === thisStartDate &&
                        endOfMonth === thisEndDate
                    ) {
                        return true;
                    }
                }

                return false;
            },
            thisWeekPicked() {
                if(this.startDate !== undefined && this.endDate !== undefined) {
                    let startOfWeek = this.$moment().startOf("week").format("YYYY-MM-DD");
                    let thisStartDate = this.startDate.format("YYYY-MM-DD");
                    let endOfWeek = this.$moment().endOf("week").format("YYYY-MM-DD");
                    let thisEndDate = this.endDate.format("YYYY-MM-DD");

                    if(
                        startOfWeek === thisStartDate &&
                        endOfWeek === thisEndDate
                    ) {
                        return true;
                    }
                }

                return false;
            },
            thisYearPicked() {
                if(this.startDate !== undefined && this.endDate !== undefined) {
                    let startOfYear = this.$moment().startOf("year").format("YYYY-MM-DD");
                    let thisStartDate = this.startDate.format("YYYY-MM-DD");
                    let endOfYear = this.$moment().endOf("year").format("YYYY-MM-DD");
                    let thisEndDate = this.endDate.format("YYYY-MM-DD");

                    if(
                        startOfYear === thisStartDate &&
                        endOfYear === thisEndDate
                    ) {
                        return true;
                    }
                }

                return false;
            }
        },
        methods: {
            onClickLastMonth() {
                this.onUpdateEndDate(this.$moment().subtract(1, "month").endOf("month"));
                this.onUpdateStartDate(this.$moment().subtract(1, "month").startOf("month"));
            },
            onClickLastWeek() {
                this.onUpdateEndDate(this.$moment().subtract(7, "days").endOf("week"));
                this.onUpdateStartDate(this.$moment().subtract(7, "days").startOf("week"));
            },
            onClickLastYear() {
                this.onUpdateEndDate(this.$moment().subtract(1, "year").endOf("year"));
                this.onUpdateStartDate(this.$moment().subtract(1, "year").startOf("year"));
            },
            onClickThisMonth() {
                this.onUpdateEndDate(this.$moment().endOf("month"));
                this.onUpdateStartDate(this.$moment().startOf("month"));
            },
            onClickThisWeek() {
                this.onUpdateEndDate(this.$moment().endOf("week"));
                this.onUpdateStartDate(this.$moment().startOf("week"));
            },
            onClickThisYear() {
                this.onUpdateEndDate(this.$moment().endOf("year"));
                this.onUpdateStartDate(this.$moment().startOf("year"));
            },
            onUpdateEndDate(newValue) {
                this.$emit("input:end-date", newValue);
            },
            onUpdateStartDate(newValue) {
                this.$emit("input:start-date", newValue);
            }
        },
        name: "DateRangePicker",
        props: {
            displayShortcutButtons: {
                default: true,
                required: false,
                type: Boolean
            },
            endDate: {
                required: true
            },
            startDate: {
                required: true
            }
        }
    }
</script>

<style scoped>

</style>
