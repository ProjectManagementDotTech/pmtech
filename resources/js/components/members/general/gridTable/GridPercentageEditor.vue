<template>
    <input class="p-1" min="0" max="100" type="number" :class="selectedClass"
           :id="uuid" :value="thisValue" @blur="onBlur" @input="onBlur"
           @click="$emit('click')" @keyup.enter="onBlur" />
</template>

<script>
    export default {
        computed: {
            selectedClass() {
                if(this.$attrs.selected == true) {
                    return "bg-indigo-400 text-white";
                }
            }
        },
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
        name: "GridPercentageEditor",
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
