<template>
    <div class="absolute bg-yellow-200 border border-gray-400 bottom-0 right-0 p-4 rounded w-1/6 z-40">
        <p>We've released new features for project-management.tech.</p>
        <p>Please click the button below to reload the application.</p>
        <div class="mt-2 text-center w-full">
            <button class="btn btn-primary" @click="onClick">Reload</button>
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            onClick() {
                window.location.reload(true);
            }
        },
        name: "NewVersionLoader"
    }
</script>

<style scoped>

</style>
