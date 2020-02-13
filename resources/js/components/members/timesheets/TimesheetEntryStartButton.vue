<template>
    <div>
        <div v-if="isNew" class="flex items-center">
            <div class="w-full" v-if="!started">
                <button class="float-right" :disabled="timesheetEntry.description.length == 0"
                        @click.stop="onClickStart">
                    <i class="fas fa-play-circle text-green-500 text-2xl" />
                </button>
            </div>
            <template v-else>
                <div class="w-5/12">
                    <date-time-picker v-bind:value="timesheetEntry.started_at"
                                      @blur="$emit('blur')"
                                      @input="onInputStartedAt" />
                </div>
                <div class="w-5/12 p-1 border border-gray-200 rounded  overflow-hidden whitespace-no-wrap">
                    {{ elapsedTime }}
                </div>
                <div class="w-2/12">
                <button class="float-right" @click.stop="onClickStop">
                    <i class="fas fa-stop-circle text-red-500 text-2xl" />
                </button>
                </div>
            </template>
        </div>
        <div v-else class="flex items-center">
            <template>
                <div v-if="!editing"
                     class="w-5/12 p-1 border border-gray-200 rounded overflow-hidden whitespace-no-wrap"
                     @click="onClickToggleEditing">
                    {{ timesheetEntryStartedAt }}
                </div>
                <div v-else class="w-5/12">
                    <date-time-picker v-bind:value="timesheetEntry.started_at"
                                      @input="onInputStartedAt" />
                </div>
            </template>
            <template>
                <div v-if="!editing"
                     class="w-5/12 p-1 border border-gray-200 rounded  overflow-hidden whitespace-no-wrap"
                     @click="onClickToggleEditing">
                    {{ timesheetEntry.duration }}
                </div>
                <div v-else class="w-5/12">
                    <date-time-picker v-bind:value="timesheetEntry.ended_at"
                                      @input="onInputEndedAt" />
                </div>
            </template>
            <div class="w-2/12">
                <button v-if="!editing" class="float-right"
                        @click.stop="onClickStartFromHistory">
                    <i class="fas fa-play-circle text-green-500 text-2xl" />
                </button>
                <button v-else class="float-right"
                        @click.stop="onClickSave">
                    <i class="fas fa-save text-green-500 text-2xl"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import DateTimePicker from "../general/DateTimePicker";
    import Vue from "vue";

    export default {
        components: {
            DateTimePicker
        },
        computed: {
            elapsedTime() {
                if(this.elapsedTimeInSeconds < 60) {
                    return "00:00:" + this.elapsedTimeInSeconds
                        .toString()
                        .padStart(2, "0");
                } else if(this.elapsedTimeInSeconds < 3600) {
                    let seconds = this.elapsedTimeInSeconds;
                    let minutes = Math.floor(seconds / 60);
                    seconds -= (minutes * 60);
                    return "00:" + minutes.toString().padStart(2, "0") + ":" +
                        seconds.toString().padStart(2, "0");
                } else {
                    let seconds = this.elapsedTimeInSeconds;
                    let hours = Math.floor(seconds / 3600);
                    seconds -= (hours * 3600);
                    let minutes = Math.floor(seconds / 60);
                    seconds -= (minutes * 60);
                    return hours.toString().padStart(2, "0") + ":" +
                        minutes.toString().padStart(2, "0") + ":" +
                        seconds.toString().padStart(2, "0");
                }
            },
            started() {
                return this.timesheetEntry.started_at != null &&
                    this.timesheetEntry.ended_at == null;
            },
            timesheetEntryStartedAt() {
                if(typeof this.timesheetEntry.started_at == "string") {
                    return this.$moment(this.timesheetEntry.started_at,
                        "YYYY-MM-DD HH:mm:ss").format("DD MMM YYYY HH:mm:ss");
                } else {
                    return this.timesheetEntry.started_at
                        .format("DD MMM YYYY HH:mm:ss");
                }
            }
        },
        data() {
            return {
                editing: false,
                elapsedTimeInSeconds: 0,
                intervalHandle: undefined,
            }
        },
        methods: {
            calculateElapsedTimeInSeconds() {
                if(this.started) {
                    this.elapsedTimeInSeconds =
                        this.$moment.utc().diff(
                            this.$moment.utc(this.timesheetEntry.started_at,
                                "YYYY-MM-DD HH:mm:ss"
                            ), "seconds");
                    this.intervalHandle = window.setInterval(() => {
                        this.elapsedTimeInSeconds++;
                    }, 1000);
                }
            },
            onClickSave() {
                this.$emit("save");
                this.editing = false;
            },
            onClickStart() {
                this.$emit("start");
            },
            onClickStartFromHistory() {
                this.$emit("start", this.timesheetEntry);
            },
            onClickStop() {
                if(this.intervalHandle !== undefined) {
                    window.clearInterval(this.intervalHandle);
                }
                Vue.set(this.timesheetEntry, "ended_at",
                    this.$moment.utc().format("YYYY-MM-DD HH:mm:ss"));
                this.$emit("stop");
            },
            onClickToggleEditing() {
                this.editing = true;
            },
            onInputEndedAt(newValue) {
                this.$emit("input-ended-at", newValue);
            },
            onInputStartedAt(newValue) {
                this.$emit("input-started-at", newValue);
            }
        },
        mounted() {
            this.calculateElapsedTimeInSeconds();
        },
        name: "TimesheetEntryStartButton",
        props: {
            isNew: {
                default: false,
                required: false,
                type: Boolean
            },
            timesheetEntry: {
                required: true,
                type: Object
            }
        },
        watch: {
            timesheetEntry: {
                deep: true,
                handler(newVal, oldVal) {
                    if(this.intervalHandle !== undefined) {
                        window.clearInterval(this.intervalHandle);
                    }
                    this.calculateElapsedTimeInSeconds();
                }
            }
        }
    }
</script>

<style scoped>

</style>
