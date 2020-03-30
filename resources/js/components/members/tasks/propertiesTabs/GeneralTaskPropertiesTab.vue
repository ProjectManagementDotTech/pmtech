<template>
    <div>
        <div class="flex w-full">
            <template v-if="data.length == 1">
                <pmtech-input label="Task" name="name" rules="required"
                              v-model="data[0].name" />
            </template>
            <template v-else>
                <pmtech-input label="Task" name="name" rules="required" disabled="true"
                              v-model="emptyTaskName" />
            </template>
        </div>
    </div>
</template>

<script>
    import PmtechInput from "../../../shared/input/PmtechInput";

    export default {
        components: {
            PmtechInput
        },
        data() {
            return {
                emptyTaskName: ""
            };
        },
        name: "GeneralTaskPropertiesTab",
        props: {
            data: {
                required: true
            }
        }
    }
</script>

<style scoped>

</style>
