<template>
    <div>
        <div class="flex items-center border-b border-gray-400">
            <div class="px-2 pt-2 pb-1 w-7/12 flex justify-between">
                <button class="focus:outline-none" @click="decreaseMonth">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <div v-html="value ? value.format('MMMM') : ''" />
                <button class="focus:outline-none" @click="increaseMonth">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            <div class="px-2 pt-2 pb-1 w-5/12 flex justify-between border-l border-gray-400">
                <button class="focus:outline-none" @click="decreaseYear">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <div v-html="value ? value.format('YYYY') : ''" />
                <button class="focus:outline-none" @click="increaseYear">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
        <div class="flex items-center border-b border-gray-400">
            <div class="block w-full">
                <keep-alive>
                    <component v-bind:is="visibleComponent" v-bind:value="value"
                               :config="config" :weeks="weeks" @input="onInput" />
                </keep-alive>
            </div>
        </div>
    </div>
</template>

<script>
    import DayPicker from "./DayPicker";

    export default {
        components: {
            DayPicker
        },
        data() {
            return {
                visibleComponent: "day-picker",
                weeks: []
            };
        },
        methods: {
            decreaseMonth() {
                let newValue = this.$moment(this.value).subtract(1, "months");
                this.$emit("input", newValue);
            },
            decreaseYear() {
                let newValue = this.$moment(this.value).subtract(1, "years");
                this.$emit("input", newValue);
            },
            increaseMonth() {
                let newValue = this.$moment(this.value).add(1, "months");
                this.$emit("input", newValue);
            },
            increaseYear() {
                let newValue = this.$moment(this.value).add(1, "years");
                this.$emit("input", newValue);
            },
            onInput(newValue) {
                this.$emit("input", newValue);
            },
            updateArrays() {
                this.updateWeeksArray();
                // this.updateDecadesArray();
            },
            updateDecadesArray() {

            },
            updateWeeksArray() {
                let startDate = this.$moment(this.value).startOf("month");
                let daysInMonth = startDate.daysInMonth();
                let weeks = [];
                let week = [];
                for(let i = 1; i <= daysInMonth; i++) {
                    let weekDay = startDate.day();
                    if(week.length == 0 && weekDay > 0) {
                        for(let j = 0; j < weekDay; j++) {
                            week[j] = "";
                        }
                    }
                    week[weekDay] = i.toString();
                    if(weekDay == 6) {
                        weeks.push(week);
                        week = [];
                    }
                    startDate.add(1, "day");
                }
                if(week.length > 0 && week.length < 7) {
                    for(let j = week.length; j < 7; j++) {
                        week[j] = "";
                    }
                    weeks.push(week);
                }
                this.weeks = JSON.parse(JSON.stringify(weeks));
            }
        },
        mounted() {
            this.updateArrays();
        },
        name: "DatePicker",
        props: {
            config: {
                required: true,
                type: Object
            },
            value: {
                required: true
            }
        },
        watch: {
            value: {
                deep: true,
                handler(newVal) {
                    this.updateArrays();
                }
            }
        }
    }
</script>

<style scoped>

</style>
