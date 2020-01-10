<template>
    <div class="bg-indigo-200">
        <h2>TestComponent1</h2>
        <p><test-component2 v-model="value" /></p>
        <p>The value in TestComponent1: {{ displayableValue }}</p>
    </div>
</template>

<script>
    import TestComponent2 from "./TestComponent2";

    export default {
        components: {
            TestComponent2
        },
        computed: {
            displayableValue() {
                return JSON.stringify(this.value);
            }
        },
        name: "TestComponent1",
        props: {
            value: {
                required: false,
                type: Object
            }
        }
    }
</script>

<style scoped>

</style>
