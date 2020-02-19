<template>
    <div>
        <div>
            You are not authorized to access that resource.
        </div>
        <div>
            Click <button @click="onClick">here</button> to go back.
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            onClick() {
                window.router.go(-2);
            }
        },
        name: "Error403"
    }
</script>

<style scoped>

</style>
