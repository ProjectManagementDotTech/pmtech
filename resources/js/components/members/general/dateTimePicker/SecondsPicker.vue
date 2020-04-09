<template>
    <div class="flex items-center px-2 w-full">
        <div class="w-full">
            <div class="flex w-full" v-for="(secondRow, rKey) in minutesAndSeconds"
                 :key="rKey">
                <div class="w-1/3" v-for="(second, dKey) in secondRow"
                     :class="specialSecondClass(second.value)" :key="dKey"
                     @click="onClickSecond(second.value)">
                    <span>{{ second.title }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            onClickSecond(second) {
                let newDate = this.$moment();
                newDate.date(this.value.date());
                newDate.month(this.value.month());
                newDate.year(this.value.year());
                newDate.hour(this.value.hour());
                newDate.minute(this.value.minute());
                newDate.second(second);

                this.$emit("input", newDate);
            },
            specialSecondClass(second) {
                let result = "";

                if(second >= 0 && second <= 59) {
                    let valueSecond = this.value.second();
                    let now = this.$moment();
                    if(this.config.futureDatesAllowed) {
                        result += "second ";
                    } else {
                        let compareDate = this.$moment();
                        compareDate.second(second);
                        if(now.isAfter(compareDate)) {
                            result += "second ";
                        } else {
                            result += "non-clickable-second ";
                        }
                    }

                    let nowSecond = now.second();
                    if(nowSecond >= second && nowSecond <= second + 4) {
                        result += "this-second ";
                    }

                    let userDate = this.$moment(this.value, this.config.format);
                    let userSecond = userDate.second();
                    if(userSecond >= second && userSecond <= second + 4) {
                        result += "selected ";
                    }

                    result = result.substr(0, result.length - 1);
                }

                return result;
            }
        },
        name: "SecondPicker",
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
    .second {
        @apply cursor-pointer text-center;
    }
    .second.this-second.selected:hover {
        @apply border-gray-600;
    }
    .second.selected {
        @apply bg-indigo-400 rounded text-white;
    }
    .second.this-second {
        @apply border-b-2 border-dotted border-gray-600;
    }
    .second.this-second.selected {
        @apply border-white;
    }
    .second span {
        @apply inline-block text-center;
    }
    .second:hover, .second.selected:hover {
        @apply bg-gold-100 text-gray-800;
    }
</style>
