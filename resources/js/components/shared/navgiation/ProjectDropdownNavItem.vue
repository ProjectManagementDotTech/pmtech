<template>
    <div class="relative">
        <button class="relative hover:bg-gray-800 block mt-1 rounded px-2 py-1 text-white font-semibold focus:outline-none focus:bg-gray-800 sm:ml-2 sm:mt-0"
                :class="{ 'z-40': isOpen }" @click.stop="isOpen = !isOpen">
            Projects<i class="ml-2 fas" :class="chevronClass"></i>
        </button>
        <button v-if="isOpen" class="fixed inset-0 h-full w-full bg-transparent z-30 cursor-default" tabindex="-1" @click="isOpen = false"></button>
        <div v-if="isOpen" class="absolute right-0 bg-white rounded-lg py-2 w-48 shadow-xl z-40">
            <a class="block px-4 py-2 hover:bg-gold-100 focus:bg-gold-100 focus:outline-none cursor-pointer" @click="onClickAddProject">Add</a>
            <router-link class="block px-4 py-2 hover:bg-gold-100 focus:bg-gold-100 focus:outline-none" :to="'/workspaces/' + $route.params.workspaceId" @click.native="isOpen = false">Overview</router-link>
            <template v-if="currentProject">
                <div class="bg-gray-200 cursor-default px-2 py-1 text-xs uppercase">
                    {{ $store.getters["projects/byId"]($route.params.projectId).name }}
                </div>
                <router-link class="block px-4 py-2 hover:bg-gold-100 focus:bg-gold-100 focus:outline-none" :to="'/workspaces/' + $route.params.workspaceId + '/projects/' + $route.params.projectId + '/settings'" @click.native="isOpen = false">Settings</router-link>
            </template>
        </div>
    </div>
</template>

<script>
    export default {
        beforeDestroy() {
            window.removeEventListener("resize", this.onResize);
            document.removeEventListener("keydown", this.onKeyDown);
        },
        computed: {
            currentProject() {
                return this.$route.params.projectId !== undefined;
            }
        },
        created() {
            window.addEventListener("resize", this.onResize);
            document.addEventListener("keydown", this.onKeyDown);
        },
        data() {
            return {
                chevronClass: "fa-chevron-right",
                isOpen: false
            }
        },
        methods: {
            onClickAddProject() {
                this.isOpen = false;
                this.$eventBus.$emit("add-project");
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
            }
        },
        mounted() {
            this.onResize();
        },
        name: "ProjectDropdownNavItem",
        watch: {
            isOpen() {
                this.onResize();
            }
        }
    }
</script>

<style scoped>
</style>
