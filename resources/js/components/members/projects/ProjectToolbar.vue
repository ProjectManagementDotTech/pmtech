<template>
    <div class="flex w-full">
        <div class="bg-gray-200 border-b border-gray-300 mb-2 px-4 py-1 shadow-md w-full">
            <button class="hover:bg-gray-300 border border-gray-400 hover:border-gray-500 focus:outline-none p-2 rounded"
                    :disabled="selectedTaskCount == 0"
                    @click="$emit('properties')">
                <i class="fas fa-info-circle fa-2x text-blue-500"></i><br />
                Properties
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ProjectToolbar",
        props: {
            selectedTaskCount: {
                default: 0,
                required: false,
                type: Number
            }
        }
    }
</script>

<style scoped>

</style>
