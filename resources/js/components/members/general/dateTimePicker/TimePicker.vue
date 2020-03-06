<template>
    <div v-if="visibleComponent == ''">
        <div class="flex items-center mt-4">
            <div class="w-1/3 cursor-pointer text-center" @click="increaseHour">
                <i class="fas fa-chevron-up"></i>
            </div>
            <div class="w-1/3 cursor-pointer text-center" @click="increaseMinute">
                <i class="fas fa-chevron-up"></i>
            </div>
            <div class="w-1/3 cursor-pointer text-center" @click="increaseSecond">
                <i class="fas fa-chevron-up"></i>
            </div>
        </div>
        <div class="flex items-center">
            <div class="cursor-pointer text-center w-1/3" @click="onClickHour">
                {{ this.value.format("HH") }}
            </div>
            <div class="cursor-pointer text-center w-1/3" @click="onClickMinute">
                {{ this.value.format("mm") }}
            </div>
            <div class="cursor-pointer text-center w-1/3" @click="onClickSecond">
                {{ this.value.format("ss") }}
            </div>
        </div>
        <div class="flex items-center mb-4">
            <div class="w-1/3 cursor-pointer text-center" @click="decreaseHour">
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="w-1/3 cursor-pointer text-center" @click="decreaseMinute">
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="w-1/3 cursor-pointer text-center" @click="decreaseSecond">
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </div>
    <div v-else class="py-2">
        <keep-alive>
            <component v-bind:is="visibleComponent" v-bind:value="value"
                       :config="config" :hours="hours"
                       :minutes-and-seconds="minutesAndSeconds"
                       @input="onInput" />
        </keep-alive>
    </div>
</template>

<script>
    import HourPicker from "./HourPicker";
    import MinutePicker from "./MinutePicker";
    import SecondsPicker from "./SecondsPicker";

    export default {
        components: {
            HourPicker,
            MinutePicker,
            SecondsPicker
        },
        data() {
            return {
                minutesAndSeconds: [
                    [ { title: "00 - 04", value: 0 }, { title: "05 - 09", value: 5 }, { title: "10 - 14", value: 10 } ],
                    [ { title: "15 - 19", value: 15 }, { title: "20 - 24", value: 20 }, { title: "25 - 29", value: 25 } ],
                    [ { title: "30 - 34", value: 30 }, { title: "35 - 39", value: 35 }, { title: "40 - 44", value: 40 } ],
                    [ { title: "45 - 49", value: 45 }, { title: "50 - 54", value: 50 }, { title: "55 - 59", value: 55 } ]
                ],
                hours: [
                    [ "00", "01", "02", "03", "04", "05" ],
                    [ "06", "07", "08", "09", "10", "11" ],
                    [ "12", "13", "14", "15", "16", "17" ],
                    [ "18", "19", "20", "21", "22", "23" ]
                ],
                visibleComponent: ""
            };
        },
        methods: {
            decreaseHour() {
                let newValue = this.$moment(this.value).subtract(1, "hours");
                this.$emit("input", newValue);
            },
            decreaseMinute() {
                let newValue = this.$moment(this.value).subtract(1, "minutes");
                this.$emit("input", newValue);
            },
            decreaseSecond() {
                let newValue = this.$moment(this.value).subtract(1, "seconds");
                this.$emit("input", newValue);
            },
            increaseHour() {
                let newValue = this.$moment(this.value).add(1, "hours");
                this.$emit("input", newValue);
            },
            increaseMinute() {
                let newValue = this.$moment(this.value).add(1, "minutes");
                this.$emit("input", newValue);
            },
            increaseSecond() {
                let newValue = this.$moment(this.value).add(1, "seconds");
                this.$emit("input", newValue);
            },
            onClickHour() {
                this.visibleComponent = "hour-picker";
            },
            onClickMinute() {
                this.visibleComponent = "minute-picker";
            },
            onClickSecond() {
                this.visibleComponent = "seconds-picker";
            },
            onInput(newDateTime) {
                this.visibleComponent = "";
                this.$emit("input", newDateTime);
            }
        },
        name: "TimePicker",
        props: {
            config: {
                required: true,
                type: Object
            },
            value: {
                required: true
            }
        }
    }
</script>

<style scoped>

</style>
