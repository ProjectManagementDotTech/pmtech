<template>
    <header class="bg-gray-900 sm:flex sm:justify-between sm:items-center sm:px-4 sm:py-3">
        <div class="flex items-center justify-between px-4 py-3 sm:px-0 sm:py-0">
            <div>
                <template v-if="authenticated">
                    <img class="h-8" src="/images/logo.png" alt="Project-Management.tech">
                </template>
                <template v-else>
                    <router-link to="/">
                        <img class="h-8" src="/images/logo.png" alt="Project-Management.tech">
                    </router-link>
                </template>
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
                    <project-dropdown-nav-item class="hidden sm:block" />
                    <timesheet-dropdown-nav-item class="hidden sm:block" />
                    <person-dropdown-nav-item class="hidden sm:block" />
                </div>
                <div class="pb-4 sm:pb-0 border-t border-gray-800 mt-1 sm:hidden">
                    <div class="px-2">
                        <div class="hover:bg-gray-800 block mt-1 rounded px-2 py-1 text-white font-semibold focus:outline-none focus:bg-gray-800 sm:ml-2 sm:mt-0 z-40">
                            Projects
                        </div>
                        <div class="mt-2 pl-2">
                            <router-link class="hover:text-white px-2 py-1 block text-gray-400" :to="'/workspaces/' + $route.params.workspaceId" @click.native="isOpen = false">Overview</router-link>
                            <a class="hover:text-white mt-2 px-2 py-1 block text-gray-400" @click="onClickAddProject">Add</a>
                        </div>
                    </div>
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
                <div class="pb-4 sm:pb-0 border-t border-gray-800 mt-1 sm:hidden">
                    <div class="px-2">
                        <div class="hover:bg-gray-800 block mt-1 rounded px-2 py-1 text-white font-semibold focus:outline-none focus:bg-gray-800 sm:ml-2 sm:mt-0 z-40">
                            {{ currentUser.name }}
                        </div>
                        <div class="mt-2 pl-2">
                            <router-link class="hover:text-white px-2 py-1 block text-gray-400" to="/logout" @click.native="isOpen = false">Logout</router-link>
                        </div>
                    </div>
                </div>
            </template>
            <template v-else>
                <div class="px-2 pt-2 sm:flex sm:pt-0">
                    <router-link class="hover:bg-gray-800 block sm:mt-1 rounded px-2 py-1 text-white font-semibold sm:ml-2 focus:outline-none" exact to="/how-it-works">
                        How it works
                    </router-link>
                    <router-link class="hover:bg-gray-800 block mt-1 rounded px-2 py-1 text-white font-semibold sm:ml-2 focus:outline-none" exact to="/pricing-and-plans">
                        Pricing and plans
                    </router-link>
                    <router-link class="hover:bg-gray-800 block mt-1 rounded px-2 py-1 text-white font-semibold sm:ml-2 focus:outline-none" exact to="/login">
                        Login
                    </router-link>
                    <router-link class="hover:bg-gray-800 block mt-1 rounded px-2 py-1 text-white font-semibold sm:ml-2 focus:outline-none" exact to="/register">
                        Register
                    </router-link>
                </div>
            </template>
        </div>
    </header>
</template>

<script>
    import { mapGetters } from "vuex";
    import PersonDropdownNavItem from "./navgiation/PersonDropdownNavItem";
    import TimesheetDropdownNavItem
        from "./navgiation/TimesheetDropdownNavItem";
    import ProjectDropdownNavItem from "./navgiation/ProjectDropdownNavItem";

    export default {
        components: {
            ProjectDropdownNavItem,
            PersonDropdownNavItem,
            TimesheetDropdownNavItem
        },
        computed: {
            ...mapGetters(["authenticated", "currentUser"])
        },
        data() {
            return {
                isOpen: false
            }
        },
        methods: {
            onClickAddProject() {
                this.isOpen = false;
                this.$eventBus.$emit("add-project");
            }
        },
        name: "Navigation"
    }
</script>

<style scoped>
    .router-link-exact-active {
        @apply bg-gray-800;
    }
</style>
