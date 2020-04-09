<template>
    <div>
        <div class="flex w-full">
            <template v-if="isWorkDrivenAttributeEditable">
                <input class="mr-2" id="work_driven" name="work_driven"
                       type="checkbox" v-model="data[0].work_driven"
                       @change="toggleWorkDriven">
                <label for="work_driven">Work driven</label>
            </template>
            <template v-else>
                <input class="mr-2" disabled id="work_driven" name="work_driven"
                       type="checkbox" v-model="data[0].work_driven"
                       @change="toggleWorkDriven">
                <label disabled for="work_driven">Work driven</label>
            </template>
        </div>
    </div>
</template>

<script>
    export default {
        computed: {
            isWorkDrivenAttributeEditable() {
                if(this.data.length === 1) {
                    return true;
                }

                if(this.data.length > 1) {
                    let allEqual = this.data[0].work_driven;
                    for(let i = 1; i < this.data.length; i++) {
                        if(this.data[i].work_driven !== allEqual) {
                            return false;
                        }
                    }

                    return true;
                } else {
                    return false;
                }
            }
        },
        methods: {
            toggleWorkDriven() {
                let workDriven = this.data[0].work_driven;
                for(let i = 1; i < this.data.length; i++) {
                    this.data[i].work_driven = workDriven;
                }
            }
        },
        name: "AdvancedTaskPropertiesTab",
        props: {
            data: {
                required: true
            }
        }
    }
</script>

<style scoped>

</style>
