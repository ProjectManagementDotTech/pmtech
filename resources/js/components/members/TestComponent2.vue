<template>
    <div class="bg-indigo-100">
        <h3>TestComponent2</h3>
        <p>Input something: <input class="border border-gray-300" type="text" v-model="value.someNestedObect.someAttribute" /></p>
        <p>The value in TestComponent2: {{ displayableValue }}</p>
    </div>
</template>

<script>
    export default {
        computed: {
            displayableValue() {
                return JSON.stringify(this.value);
            }
        },
        name: "TestComponent2",
        props: {
            value: {
                required: true
            }
        }
    }
</script>

<style scoped>

</style>
