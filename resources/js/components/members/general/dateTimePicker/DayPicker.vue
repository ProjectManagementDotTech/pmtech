<template>
    <div class="block">
        <div class="w-full px-4 flex items-center justify-between border-b border-gray-400">
            <div class="w-1/7 text-center">Su</div>
            <div class="w-1/7 text-center">Mo</div>
            <div class="w-1/7 text-center">Tu</div>
            <div class="w-1/7 text-center">We</div>
            <div class="w-1/7 text-center">Th</div>
            <div class="w-1/7 text-center">Fr</div>
            <div class="w-1/7 text-center">Sa</div>
        </div>
        <div v-for="(week, wkey) in weeks" :key="wkey"
             class="w-full px-4 flex items-center justify-between week">
            <div v-for="(day, dkey) in week" :key="dkey" class="w-1/7"
                 :class="specialDayClasses(day)" @click="onClickDay(day)">
                <span v-if="day != ''">{{ day }}</span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            onClickDay(aDate) {
                let newDate = this.$moment();
                newDate.date(aDate);
                newDate.month(this.value.month());
                newDate.year(this.value.year());
                newDate.hour(this.value.hour());
                newDate.minute(this.value.minute());
                newDate.second(this.value.second());

                this.$emit("input", newDate);
            },
            specialDayClasses(aDate) {
                let result = "";
                if(aDate !== "") {
                    let valueMonth = this.value.month();
                    let valueYear = this.value.year();
                    let now = this.$moment();
                    if (this.config.futureDatesAllowed) {
                        result += "day ";
                    } else {
                        let compareDate = this.$moment(aDate + "/" +
                            (valueMonth + 1) + "/" + valueYear + " " +
                            this.value.hour() + ":" + this.value.minute() +
                            ":" + this.value.second, "DD/MM/YYYY HH:mm:ss");
                        if (now.isAfter(compareDate)) {
                            result += "day ";
                        } else {
                            result += "non-clickable-day ";
                        }
                    }

                    let nowDate = now.date();
                    let nowMonth = now.month();
                    let nowYear = now.year();
                    if (
                        aDate == nowDate &&
                        valueMonth == nowMonth &&
                        valueYear == nowYear
                    ) {
                        result += "today ";
                    }

                    let userDate = this.$moment(this.value, this.config.format);
                    let userDateDate = userDate.date();
                    let userMonth = userDate.month();
                    let userYear = userDate.year();
                    if (aDate == userDateDate && valueMonth == userMonth && valueYear == userYear) {
                        result += "selected ";
                    }
                    return result.substr(0, result.length - 1);
                } else {
                    return result;
                }
            }
        },
        name: "DayPicker",
        props: {
            config: {
                required: true,
                type: Object
            },
            value: {
                required: true
            },
            weeks: {
                required: true,
                type: Array
            }
        }
    }
</script>

<style scoped>
    .day {
        @apply cursor-pointer text-center;
    }
    .day.today.selected:hover {
        @apply border-gray-600;
    }
    .day.selected {
        @apply bg-indigo-400 text-white rounded;
    }
    .day.selected:first-child, .day.selected:last-child {
        @apply text-gray-400;
    }
    .day.today {
        @apply border-b-2 border-dotted border-gray-600;
    }
    .day.today.selected {
        @apply border-white;
    }
    .day span {
        @apply inline-block text-center;
    }
    .day:first-child, .day:last-child {
        @apply text-gray-600;
    }
    .day:hover, .day.selected:hover {
        @apply bg-gold-100 text-gray-800;
    }
    .day.selected:first-child:hover, .day.selected:last-child:hover {
        @apply text-gray-600;
    }
</style>
