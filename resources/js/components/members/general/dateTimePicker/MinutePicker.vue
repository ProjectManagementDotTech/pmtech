<template>
    <div class="flex items-center px-2 w-full">
        <div class="w-full">
            <div class="flex w-full" v-for="(minuteRow, rKey) in minutesAndSeconds"
                 :key="rKey">
                <div class="w-1/3" v-for="(minute, dKey) in minuteRow"
                     :class="specialMinuteClass(minute.value)" :key="dKey"
                     @click="onClickMinute(minute.value)">
                    <span>{{ minute.title }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            onClickMinute(minute) {
                let newDate = this.$moment();
                newDate.date(this.value.date());
                newDate.month(this.value.month());
                newDate.year(this.value.year());
                newDate.hour(this.value.hour());
                newDate.minute(minute);
                newDate.second(this.value.second());

                this.$emit("input", newDate);
            },
            specialMinuteClass(minute) {
                let result = "";

                if(minute >= 0 && minute <= 59) {
                    let valueMinute = this.value.minute();
                    let now = this.$moment();
                    if(this.config.futureDatesAllowed) {
                        result += "minute ";
                    } else {
                        let compareDate = this.$moment();
                        compareDate.minute(minute);
                        if(now.isAfter(compareDate)) {
                            result += "minute ";
                        } else {
                            result += "non-clickable-minute ";
                        }
                    }

                    let nowMinute = now.minute();
                    if(nowMinute >= minute && nowMinute <= minute + 4) {
                        result += "this-minute ";
                    }

                    let userDate = this.$moment(this.value, this.config.format);
                    let userMinute = userDate.minute();
                    if(userMinute >= minute && userMinute <= minute + 4) {
                        result += "selected ";
                    }

                    result = result.substr(0, result.length - 1);
                }

                return result;
            }
        },
        name: "MinutePicker",
        props: {
            config: {
                required: true,
                type: Object
            },
            minutesAndSeconds: {
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
    .minute {
        @apply cursor-pointer text-center;
    }
    .minute.this-minute.selected:hover {
        @apply border-gray-600;
    }
    .minute.selected {
        @apply bg-indigo-400 rounded text-white;
    }
    .minute.this-minute {
        @apply border-b-2 border-dotted border-gray-600;
    }
    .minute.this-minute.selected {
        @apply border-white;
    }
    .minute span {
        @apply inline-block text-center;
    }
    .minute:hover, .minute.selected:hover {
        @apply bg-gold-100 text-gray-800;
    }
</style>
