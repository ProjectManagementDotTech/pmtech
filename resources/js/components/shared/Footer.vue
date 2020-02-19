<template>
    <footer class="w-full p-4 flex flex-wrap text-center border-t">
        <div class="w-full sm:pb-2 text-xs md:text-sm lg:text-base sm:border-b sm:border-gray-100">
            Version 2020.2 {{ envMoniker }}- See the Release Notes
            <a href="https://github.com/ProjectManagementDotTech/pmtech/releases" target="_blank">here.</a>
        </div>
        <div class="w-full sm:w-1/2 xl:w-1/3 sm:py-2 text-xs md:text-sm lg:text-base">
            &copy; {{ $moment().format("YYYY") }} by
            <a href="https://twitter.com/PHPGuus" target="_blank">Guus Leeuw jr.
            </a> All rights reserved.
        </div>
        <div class="hidden xl:inline-block w-full xl:w-1/3 sm:py-2 text-xs md:text-sm lg:text-base">
            Implemented using <a href="https://laravel.com" target="_blank">
            Laravel</a>, <a href="https://vuejs.org" target="_blank">Vue.js</a>
            and <a href="https://tailwindcss.com" target="_blank">tailwindcss</a>.
        </div>
        <div class="w-full sm:w-1/2 xl:w-1/3 sm:py-2 text-xs md:text-sm lg:text-base">
            Operated by <a href="https://www.itpassion.com" target="_blank">
            ITPassion Ltd.</a>
        </div>
        <div class="w-full sm:pt-2 text-xs md:text-sm lg:text-base sm:border-t sm:border-gray-100 xl:hidden">
            Implemented using <a href="https://laravel.com" target="_blank">
            Laravel</a>, <a href="https://vuejs.org" target="_blank">Vue.js</a>
            and <a href="https://tailwindcss.com" target="_blank">tailwindcss</a>.
        </div>
    </footer>
</template>

<script>
    export default {
        computed: {
            envMoniker() {
                console.log(process.env.NODE_ENV);
                if(process.env.NODE_ENV == "development") {
                    return "DEV ";
                } else if(process.env.NODE_ENV == "preprod") {
                    return "PREPROD ";
                } else {
                    return "";
                }
            }
        },
        name: "Footer"
    }
</script>

<style scoped>
    a {
        @apply text-indigo-400;
    }
</style>
