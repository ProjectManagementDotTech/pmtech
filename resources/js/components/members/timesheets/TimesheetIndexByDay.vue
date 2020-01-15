<template>
    <div class="border-b border-gray-200">
        <div class="items-center flex flex-wrap p-2 w-full">
            <div class="w-6/12 md:w-9/12">
                <h4>{{ date }}</h4>
            </div>
            <div class="w-5/12 md:w-2/12">
                <h5>Total time: {{ timesheetEntries.duration }}</h5>
            </div>
            <div class="w-1/12">
                <button class="float-right focus:outline-none" @click="toggleExpanded">
                        <i v-if="expanded" class="fas fa-chevron-up" key="expanded"></i>
                        <i v-else class="fas fa-chevron-down" key="collapsed"></i>
                </button>
            </div>
        </div>
        <transition-group name="fade">
            <timesheet-entry-editor v-if="expanded"
                                    class="bg-white even:bg-gray-100 hover:bg-gold-100 p-1"
                                    v-for="entry in timesheetEntries.entries"
                                    :key="entry.id" :timesheet-entry="entry"
                                    @update-timesheet="$emit('update-timesheet')" />
        </transition-group>
    </div>
</template>

<script>
    import TimesheetEntryEditor from "./TimesheetEntryEditor";

    export default {
        components: {
            TimesheetEntryEditor
        },
        computed: {
            date() {
                return this.$moment(this.timesheetEntries.date)
                    .format("DD MMM YYYY");
            }
        },
        data() {
            return {
                expanded: false
            };
        },
        methods: {
            toggleExpanded(e) {
                if(this.expanded) {
                    e.target.classList.remove("expanded");
                    e.target.classList.add("collapsed");
                } else {
                    e.target.classList.remove("collapsed");
                    e.target.classList.add("expanded");
                }
                this.expanded = !this.expanded;
            }
        },
        name: "TimesheetIndexByDay",
        props: {
            timesheetEntries: {
                required: true,
                type: Object
            }
        }
    }
</script>

<style scoped>
    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s ease;
    }
    .fade-enter, .fade-leave-to {
        opacity: 0;
    }
</style>
