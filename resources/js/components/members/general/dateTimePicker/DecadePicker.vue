<template>
    <div class="flex items-center px-2 w-full">
        <div class="cursor-pointer w-1/12" @click="onClickPrevious">
            <i class="fas fa-chevron-left"></i>
        </div>
        <div class="w-5/6">
            <div class="flex w-full" v-for="(decadeRow, rKey) in decades"
                 :key="rKey">
                <div class="w-1/3" v-for="(decade, dKey) in decadeRow"
                     :class="specialDecadesClass(decade)" :key="dKey"
                     @click="onClickDecade(decade)">
                    <span v-if="decade != ''">{{ decade }}</span>
                </div>
            </div>
        </div>
        <div class="cursor-pointer text-right w-1/12" @click="onClickNext">
            <i class="fas fa-chevron-right"></i>
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            onClickDecade(decade) {
                this.$emit("decade-picked", decade);
            },
            onClickNext() {
                this.$emit("shift-decades", this.decades[3][1]);
            },
            onClickPrevious() {
                this.$emit("shift-decades", this.decades[0][0]);
            },
            specialDecadesClass(decade) {
                let result = "";

                if(decade !== "") {
                    let valueYear = this.value.year();
                    let now = this.$moment();
                    if(this.config.futureDatesAllowed) {
                        result += "decade ";
                    } else {
                        let compareDate = this.$moment();
                        compareDate.year(decade);
                        if(now.isAfter(compareDate)) {
                            result += "decade ";
                        } else {
                            result += "non-clickable-decade ";
                        }
                    }

                    let compareDateStart = this.$moment().startOf("year");
                    compareDateStart.year(decade);
                    let startYear = compareDateStart.year();
                    compareDateStart.year(startYear - (startYear % 10));
                    let compareDateEnd = this.$moment(compareDateStart);
                    compareDateEnd.add(10, "years").subtract(1, "day");
                    if(
                        now.isAfter(compareDateStart) &&
                        now.isBefore(compareDateEnd)
                    ) {
                        result += "this-decade ";
                    }

                    let userDate = this.$moment(this.value, this.config.format);
                    let userYear = userDate.year();
                    if(userYear >= decade && userYear < decade + 10) {
                        result += "selected ";
                    }

                    result = result.substr(0, result.length - 1);
                }

                return result;
            }
        },
        name: "DecadePicker",
        props: {
            config: {
                required: true,
                type: Object
            },
            decades: {
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
    .decade {
        @apply cursor-pointer text-center;
    }
    .decade.this-decade.selected:hover {
        @apply border-gray-600;
    }
    .decade.selected {
        @apply bg-indigo-400 rounded text-white;
    }
    .decade.this-decade {
        @apply border-b-2 border-dotted border-gray-600;
    }
    .decade.this-decade.selected {
        @apply border-white;
    }
    .decade span {
        @apply inline-block text-center;
    }
    .decade:hover, .decade.selected:hover {
        @apply bg-gold-100 text-gray-800;
    }
</style>
