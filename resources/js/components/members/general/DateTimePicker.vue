<template>
    <div class="relative">
        <p>calendarDate: {{ calendarDate }}</p>
        <p>content: {{ content }}</p>
        <p>goldenCopy: {{ goldenCopy }}</p>
        <p>value: {{ value }}</p>
        <input class="relative block border border-gray-200 focus:outline-none focus:border-indigo-400 p-1 rounded w-full"
               type="text" v-model="content" :class="{ 'z-40': isOpen }"
               @focus="onFocus" @input="onInput" />
        <button v-if="isOpen" class="fixed inset-0 h-full w-full bg-transparent z-30 cursor-default w-" tabindex="-1" @click="isOpen = false"></button>
        <div v-if="isOpen" class="pmtech-date-time-picker-backdrop">
            <div class="pmtech-date-time-picker-arrow"></div>
            <component v-bind:is="components[pickerComponent]"
                       v-bind:value="calendarDate" :config="config"
                       @input="onInput" />
            <div class="mt-2 pb-2 flex items-center justify-around w-full">
                <button class="focus:outline-none"
                        @click="toggleMainPickerComponent">
                    <i v-if="config.pickTime && pickerComponent == 'Date'" class="fas fa-clock"></i>
                    <i v-else-if="config.pickDate && pickerComponent == 'Timer'" class="fas fa-calendar-alt"></i>
                </button>
                <button class="focus:outline-none" @click="onClickResetCalendarDate">
                    <i class="fas fa-calendar-day"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import DatePicker from "./dateTimePicker/DatePicker";
    import TimePicker from "./dateTimePicker/TimePicker";

    export default {
        beforeDestroy() {
            document.removeEventListener("keydown", this.onKeyDown);
        },
        components: {
            DatePicker,
            TimePicker
        },
        created() {
            document.addEventListener("keydown", this.onKeyDown);
        },
        data() {
            return {
                calendarDate: "",
                components: {
                    Date: "date-picker",
                    Timer: "time-picker"
                },
                content: this.value,
                goldenCopy: this.value,
                isOpen: true,
                pickerComponent: "Date"
            };
        },
        methods: {
            onClickResetCalendarDate() {
                this.calendarDate = this.$moment(this.goldenCopy,
                    this.config.format);
                this.content = this.calendarDate.format(this.config.format);
            },
            onFocus() {
                this.goldenCopy = this.value;
                this.isOpen = true;
            },
            onInput(newValue) {
                console.log("DateTimePicker::onInput")
                console.log("  newValue: " + newValue.format(this.config.format));
                this.$emit("input", newValue);
            },
            onKeyDown(e) {
                if(e.key === "Esc" || e.key === "Escape") {
                    this.isOpen = false;
                }
            },
            toggleMainPickerComponent() {
                if(this.pickerComponent == "Date") {
                    this.pickerComponent = "Timer";
                } else if(this.pickerComponent == "Timer") {
                    this.pickerComponent = "Date";
                }
            }
        },
        mounted() {
            if(this.content === null || this.content === undefined) {
                this.calendarDate = this.$moment().utc().local();
                this.content = this.calendarDate.format(this.config.format);
            } else {
                this.calendarDate = this.$moment(this.content,
                    "YYYY-MM-DD HH:mm:ss");
                this.content = this.calendarDate.format(this.config.format);
            }
        },
        name: "DateTimePicker",
        props: {
            config: {
                default: function () { return {
                    format: "DD MMM YYYY HH:mm:ss",
                    futureDatesAllowed: true,
                    pickDate: true,
                    pickTime: true
                }}
            },
            value: {
                required: true
            }
        },
        watch: {
            value: {
                deep: true,
                handler(newVal) {
                    this.calendarDate = this.$moment(newVal, this.config.format);
                    this.content = this.calendarDate.format(this.config.format);
                }
            }
        }
    }
</script>

<style scoped>
    .pmtech-date-time-picker-arrow {
        @apply absolute bg-white;
        width: 1.35em;
        height: 1em;
        top: -0.9em;
        left: 2em;
    }
    .pmtech-date-time-picker-arrow:after {
        left: .5em;
        transform: rotate(45deg);
    }
    .pmtech-date-time-picker-arrow:after,
    .pmtech-date-time-picker-arrow:before {
        @apply absolute block bg-gray-400;
        content: "";
        width: 1em;
        height: 0.1em;
        top: 0.4em;
    }
    .pmtech-date-time-picker-arrow:before {
        left: -0.2em;
        transform: rotate(-45deg);
    }
    .pmtech-date-time-picker-backdrop {
        @apply absolute w-64 left-0 bg-white border-2 border-gray-400 rounded-lg shadow-xl z-40;
        margin-top: 0.75em;
    }
</style>
