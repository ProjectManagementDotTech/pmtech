<template>
    <svg class="w-full h-64"></svg>
</template>

<script>
    import * as d3 from "d3";

    export default {
        data() {
            return {
                chart: null,
                chartableDateRange: [],
                chartableLineData: [],
                maximumHours: 0
            }
        },
        methods: {
            colorLuminance(hex, lum) {
                /*
                 * (C) Craig Buckler
                 * https://www.sitepoint.com/javascript-generate-lighter-darker-color/
                 */
                hex = String(hex).replace(/[^0-9a-f]/gi, "");
                if(hex.length < 6) {
                    hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
                }
                lum = lum || 0;
                var rgb = "", c, i;
                for(i = 0; i < 3; i++) {
                    c = parseInt(hex.substr(i * 2, 2), 16);
                    c = Math.round(Math.min(Math.max(0, c + (c * lum)), 255)).toString(16);
                    rgb += ("00" + c).substr(c.length);
                }

                return rgb;
            },
            getTotalProjectDayDuration(project, day) {
                let result = 0;
                this.timesheetEntries.forEach(timesheetDayGroup => {
                    let timesheetDate = this.$moment(timesheetDayGroup.date, "YYYY-MM-DD");
                    if(timesheetDayGroup.date == day.format("YYYY-MM-DD")) {
                        timesheetDayGroup.entries.forEach(timesheetEntry => {
                            if(
                                (
                                    project == null &&
                                    timesheetEntry.project_id == null
                                ) || (
                                    project != null &&
                                    timesheetEntry.project_id == project.id
                                )
                            ) {
                                result += this.$moment(timesheetEntry.ended_at)
                                    .diff(timesheetEntry.started_at, "seconds");
                            }
                        });
                    }
                });

                return result;
            },
            getTotalTaskDayDuration(task, day) {
                let result = 0;
                this.timesheetEntries.forEach(timesheetDayGroup => {
                    let timesheetDate = this.$moment(timesheetDayGroup.date, "YYYY-MM-DD");
                    if(timesheetDayGroup.date == day.format("YYYY-MM-DD")) {
                        timesheetDayGroup.entries.forEach(timesheetEntry => {
                            if(
                                (
                                    task == null &&
                                    timesheetEntry.task_id == null
                                ) || (
                                    task != null &&
                                    timesheetEntry.task_id == task.id
                                )
                            ) {
                                result += this.$moment(timesheetEntry.ended_at)
                                    .diff(timesheetEntry.started_at, "seconds");
                            }
                        });
                    }
                });

                return result;
            },
            initializeChartableDateRange() {
                let sd = this.$moment(this.filter.startDate);
                let ed = this.filter.endDate;

                this.chartableDateRange = [];

                while(sd.isSameOrBefore(ed)) {
                    this.chartableDateRange.push({
                        day: this.$moment(sd),
                    });
                    sd.add(1, "days");
                }
            },
            initializeChartableLineData() {
                this.chartableLineData = [];
                this.maximumHours = 0;

                if (this.filter.selectedProject == undefined) {
                    return this.initializeChartableLineDataAcrossProjects();
                } else {
                    return this.initializeChartableLineDataAcrossTasks();
                }
            },
            initializeChartableLineDataAcrossProjects() {
                return new Promise((resolve, reject) => {
                    let projects = this.$store.getters["projects/all"];
                    projects.forEach(project => {
                        let durations = [];
                        this.chartableDateRange.forEach(rangeDate => {
                            let hours = this.getTotalProjectDayDuration(project,
                                this.$moment(rangeDate.day, "YYYY-MM-DD")) / 3600;
                            if(hours > this.maximumHours) {
                                this.maximumHours = Math.ceil(hours);
                            }
                            durations.push({
                                day: rangeDate.day.format("DD MMM YYYY"),
                                hours: hours
                            });
                        });
                        this.chartableLineData.push({
                            color: project.color,
                            durations: durations,
                            name: project.name
                        });
                    });

                    /*
                     * Now the timesheet entries without project
                     */
                    let durations = [];
                    this.chartableDateRange.forEach(rangeDate => {
                        let hours = this.getTotalProjectDayDuration(null,
                            this.$moment(rangeDate.day, "YYYY-MM-DD")) / 3600;
                        if(hours > this.maximumHours) {
                            this.maximumHours = Math.ceil(hours);
                        }
                        durations.push({
                            day: rangeDate.day.format("DD MMM YYYY"),
                            hours: hours
                        });
                    });
                    this.chartableLineData.push({
                        color: "000000",
                        durations: durations,
                        name: "No Project"
                    });

                    resolve();
                });
            },
            initializeChartableLineDataAcrossTasks() {
                return new Promise((resolve, reject) => {
                    let project = this.$store.getters["projects/byId"](this.filter.selectedProject.id);
                    this.$axios.get("/projects/" + this.filter.selectedProject.id + "/tasks")
                        .then(response => {
                            let tasks = response.data;
                            let luminosity = -0.85;
                            let luminosityFactor = 1.7/(tasks.length);
                            tasks.forEach(task => {
                                let durations = [];
                                this.chartableDateRange.forEach(rangeDate => {
                                    let hours = this.getTotalTaskDayDuration(task,
                                        this.$moment(rangeDate.day, "YYYY-MM-DD")) / 3600;
                                    if(hours > this.maximumHours) {
                                        this.maximumHours = Math.ceil(hours);
                                    }
                                    durations.push({
                                        day: rangeDate.day.format("DD MMM YYYY"),
                                        hours: hours
                                    });
                                });
                                this.chartableLineData.push({
                                    color: this.colorLuminance(project.color, luminosity),
                                    durations: durations,
                                    name: task.name
                                });
                                luminosity += luminosityFactor;
                            });

                            /*
                             * Now the timesheet entries without task
                             */
                            let durations = [];
                            this.chartableDateRange.forEach(rangeDate => {
                                let hours = this.getTotalTaskDayDuration(null,
                                    this.$moment(rangeDate.day, "YYYY-MM-DD")) / 3600;
                                if(hours > this.maximumHours) {
                                    this.maximumHours = Math.ceil(hours);
                                }
                                durations.push({
                                    day: rangeDate.day.format("DD MMM YYYY"),
                                    hours: hours
                                });
                            });
                            this.chartableLineData.push({
                                color: this.colorLuminance(project.color, luminosity),
                                durations: durations,
                                name: "No Task"
                            });
                        })
                        .finally(() => {
                            resolve();
                        });
                });
            },
            normalizeChartableLineData() {
                for(let i = 0; i < this.chartableLineData.length; i++) {
                    let noneZeroDurationCount = 0;
                    this.chartableLineData[i].durations.forEach(duration => {
                        if(duration.hours > 0) {
                            noneZeroDurationCount++;
                        }
                    });
                    if(noneZeroDurationCount == 0) {
                        this.chartableLineData.splice(i, 1);
                        i--;
                    }
                }
            },
            renderChart() {
                let svg = d3
                    .select("svg")
                    .attr("width", 1230)
                    .attr("height", 256);

                this.chart = svg
                    .append("g")
                    .attr("transform", "translate(25 25)");

                let yScale = d3
                    .scaleLinear()
                    .range([206, 0])
                    .domain([0, this.maximumHours]);

                this.chart
                    .append("g")
                    .call(d3.axisLeft(yScale).ticks([this.maximumHours]));

                console.dir(this.chartableDateRange);
                console.dir(this.chartableDateRange[0].day.format("DD MMM YYYY"));
                console.dir(this.chartableDateRange[this.chartableDateRange.length - 1].day.format("DD MMM YYYY"));

                let scaleStart = this.chartableDateRange[0].day;
                let scaleEnd = this.chartableDateRange[this.chartableDateRange.length - 1].day;

                let xScale = d3
                    .scaleBand()
                    .range([0, 1230])
                    .domain(this.chartableDateRange.map(entry => entry.day.format("DD MMM YYYY")))
                    .padding(1);

                this.chart
                    .append("g")
                    .attr("transform", "translate(0 206)")
                    .call(d3.axisBottom(xScale));

                this.chartableLineData.forEach(chartLine => {
                    let line = d3.line()
                        .x(duration => xScale(duration.day))
                        .y(duration => yScale(duration.hours));
                    this.chart
                        .append("path")
                        .datum(chartLine.durations)
                        .attr("fill", "none")
                        .attr("stroke", "#" + chartLine.color)
                        .attr("stroke-linejoin", "round")
                        .attr("stroke-linecap", "round")
                        .attr("stroke-width", 3)
                        .attr("d", line);
                });
            },
            updateChart() {
                this.initializeChartableDateRange();
                this.initializeChartableLineData()
                    .then(() => {
                        this.normalizeChartableLineData();
                        this.renderChart();
                    });
            }
        },
        mounted() {
            this.updateChart();
        },
        name: "TimesheetReportLineGraph",
        props: {
            filter: {
                required: true,
                type: Object
            },
            timesheetEntries: {
                required: true,
                type: Array
            }
        },
        watch: {
            timesheetEntries: {
                deep: true,
                handler(newVal) {
                    if(this.chart !== null) {
                        this.chart = null;
                        d3.select("svg").selectAll("*").remove();
                    }
                    this.updateChart();
                }
            }
        }
    }
</script>

<style scoped>

</style>
