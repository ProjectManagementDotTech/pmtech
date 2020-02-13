<template>
    <input class="p-1" :id="uuid" :value="thisValue" @blur="onBlur"
           @keyup.enter="onBlur" />
</template>

<script>
    export default {
        created() {
            this.uuid = this.$utils.uuid();
        },
        data() {
            return {
                uuid: "",
                thisValue: this.value
            };
        },
        methods: {
            onBlur(event) {
                this.$emit('input', event.target.value);
            },
            resetInputControl() {
                document.getElementById(this.uuid).value = "";
            }
        },
        name: "GridTextEditor",
        props: ["value"],
        watch: {
            value(newValue) {
                this.thisValue = newValue;
            }
        }
    }
</script>

<style scoped>

</style>
