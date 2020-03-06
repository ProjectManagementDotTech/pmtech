<template>
    <div class="relative">
        <input class="relative block border border-gray-200 focus:outline-none focus:border-indigo-400 p-1 rounded w-full"
               type="text" :value="content" :class="{ 'z-40': isOpen }"
               :id="uuid" @focus="onFocus" @input="onInput" />
        <button v-if="isOpen"
                class="fixed inset-0 h-full w-full bg-transparent z-30 cursor-default w-"
                tabindex="-1" @click="onClickBackdrop"></button>
        <div v-if="isOpen" class="pmtech-date-time-picker-backdrop"
             :class="absolutePlacementClass" >
            <div class="pmtech-date-time-picker-arrow" :class="absolutePlacementClass"></div>
            <component v-bind:is="components[pickerComponent]"
                       v-bind:value="calendarDate" :config="config"
                       @input="onInput" />
            <div class="mt-2 pb-2 flex items-center justify-around w-full">
                <button class="focus:outline-none"
                        @click="toggleMainPickerComponent">
                    <i v-if="config.pickTime && pickerComponent == 'Date'"
                       class="fas fa-clock"></i>
                    <i v-else-if="config.pickDate && pickerComponent == 'Timer'"
                       class="fas fa-calendar-alt"></i>
                </button>
                <button class="focus:outline-none"
                        @click="onClickResetCalendarDate">
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
        computed: {
            absolutePlacementClass() {
                let el = document.getElementById(this.uuid);
                if(el) {
                    var box = el.getBoundingClientRect();
                    var body = document.body;
                    var docEl = document.documentElement;
                    var scrollLeft = window.pageXOffset || docEl.scrollLeft ||
                        body.scrollLeft;
                    var clientLeft = docEl.clientLeft || body.clientLeft || 0;
                    var left = Math.round(box.left + scrollLeft - clientLeft);
                    var width = 16 * parseFloat(
                        getComputedStyle(document.documentElement).fontSize);
                    var right = left + width;
                    if(right > document.body.clientWidth) {
                        return "right";
                    } else {
                        return "left";
                    }
                }
            }
        },
        created() {
            document.addEventListener("keydown", this.onKeyDown);
            this.uuid = this.$utils.uuid();
            this.createConfigObject();
        },
        data() {
            return {
                calendarDate: "",
                components: {
                    Date: "date-picker",
                    Timer: "time-picker"
                },
                config: {},
                content: this.value,
                defaultConfig: {
                    format: "DD MMM YYYY HH:mm:ss",
                    futureDatesAllowed: true,
                    pickDate: true,
                    pickTime: true
                },
                goldenCopy: this.value,
                isOpen: false,
                pickerComponent: "Date",
                uuid: ""
            };
        },
        methods: {
            createConfigObject() {
                this.config = JSON.parse(JSON.stringify(this.defaultConfig));
                let keys = Object.keys(this.userConfig);
                for(let i = 0; i < keys.length; i++) {
                    let key = keys[i];
                    this.config[key] = this.userConfig[key];
                }
            },
            onClickBackdrop() {
                this.isOpen = false;
                this.$emit("blur");
            },
            onClickResetCalendarDate() {
                this.calendarDate = this.$moment(this.goldenCopy,
                    this.config.format);
                this.content = this.calendarDate.format(this.config.format);
                this.$emit("input", this.calendarDate);
            },
            onFocus() {
                this.goldenCopy = this.value;
                this.isOpen = true;
            },
            onInput(newValue) {
                if(newValue.target !== undefined) {
                    this.calendarDate = this.$moment(newValue.target.value,
                        this.config.format);
                    if(this.calendarDate.isValid()) {
                        this.content = this.calendarDate.format(
                            this.config.format);
                        this.$emit("input", this.calendarDate);
                    } else {
                        this.calendarDate = this.$moment(this.content,
                            this.config.format);
                        this.content = newValue.target.value;
                        /*
                         * Nothing is to be emitted here, as the input is, in
                         * fact, invalid!
                         * -- glj
                         */
                    }
                } else {
                    this.$emit("input", newValue);
                }
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
                this.onInput(this.calendarDate);
            } else {
                this.calendarDate = this.$moment(this.content,
                    "YYYY-MM-DD HH:mm:ss");
                this.content = this.calendarDate.format(this.config.format);
            }
        },
        name: "DateTimePicker",
        props: {
            userConfig: {
                required: false,
            },
            value: {
                required: true
            }
        },
        watch: {
            value: {
                deep: true,
                handler(newVal) {
                    if(newVal) {
                        this.calendarDate = this.$moment(newVal, this.config.format);
                        this.content = this.calendarDate.format(this.config.format);
                    } else {
                        this.calendarDate = this.$moment().utc().local();
                        this.content = this.calendarDate.format(this.config.format);
                        this.onInput(this.calendarDate);
                    }
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
    }
    .pmtech-date-time-picker-arrow.left {
        left: 2em;
    }
    .pmtech-date-time-picker-arrow.right {
        right: 2em;
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
        @apply absolute w-64 bg-white border-2 border-gray-400 rounded-lg shadow-xl z-40;
        margin-top: 0.75em;
    }
    .pmtech-date-time-picker-backdrop.left {
        @apply left-0;
    }
    .pmtech-date-time-picker-backdrop.right {
        @apply right-0;
    }
</style>
