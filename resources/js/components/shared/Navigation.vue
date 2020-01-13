<template>
    <header class="bg-gray-900 sm:flex sm:justify-between sm:items-center sm:px-4 sm:py-3">
        <div class="flex items-center justify-between px-4 py-3 sm:px-0 sm:py-0">
            <div>
                <img class="h-8" src="/images/logo.png" alt="Project-Management.tech">
            </div>
            <div class="sm:hidden">
                <button class="block text-gray-500 hover:text-white focus:text-white focus:outline-none"
                        type="button" @click="isOpen = !isOpen">
                    <i v-if="!isOpen" class="fas fa-bars text-xl"></i>
                    <i v-else class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        <div :class="isOpen ? 'block' : 'hidden'" class="sm:block">
            <template v-if="authenticated">
                <div class="px-2 pt-2 sm:flex sm:pt-0">
                    <router-link class="hover:bg-gray-800 block rounded px-2 py-1 text-white font-semibold sm:ml-2" exact :to="'/workspaces/' + $route.params.workspaceId">Projects</router-link>
                    <timesheet-dropdown-nav-item class="hidden sm:block" />
                </div>
                <div class="pb-4 sm:pb-0 border-t border-gray-800 mt-1 sm:hidden">
                    <div class="px-2">
                        <div class="hover:bg-gray-800 block mt-1 rounded px-2 py-1 text-white font-semibold focus:outline-none focus:bg-gray-800 sm:ml-2 sm:mt-0 z-40">
                            Timesheet
                        </div>
                        <div class="mt-2 pl-2">
                            <router-link class="hover:text-white px-2 py-1 block text-gray-400" :to="'/workspaces/' + $route.params.workspaceId + '/timesheet'" @click.native="isOpen = false">Entry</router-link>
                            <router-link class="hover:text-white mt-2 px-2 py-1 block text-gray-400" :to="'/workspaces/' + $route.params.workspaceId + '/timesheet/report'" @click.native="isOpen = false">Report</router-link>
                        </div>
                    </div>
                </div>
            </template>
            <template v-else>
                <router-link class="hover:bg-gray-800 block rounded px-2 py-1 text-white font-semibold sm:ml-2" exact to="/login">Login</router-link>
                <router-link class="hover:bg-gray-800 block mt-1 rounded px-2 py-1 text-white font-semibold sm:ml-2 sm:mt-0" exact to="/register">Register</router-link>
            </template>
        </div>
    </header>
</template>

<script>
    import { mapGetters } from "vuex";
    import TimesheetDropdownNavItem
        from "./Navgiation/TimesheetDropdownNavItem";

    export default {
        components: {
            TimesheetDropdownNavItem
        },
        computed: {
            ...mapGetters([ "authenticated" ])
        },
        data() {
            return {
                isOpen: false
            }
        },
        name: "Navigation"
    }
</script>

<style scoped>

</style>
