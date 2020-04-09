<template>
    <div class="flex items-center px-2 py-2 w-full">
        <div class="w-full">
            <div class="flex w-full" v-for="(monthRow, rKey) in months"
                 :key="rKey">
                <div class="w-1/3" v-for="(month, dKey) in monthRow"
                     :class="specialMonthClass(month.m)" :key="dKey"
                     @click="onClickMonth(month.m)">
                    <span v-if="month.mmmm != ''">{{ month.mmmm }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            onClickMonth(month) {
                let newDate = this.$moment();
                newDate.date(this.value.date());
                newDate.month(month);
                newDate.year(this.value.year());
                newDate.hour(this.value.hour());
                newDate.minute(this.value.minute());
                newDate.second(this.value.second());

                this.$emit("input", newDate);
            },
            specialMonthClass(month) {
                let result = "";

                if(month >= 0 && month <= 11) {
                    let valueMonth = this.value.month();
                    let now = this.$moment();
                    if(this.config.futureDatesAllowed) {
                        result += "month ";
                    } else {
                        let compareDate = this.$moment();
                        compareDate.month(month + 1).startOf("month");
                        if(now.isAfter(compareDate)) {
                            result += "month ";
                        } else {
                            result += "non-clickable-year ";
                        }
                    }

                    let nowMonth = now.month();
                    if(nowMonth == month) {
                        result += "this-month ";
                    }

                    let userDate = this.$moment(this.value, this.config.format);
                    let userMonth = userDate.month();
                    if(userMonth == month) {
                        result += "selected ";
                    }

                    result = result.substr(0, result.length - 1);
                }

                return result;
            }
        },
        name: "MonthPicker",
        props: {
            config: {
                required: true,
                type: Object
            },
            months: {
                required: true,
                type: Array
            },
            value: {
                required: true
            }
        }
    }
</script>

<style scoped>
    .month {
        @apply cursor-pointer text-center;
    }
    .month.this-month.selected:hover {
        @apply border-gray-600;
    }
    .month.selected {
        @apply bg-indigo-400 rounded text-white;
    }
    .month.this-month {
        @apply border-b-2 border-dotted border-gray-600;
    }
    .month.this-month.selected {
        @apply border-white;
    }
    .month span {
        @apply inline-block text-center;
    }
    .month:hover, .month.selected:hover {
        @apply bg-gold-100 text-gray-800;
    }
</style>
