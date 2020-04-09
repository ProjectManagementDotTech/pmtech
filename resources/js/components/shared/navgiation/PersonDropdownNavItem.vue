<template>
    <div class="relative">
        <button class="relative hover:bg-gray-800 block mt-1 rounded px-2 py-1 text-white font-semibold focus:outline-none focus:bg-gray-800 sm:ml-2 sm:mt-0"
                :class="{ 'z-40': isOpen }" @click.stop="isOpen = !isOpen">
            {{ currentUser.name }}<i class="ml-2 fas" :class="chevronClass"></i>
        </button>
        <button v-if="isOpen" class="fixed inset-0 h-full w-full bg-transparent z-30 cursor-default" tabindex="-1" @click="isOpen = false"></button>
        <div v-if="isOpen" class="absolute bg-white overflow-hidden pb-2 right-0 rounded-lg w-48 shadow-xl z-40">
            <div class="bg-gray-200 cursor-default px-2 py-1 text-xs uppercase">
                Switch workspace
            </div>
            <router-link class="block px-4 py-2 hover:bg-gold-100 hover:text-gray-800 focus:bg-gold-100 focus:outline-none"
                         v-for="workspace in $store.getters['workspaces/all']"
                         :class="workspace.id == workspaceId ? 'bg-indigo-400 text-white' : ''"
                         :key="workspace.id" :to="'/workspaces/' + workspace.id"
                         @click.native="isOpen = false">
                {{ workspace.name }}
            </router-link>
            <div class="border-t border-gray-200 h-px my-1" />
            <div class="bg-gray-200 cursor-default px-2 py-1 text-xs uppercase">
                {{ currentUser.name }}
            </div>
            <sub-menu title="Settings" :children="settingsSubMenuChildren"
                      @close="onClose" />
            <router-link class="block px-4 py-2 hover:bg-gold-100 focus:bg-gold-100 focus:outline-none" to="/logout" @click.native="isOpen = false">Logout</router-link>
        </div>
    </div>
</template>

<script>
    import { mapGetters } from "vuex";
    import SubMenu from "./SubMenu";

    export default {
        beforeDestroy() {
            window.removeEventListener("resize", this.onResize);
            document.removeEventListener("keydown", this.onKeyDown);
        },
        components: {
            SubMenu
        },
        computed: {
            ...mapGetters(["currentUser"])
        },
        created() {
            window.addEventListener("resize", this.onResize);
            document.addEventListener("keydown", this.onKeyDown);
            this.workspaceId = this.$route.params.workspaceId;
            this.updateSubMenuChildren();
        },
        data() {
            return {
                chevronClass: "fa-chevron-right",
                isOpen: false,
                settingsSubMenuChildren: [],
                workspaceId: "",
            };
        },
        methods: {
            onClose() {
                console.log("PersonDropdownNavItem::onClose");
                this.isOpen = false;
            },
            onKeyDown(e) {
                if(e.key === "Esc" || e.key === "Escape") {
                    this.isOpen = false;
                }
            },
            onResize() {
                if(window.innerWidth >= 640) {
                    if(this.isOpen) {
                        this.chevronClass = "fa-chevron-up";
                    } else {
                        this.chevronClass = "fa-chevron-down";
                    }
                } else {
                    if(this.isOpen) {
                        this.chevronClass = "fa-chevron-left";
                    } else {
                        this.chevronClass = "fa-chevron-right";
                    }
                }
            },
            updateSubMenuChildren() {
                this.settingsSubMenuChildren = [
                    {
                        children: [
                            {
                                title: "Invoices",
                                to: "/workspaces/" +
                                    this.workspaceId + "/users/" +
                                    this.$store.getters["currentUser"].id +
                                    "/settings/billing/invoices",
                                type: "link"
                            },
                            {
                                title: "Payment",
                                to: "/workspaces/" +
                                    this.workspaceId + "/users/" +
                                    this.$store.getters["currentUser"].id +
                                    "/settings/billing/payment",
                                type: "link"
                            },
                            {
                                title: "Payment methods",
                                to: "/workspaces/" +
                                    this.workspaceId + "/users/" +
                                    this.$store.getters["currentUser"].id +
                                    "/settings/billing/payment-methods",
                                type: "link"
                            }
                        ],
                        title: "Billing",
                        type: "menu"
                    }
                ];
            }
        },
        mounted() {
            this.onResize();
        },
        name: "PersonDropdownNavItem",
        watch: {
            $route(to, from) {
                if(to.params.workspaceId != from.params.workspaceId) {
                    this.workspaceId = to.params.workspaceId;
                    this.updateSubMenuChildren();
                }
            },
            isOpen() {
                this.onResize();
            }
        }
    }
</script>

<style scoped>
</style>
