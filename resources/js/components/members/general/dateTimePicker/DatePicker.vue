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
                <button class="focus:outline-none" @click="onClickYear">
                    {{ value ? value.format("YYYY") : "" }}
                </button>
                <button class="focus:outline-none" @click="increaseYear">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
        <div class="flex items-center border-b border-gray-400">
            <div class="block w-full">
                <keep-alive>
                    <component v-bind:is="visibleComponent" v-bind:value="value"
                               :config="config" :decades="decades"
                               :weeks="weeks" :years="years"
                               @decade-picked="onDecadePicked"
                               @input="onInput"
                               @shift-decades="onShiftDecades" />
                </keep-alive>
            </div>
        </div>
    </div>
</template>

<script>
    import DayPicker from "./DayPicker";
    import DecadePicker from "./DecadePicker";
    import YearPicker from "./YearPicker";

    export default {
        components: {
            DayPicker,
            DecadePicker,
            YearPicker
        },
        data() {
            return {
                decades: [],
                visibleComponent: "day-picker",
                weeks: [],
                years: [],
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
            onClickYear() {
                if(this.visibleComponent == "day-picker") {
                    this.visibleComponent = "decade-picker";
                } else {
                    this.visibleComponent = "day-picker";
                }
            },
            onDecadePicked(newDecade) {
                this.updateYearsArray(newDecade);
                this.visibleComponent = "year-picker";
            },
            onInput(newValue) {
                this.$emit("input", newValue);
            },
            onShiftDecades(newDecade) {
                this.updateDecadesArray(newDecade);
            },
            updateArrays() {
                this.updateDecadesArray();
                this.updateWeeksArray();
            },
            updateDecadesArray(middleDecade) {
                let startDate = undefined;
                if(middleDecade == undefined) {
                    startDate = this.$moment(this.value).startOf("year");
                } else {
                    startDate = this.$moment(this.value).startOf("year")
                        .year(middleDecade);
                }
                /*
                 * Make sure we're at the start of the decade...
                 */
                let year = startDate.year();
                year = year - (year % 10);
                startDate.year(year - 50);

                /*
                 * Show 4 rows of 3 decades each, starting 50 years ago,
                 * spanning a century
                 */
                let decades = [];
                let row = [];
                for(let i = 0; i < 10; i += 3) {
                    for(let j = 0; j < 3; j++) {
                        if(i < 9 || (i == 9 && j < 2)) {
                            row[j] = startDate.year();
                            startDate.year(row[j] + 10);
                        } else {
                            row[j] = "";
                        }
                    }
                    decades.push(JSON.parse(JSON.stringify(row)));
                    row = [];
                }
                this.decades = JSON.parse(JSON.stringify(decades));
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
            },
            updateYearsArray(decade) {
                let startDate = this.$moment(this.value).startOf("year");
                startDate.year(decade);

                /*
                 * Show 4 rows of 3 decades each, starting 50 years ago,
                 * spanning a century
                 */
                let years = [];
                let row = [];
                for(let i = 0; i < 10; i += 3) {
                    for(let j = 0; j < 3; j++) {
                        if(i < 9 || (i == 9 && j < 1)) {
                            row[j] = startDate.year();
                            startDate.add(1, "year");
                        } else {
                            row[j] = "";
                        }
                    }
                    years.push(JSON.parse(JSON.stringify(row)));
                    row = [];
                }
                this.years = JSON.parse(JSON.stringify(years));
            },
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
