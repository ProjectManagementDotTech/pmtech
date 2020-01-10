<template>
    <div>
        <div v-if="isNew && isManual">
            New and Manual
        </div>
        <div v-else-if="isNew && !isManual">
            <div v-if="!started">
                <button @click.stop="onClickStart">
                    <i class="fas fa-play-circle text-green-500 text-2xl" />
                </button>
            </div>
            <div v-else>
                {{ elapsedTime }}
                <button @click.stop="onClickStop">
                    <i class="fas fa-stop-circle text-red-500 text-2xl" />
                </button>
            </div>
        </div>
        <div v-else-if="!isNew && isManual">
            !New and Manual
        </div>
        <div v-else>
            {{ timesheetEntry.duration }}
            <button @click.stop="onClickStartFromHistory">
                <i class="fas fa-play-circle text-green-500 text-2xl" />
            </button>
        </div>
    </div>
</template>

<script>
    import Vue from "vue";

    export default {
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
            }
        },
        data() {
            return {
                elapsedTimeInSeconds: 0,
                intervalHandle: undefined,
                isManual: false
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
