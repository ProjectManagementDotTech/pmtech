<template>
    <filtering-dropdown-control :value="value"
                                :entries="$store.getters['clients/all']"
                                @blur="onBlur" @input="onInput" />
</template>

<script>
    import FilteringDropdownControl from "../general/FilteringDropdownControl";

    export default {
        components: {
            FilteringDropdownControl
        },
        methods: {
            onBlur() {
                this.$emit("input", this.value);
            },
            onInput(selectedClient) {
                this.$emit("input", selectedClient);
            }
        },
        name: "ClientSelectionControl",
        props: {
            value: {
                required: true
            }
        }
    }
</script>

<style scoped>

</style>
