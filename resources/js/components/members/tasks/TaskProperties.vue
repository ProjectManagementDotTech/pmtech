<template>
    <tabbed-modal-dialog-box :ok-button-disabled="isOkButtonDisabled"
                             :tab-component-data="tasks" :tabs="tabs"
                             @cancel="onCancel" @ok="onOk">
        <template slot="header">Task properties</template>
    </tabbed-modal-dialog-box>
</template>

<script>

    /*
     * TODO Integrate VeeValidate
     */

    import AdvancedTaskPropertiesTab from
        "./propertiesTabs/AdvancedTaskPropertiesTab";
    import GeneralTaskPropertiesTab from
        "./propertiesTabs/GeneralTaskPropertiesTab";
    import TabbedModalDialogBox from "../general/TabbedModalDialogBox";
    import Vue from "vue";

    Vue.component("advanced-task-properties-tab", AdvancedTaskPropertiesTab);
    Vue.component("general-task-properties-tab", GeneralTaskPropertiesTab);

    export default {
        components: {
            TabbedModalDialogBox
        },
        data() {
            return {
                goldenCopy: [],
                tabs: [
                    {
                        component: "general-task-properties-tab",
                        default: true,
                        title: "General"
                    },
                    {
                        component: "advanced-task-properties-tab",
                        default: false,
                        title: "Advancded"
                    }
                ]
            };
        },
        methods: {
            isOkButtonDisabled() {
                return false;
            },
            onCancel() {
                this.$emit("reset-and-close", this.goldenCopy);
            },
            onOk() {
                let promises = [];
                for(let i = 0; i < this.tasks.length; i++) {
                    promises.push(
                        this.$axios.put("/api/v1/tasks/" + this.tasks[i].id,
                            this.tasks[i])
                    );
                }
                Promise.all(promises)
                    .then(() => {
                        this.$emit("close");
                    })
                    .catch(error => {
                        console.dir(error);
                        alert("An error occurred and not all tasks were " +
                            "updated. Please refresh your screen.");
                    });
            }
        },
        mounted() {
            this.goldenCopy = JSON.parse(JSON.stringify(this.tasks));
        },
        name: "TaskProperties",
        props: {
            tasks: {
                default: [],
                required: true,
                type: Array,
                validator: function(value) {
                    return value.length > 0;
                }
            }
        },
        watch: {
            tasks: {
                deep: true,
                handler(newVal, oldVal) {
                    this.goldenCopy = JSON.parse(JSON.stringify(newVal));
                }
            }
        }
    }
</script>

<style scoped>

</style>
