<template>
    <article class="border border-red-300 overflow-hidden rounded">
        <main class="w-full flex flex-wrap items-center p-2">
            <div class="font-bold text-center text-red-500 w-full sm:w-1/4 lg:w-1/6">
                Archive
            </div>
            <div class="w-full sm:w-1/2 lg:w-2/3">
                When you archive a workspace, it and all attached projects become
                unavailable. This action can be reversed, because the data is not
                deleted.
            </div>
            <div class="text-right w-full sm:w-1/4 lg:w-1/6">
                <button class="bg-red-500 hover:bg-red-700 border-2 border-red-700 hover:border-red-900 font-weight-bold p-2 rounded text-gray-200"
                        @click="onClickArchive">
                    Archive
                </button>
            </div>
        </main>
    </article>
</template>

<script>
    export default {
        methods: {
            onClickArchive() {
                this.$axios.post("/api/v1/workspaces/" +
                    this.$route.params.workspaceId + "/archive")
                    .then(response => {
                        this.$store.dispatch("workspaces/fetchAll")
                            .then(() => {
                                let newLocation = response.headers.location;
                                let toRoute = newLocation.substr(
                                    window.location.protocol.length +
                                    window.location.hostname.length + 9);
                                this.$router.push(toRoute);
                            });
                    })
                    .catch(error => {
                        console.dir(error);
                        debugger;
                        if(error.response.status == 422) {
                            /*
                             * Archive cannot be deleted?!!??!!
                             */
                        }
                    });
            }
        },
        name: "ArchiveWorkspace"
    }
</script>

<style scoped>

</style>
