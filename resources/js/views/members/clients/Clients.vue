<template>
    <div v-if="$route.params.clientId != undefined">
        <router-view />
    </div>
    <div class="flex flex-wrap p-4 w-full" v-else>
        <client-overview-card v-for="client in clients"
                              :client="client" :key="client.id" />
    </div>
</template>

<script>
    import ClientOverviewCard from
        "../../../components/members/clients/ClientOverviewCard";
    import Vue from "vue";

    export default {
        beforeDestroy() {
            this.$eventBus.$off("update-client-index", this.loadClients);
        },
        components: {
            ClientOverviewCard
        },
        created() {
            this.$eventBus.$on("update-client-index", this.loadClients);
            this.loadClients();
        },
        data() {
            return {
                clients: []
            };
        },
        methods: {
            loadClients() {
                this.$axios.get("/api/v1/workspaces/" +
                    this.$route.params.workspaceId + "/clients")
                    .then(response => {
                        this.clients = response.data;
                    })
                    .catch(error => {
                        console.log("Error fetching clients");
                        console.dir(error);
                    });
            }
        },
        name: "Clients",
    }
</script>

<style scoped>

</style>
