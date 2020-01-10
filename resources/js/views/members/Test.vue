<template>
    <div class="bg-indigo-300">
        <h1>Test</h1>
        <test-component1 v-model="someObject" />
        <p>The value in Test: {{ displaySomeObject }}</p>
    </div>
</template>

<script>
    import TestComponent1 from "../../components/members/TestComponent1";

    export default {
        components: {
            TestComponent1
        },
        computed: {
            displaySomeObject() {
                return JSON.stringify(this.someObject);
            }
        },
        data() {
            return {
                someObject: {
                    someNestedObect: {
                        someAttribute: ""
                    }
                }
            }
        },
        name: "Test"
    }
</script>

<style scoped>

</style>
