<template>
    <header class="bg-gray-900 md:flex md:justify-between md:items-center md:px-4 md:py-3">
        <div class="flex items-center justify-between px-4 py-3 md:px-0 md:py-0">
            <div>
                <template v-if="currentUser != undefined">
                    <img class="h-8" src="/images/logo.png" alt="Project-Management.tech">
                </template>
                <template v-else>
                    <router-link to="/">
                        <img class="h-8" src="/images/logo.png" alt="Project-Management.tech">
                    </router-link>
                </template>
            </div>
            <div class="md:hidden">
                <button class="block text-gray-500 hover:text-white focus:text-white focus:outline-none"
                        type="button" @click="isOpen = !isOpen">
                    <i v-if="!isOpen" class="fas fa-bars text-xl"></i>
                    <i v-else class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        <div :class="isOpen ? 'block' : 'hidden'" class="md:block">
            <template v-if="currentUser != undefined">
                <div class="px-2 pt-2 md:flex md:pt-0">
                    <client-dropdown-nav-item class="hidden md:block" />
                    <project-dropdown-nav-item class="hidden md:block" />
                    <timesheet-dropdown-nav-item class="hidden md:block" />
                    <workspace-dropdown-nav-item class="hidden md:block" />
                    <person-dropdown-nav-item class="hidden md:block" />
                </div>
                <div class="pb-4 md:pb-0 border-t border-gray-800 mt-1 md:hidden">
                    <div class="px-2">
                        <div class="hover:bg-gray-800 block mt-1 rounded px-2 py-1 text-white font-semibold focus:outline-none focus:bg-gray-800 md:ml-2 md:mt-0 z-40">
                            Clients
                        </div>
                        <div class="mt-2 pl-2">
                            <a class="hover:text-white mt-2 px-2 py-1 block text-gray-400" @click="onClickAddClient">Add</a>
                            <router-link class="hover:text-white px-2 py-1 block text-gray-400" :to="'/workspaces/' + workspaceId + '/clients'" @click.native="isOpen = false">Overview</router-link>
                        </div>
                    </div>
                </div>
                <div class="pb-4 md:pb-0 border-t border-gray-800 mt-1 md:hidden">
                    <div class="px-2">
                        <div class="hover:bg-gray-800 block mt-1 rounded px-2 py-1 text-white font-semibold focus:outline-none focus:bg-gray-800 md:ml-2 md:mt-0 z-40">
                            Projects
                        </div>
                        <div class="mt-2 pl-2">
                            <a class="hover:text-white mt-2 px-2 py-1 block text-gray-400" @click="onClickAddProject">Add</a>
                            <router-link class="hover:text-white px-2 py-1 block text-gray-400" :to="'/workspaces/' + workspaceId" @click.native="isOpen = false">Overview</router-link>
                            <div v-if="$store.getters['projects/byId'](projectId)" class="border-t border-gray-700">
                                <div class="cursor-default px-2 py-1 text-gray-700 text-xs uppercase">
                                    {{ $store.getters["projects/byId"](projectId).name }}
                                </div>
                                <router-link class="block px-4 py-2 hover:text-white focus:text-white focus:outline-none text-gray-400" :to="'/workspaces/' + workspaceId + '/projects/' + projectId + '/settings'" @click.native="isOpen = false">Settings</router-link>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-4 md:pb-0 border-t border-gray-800 mt-1 md:hidden">
                    <div class="px-2">
                        <div class="hover:bg-gray-800 block mt-1 rounded px-2 py-1 text-white font-semibold focus:outline-none focus:bg-gray-800 md:ml-2 md:mt-0 z-40">
                            Timesheet
                        </div>
                        <div class="mt-2 pl-2">
                            <router-link class="hover:text-white px-2 py-1 block text-gray-400" :to="'/workspaces/' + workspaceId + '/timesheet'" @click.native="isOpen = false">Entry</router-link>
                            <router-link class="hover:text-white mt-2 px-2 py-1 block text-gray-400" :to="'/workspaces/' + workspaceId + '/timesheet/report'" @click.native="isOpen = false">Report</router-link>
                        </div>
                    </div>
                </div>
                <div class="pb-4 md:pb-0 border-t border-gray-800 mt-1 md:hidden">
                    <div class="px-2">
                        <div class="hover:bg-gray-800 block mt-1 rounded px-2 py-1 text-white font-semibold focus:outline-none focus:bg-gray-800 md:ml-2 md:mt-0 z-40">
                            Workspaces
                        </div>
                        <div class="mt-2 pl-2">
                            <a class="hover:text-white mt-2 px-2 py-1 block text-gray-400 cursor-pointer" @click="onClickAddWorkspace">Add</a>
                            <div class="border-t border-gray-700">
                                <div class="cursor-default px-2 py-1 text-gray-700 text-xs uppercase">
                                    {{ $store.getters["workspaces/byId"](workspaceId).name }}
                                </div>
                                <router-link class="block px-4 py-2 hover:text-white focus:text-white focus:outline-none text-gray-400" :to="'/workspaces/' + $route.params.workspaceId + '/settings'" @click.native="isOpen = false">Settings</router-link>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-4 md:pb-0 border-t border-gray-800 mt-1 md:hidden">
                    <div class="px-2">
                        <div class="hover:bg-gray-800 block mt-1 rounded px-2 py-1 text-white font-semibold focus:outline-none focus:bg-gray-800 md:ml-2 md:mt-0 z-40">
                            {{ currentUser.name }}
                        </div>
                        <div class="border-t border-gray-700">
                            <div class="cursor-default px-2 py-1 text-gray-700 text-xs uppercase">
                                Switch workspace
                            </div>
                            <router-link class="block px-4 py-2 hover:text-white focus:text-white focus:outline-none text-gray-400"
                                         v-for="workspace in $store.getters['workspaces/all']"
                                         :key="workspace.id"
                                         :to="'/workspaces/' + workspace.id" @click.native="isOpen = false">
                                {{ workspace.name }}
                            </router-link>
                        </div>
                        <div class="border-t border-gray-700">
                            <div class="cursor-default px-2 py-1 text-gray-700 text-xs uppercase">
                                Settings
                            </div>
                        </div>
                        <div class="mt-2 pl-2">
                            <router-link class="hover:text-white px-2 py-1 block text-gray-400" :to="'/workspaces/' + workspaceId + '/users/' + currentUser.id + '/settings/billing'" @click.native="isOpen = false">Billing</router-link>
                        </div>
                        <div class="mt-2 pl-4">
                            <router-link class="hover:text-white px-2 py-1 block text-gray-400"
                                         :to="'/workspaces/' + workspaceId + '/users/' + currentUser.id + '/settings/billing/invoices'"
                                         @click.native="isOpen = false">
                                Invoices
                            </router-link>
                            <router-link class="hover:text-white px-2 py-1 block text-gray-400"
                                         :to="'/workspaces/' + workspaceId + '/users/' + currentUser.id + '/settings/billing/payment'"
                                         @click.native="isOpen = false">
                                Payment
                            </router-link>
                            <router-link class="hover:text-white px-2 py-1 block text-gray-400"
                                         :to="'/workspaces/' + workspaceId + '/users/' + currentUser.id + '/settings/billing/payment-methods'"
                                         @click.native="isOpen = false">
                                Payment methods
                            </router-link>
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
    import ClientDropdownNavItem from "./navgiation/ClientDropdownNavItem";
    import PersonDropdownNavItem from "./navgiation/PersonDropdownNavItem";
    import ProjectDropdownNavItem from "./navgiation/ProjectDropdownNavItem";
    import TimesheetDropdownNavItem
        from "./navgiation/TimesheetDropdownNavItem";
    import WorkspaceDropdownNavItem
        from "./navgiation/WorkspaceDropdownNavItem";

    export default {
        components: {
            ClientDropdownNavItem,
            ProjectDropdownNavItem,
            PersonDropdownNavItem,
            TimesheetDropdownNavItem,
            WorkspaceDropdownNavItem
        },
        computed: {
            ...mapGetters(["currentUser"]),
        },
        created() {
            this.workspaceId = this.$route.params.workspaceId;
            this.projectId = this.$route.params.projectId;
            this.clientId = this.$route.params.clientId;
        },
        data() {
            return {
                clientId: "",
                isOpen: false,
                projectId: "",
                workspaceId: ""
            };
        },
        methods: {
            onClickAddClient() {
                this.isOpen = false;
                this.$eventBus.$emit("add-client");
            },
            onClickAddProject() {
                this.isOpen = false;
                this.$eventBus.$emit("add-project");
            },
            onClickAddWorkspace() {
                this.isOpen = false;
                this.$eventBus.$emit("add-workspace");
            }
        },
        name: "Navigation",
        watch: {
            $route(to, from) {
                if(to.params.clientId != from.params.clientId) {
                    this.clientId = to.params.clientId;
                }
                if(to.params.projectId != from.params.projectId) {
                    this.projectId = to.params.projectId;
                }
                if(to.params.workspaceId != from.params.workspaceId) {
                    this.workspaceId = to.params.workspaceId;
                }
            }
        }
    }
</script>

<style scoped>
</style>
