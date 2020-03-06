<template>
    <div class="flex items-center px-2 w-full">
        <div class="w-full">
            <div class="flex w-full" v-for="(hourRow, rKey) in hours"
                 :key="rKey">
                <div class="w-1/3" v-for="(hour, dKey) in hourRow"
                     :class="specialHourClass(hour)" :key="dKey"
                     @click="onClickHour(hour)">
                    <span v-if="hour != ''">{{ hour }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            onClickHour(hour) {
                let newDate = this.$moment();
                newDate.date(this.value.date());
                newDate.month(this.value.month());
                newDate.year(this.value.year());
                newDate.hour(hour);
                newDate.minute(this.value.minute());
                newDate.second(this.value.second());

                this.$emit("input", newDate);
            },
            specialHourClass(hour) {
                let result = "";

                if(hour >= 0 && hour <= 23) {
                    let valueHour = this.value.hour();
                    let now = this.$moment();
                    if(this.config.futureDatesAllowed) {
                        result += "hour ";
                    } else {
                        let compareDate = this.$moment();
                        compareDate.hour(hour);
                        if(now.isAfter(compareDate)) {
                            result += "hour ";
                        } else {
                            result += "non-clickable-hour ";
                        }
                    }

                    let nowHour = now.hour();
                    if(nowHour == hour) {
                        result += "this-hour ";
                    }

                    let userDate = this.$moment(this.value, this.config.format);
                    let userHour = userDate.hour();
                    if(userHour == hour) {
                        result += "selected ";
                    }

                    result = result.substr(0, result.length - 1);
                }

                return result;
            }
        },
        name: "HourPicker",
        props: {
            config: {
                required: true,
                type: Object
            },
            hours: {
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
    .hour {
        @apply cursor-pointer text-center;
    }
    .hour.this-hour.selected:hover {
        @apply border-gray-600;
    }
    .hour.selected {
        @apply bg-indigo-400 rounded text-white;
    }
    .hour.this-hour {
        @apply border-b-2 border-dotted border-gray-600;
    }
    .hour.this-hour.selected {
        @apply border-white;
    }
    .hour span {
        @apply inline-block text-center;
    }
    .hour:hover, .hour.selected:hover {
        @apply bg-gold-100 text-gray-800;
    }
</style>
