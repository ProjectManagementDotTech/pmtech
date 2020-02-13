<template>
    <div>
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
            <div class="w-1/3 text-center">{{ this.value.format("HH") }}</div>
            <div class="w-1/3 text-center">{{ this.value.format("mm") }}</div>
            <div class="w-1/3 text-center">{{ this.value.format("ss") }}</div>
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
</template>

<script>
    export default {
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
