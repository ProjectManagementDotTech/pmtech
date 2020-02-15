<template>
    <div class="w-2/3 mx-auto my-4 overflow-hidden rounded-lg shadow-lg"
         :class="borderClass">
        <header class="px-4 py-2" :class="headerClass">
            <slot name="header"></slot>
        </header>
        <main class="px-4 py-2">
            <slot></slot>
        </main>
        <footer class="border-t border-gray-100 mt-3 px-4 py-2"
                v-if="hasFooter">
            <slot name="footer"></slot>
        </footer>
    </div>
</template>

<script>
    export default {
        computed: {
            borderClass() {
                if(this.type == "danger") {
                    return "border-2 border-red-500";
                } else {
                    return "border";
                }
            },
            headerClass() {
                if(this.type == "danger") {
                    return "bg-red-300 border-b border-red-500 font-semibold";
                } else {
                    return "border-b";
                }
            }
        },
        name: "Panel",
        props: {
            hasFooter: {
                default: true,
                required: false,
                type: Boolean
            },
            type: {
                default: "",
                required: false,
                type: String
            }
        }
    }
</script>

<style scoped>

</style>
