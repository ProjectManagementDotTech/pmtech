<template>
    <div class="bg-gray-500 border-b border-gray-700 border-l inline-block p-1 text-right w-6">
        <button class="cursor-default focus:outline-none text-right w-full"
                @click="onClick">
            <slot>A</slot>
        </button>
    </div>
</template>

<script>
    export default {
        methods: {
            onClick(e) {
                this.$emit("select", { data: this.data, event: e });
            }
        },
        name: "RowHeader",
        props: {
            data: {
                default: undefined,
                required: false,
                type: Object
            }
        }
    }
</script>

<style scoped>

</style>
