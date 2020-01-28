<template>
    <div class="w-2/3 mx-auto my-4 border rounded-lg shadow-lg px-4 py-2">
        <header>
            <slot name="header"></slot>
        </header>
        <main>
            <slot></slot>
        </main>
        <footer class="border-t border-gray-100 mt-3 pt-2">
            <slot name="footer"></slot>
        </footer>
    </div>
</template>

<script>
    export default {
        name: "Panel"
    }
</script>

<style scoped>

</style>
