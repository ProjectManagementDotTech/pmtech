<template>
    <div class="mb-2 pr-2 last:pr-0 w-full md:w-1/2 xl:w-1/3">
        <div class="border border-gray-200 hover:border-indigo-400 overflow-hidden hover:shadow-md rounded-md">
            <div class="bg-gray-100 border-b border-gray-200 px-4 py-2">
                <span class="font-semibold">{{ client.name }}</span>
            </div>
            <div class="px-4 py-2">
                <div class="flex w-flex">
                    <div class="w-2/3">
                        Revenue year to date
                    </div>
                    <div class="w-1/6">
                        :
                    </div>
                    <div class="text-right w-1/6">
                        &euro;0.00
                    </div>
                </div>
                <div class="flex w-flex">
                    <div class="w-2/3">
                        Estimated revenue at year end
                    </div>
                    <div class="w-1/6">
                        :
                    </div>
                    <div class="text-right w-1/6">
                        &euro;0.00
                    </div>
                </div>
            </div>
            <div class="bg-gray-100 border-t border-gray-200 px-4 py-5 text-right">
                <router-link class="btn btn-primary" :to="'/workspaces/' + $route.params.workspaceId + '/clients/' + client.id">
                    More...
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ClientOverviewCard",
        props: {
            client: {
                default: { name: "n/a" },
                required: true,
                type: Object
            }
        }
    }
</script>

<style scoped>

</style>
