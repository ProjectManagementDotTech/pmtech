<template>
    <div class="flex items-center px-2 w-full">
        <div class="w-full">
            <div class="flex w-full" v-for="(yearRow, rKey) in years"
                 :key="rKey">
                <div class="w-1/3" v-for="(year, dKey) in yearRow"
                     :class="specialYearClass(year)" :key="dKey"
                     @click="onClickYear(year)">
                    <span v-if="year != ''">{{ year }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            onClickYear(year) {
                let newDate = this.$moment();
                newDate.date(this.value.date());
                newDate.month(this.value.month());
                newDate.year(year);
                newDate.hour(this.value.hour());
                newDate.minute(this.value.minute());
                newDate.second(this.value.second());

                this.$emit("input", newDate);
            },
            specialYearClass(year) {
                let result = "";

                if(year !== "") {
                    let valueYear = this.value.year();
                    let now = this.$moment();
                    if(this.config.futureDatesAllowed) {
                        result += "year ";
                    } else {
                        let compareDate = this.$moment();
                        compareDate.year(year);
                        if(now.isAfter(compareDate)) {
                            result += "year ";
                        } else {
                            result += "non-clickable-year ";
                        }
                    }

                    let nowYear = now.year();
                    if(nowYear == year) {
                        result += "this-year ";
                    }

                    let userDate = this.$moment(this.value, this.config.format);
                    let userYear = userDate.year();
                    if(userYear == year) {
                        result += "selected ";
                    }

                    result = result.substr(0, result.length - 1);
                }

                return result;
            }
        },
        name: "YearPicker",
        props: {
            config: {
                required: true,
                type: Object
            },
            value: {
                required: true
            },
            years: {
                required: true,
                type: Array
            }
        }
    }
</script>

<style scoped>
    .year {
        @apply cursor-pointer text-center;
    }
    .year.this-year.selected:hover {
        @apply border-gray-600;
    }
    .year.selected {
        @apply bg-indigo-400 rounded text-white;
    }
    .year.this-year {
        @apply border-b-2 border-dotted border-gray-600;
    }
    .year.this-year.selected {
        @apply border-white;
    }
    .year span {
        @apply inline-block text-center;
    }
    .year:hover, .year.selected:hover {
        @apply bg-gold-100 text-gray-800;
    }
</style>
