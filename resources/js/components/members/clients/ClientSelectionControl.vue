<template>
    <combo-control :entries="$store.getters['clients/all']" :value="value"
                   @blur="onBlur" @input="onInput" />
</template>

<script>
    import ComboControl from "../general/ComboControl";

    export default {
        components: {
            ComboControl
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
