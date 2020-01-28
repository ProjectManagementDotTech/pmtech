<template>
    <footer class="w-full p-4 flex flex-wrap text-center border-t">
        <div class="w-full sm:pb-2 text-xs md:text-sm lg:text-base sm:border-b sm:border-gray-100">
            Version 2020.1 - See the Release Notes <a href="">here.</a>
        </div>
        <div class="w-full sm:w-1/2 sm:py-2 text-xs md:text-sm lg:text-base">
            &copy; 2017 - {{ $moment().format("YYYY") }} by
            <a href="https://twitter.com/PHPGuus" target="blank">Guus Leeuw jr.
            </a> All rights reserved.
        </div>
        <div class="w-full sm:w-1/2 sm:py-2 text-xs md:text-sm lg:text-base">
            Operated by <a href="https://www.itpassion.com" target="blank">
            ITPassion Ltd.</a>
        </div>
        <div class="w-full sm:pt-2 text-xs md:text-sm lg:text-base sm:border-t sm:border-gray-100">
            Implemented using <a href="https://laravel.com" target="blank">
            Laravel</a>, <a href="https://vuejs.org" target="blank">Vue.js</a>
            and <a href="https://tailwindcss.com" target="blank">tailwindcss</a>.
        </div>
    </footer>
</template>

<script>
    export default {
        name: "Footer"
    }
</script>

<style scoped>
    a {
        @apply text-indigo-400;
    }
</style>
