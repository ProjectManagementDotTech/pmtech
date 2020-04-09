<template>
    <div class="relative">
        <button class="block hover:bg-gold-100 focus:bg-gold-100 focus:outline-none pr-4 py-2 text-left w-full"
                :class="'pl-' + (4 + (level * 2))"
                @click.stop="isOpen = !isOpen">
            {{ title }}
            <i v-if="!isOpen" class="ml-2 fas fa-chevron-down" />
            <i v-else class="ml-2 fas fa-chevron-up" />
        </button>
        <div v-if="isOpen">
            <template v-for="child in children">
                <router-link class="block focus:bg-gold-100 hover:bg-gold-100 focus:outline-none pr-4 py-2"
                             v-if="child.type == 'link'"
                             :class="'pl-' + (4 + ((level + 1) * 2))"
                             :to="child.to" @click.native="onClickLink">
                    {{ child.title }}
                </router-link>
                <sub-menu v-if="child.type == 'menu'"
                          :children="child.children" :level="level + 1"
                          :title="child.title" @close="onClose"/>
            </template>
        </div>
    </div>
</template>

<script>
    export default {
        create() {
            console.log("Submenu '" + this.title + "' level: " + this.level);
        },
        data() {
            return {
                chevronClass: "fa-chevron-right",
                isOpen: false
            };
        },
        methods: {
            onClickLink() {
                console.log("SubMenu::onClickLink");
                this.isOpen = false;
                this.$emit('close');
            },
            onClose() {
                console.log("SubMenu::onClose");
                this.isOpen = false;
                this.$emit('close');
            }
        },
        name: "SubMenu",
        props: {
            children: {
                required: true,
                type: Array
            },
            level: {
                default: 0,
                required: false,
                type: Number
            },
            title: {
                required: true,
                type: String
            }
        }
    }
</script>

<style scoped>

</style>
