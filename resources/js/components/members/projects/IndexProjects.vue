<template>
    <div>
        <vuetable pagination-path="" ref="indexProjectsTable" :fields="fields"
                  :http-fetch="fetchProjectIndex"
                  @vuetable:pagination-data="onPaginationData">
            <template slot="color" slot-scope="props">
                <div :style="'background-color: #' + props.rowData.color + '; display: block; min-height: 16px;'" />
            </template>
            <template slot="name" slot-scope="props">
                <router-link :to="'/workspaces/' + $route.params.workspaceId + '/projects/' + props.rowData.id">
                    {{ props.rowData.name }}
                </router-link>
            </template>
        </vuetable>
        <vuetable-pagination ref="indexProjectsPagination"
                             @vuetable-pagination:change-page="onChangePage" />
    </div>
</template>

<script>
    import Vuetable from "vuetable-2/src/components/Vuetable";
    import VuetablePagination from
        "vuetable-2/src/components/VuetablePagination";

    export default {
        beforeDestroy() {
            this.$eventBus.$off("update-project-index",
                this.updateProjectIndex);
        },
        components: {
            Vuetable,
            VuetablePagination
        },
        created() {
            this.$eventBus.$on("update-project-index", this.updateProjectIndex);
        },
        data() {
            return {
                fields: [
                    {
                        name: "__slot:color",
                        title: "Color",
                    },
                    {
                        name: "__slot:name",
                        title: "Project name"
                    }
                ]
            }
        },
        methods: {
            fetchProjectIndex(apiUri, httpOptions) {
                return new Promise((resolve, reject) => {
                    this.$axios.get("/workspaces/" +
                        this.$route.params.workspaceId + "/projects",
                        httpOptions)
                    .then(response => {
                        resolve(response);
                    })
                    .catch(error => {
                        resolve({
                            current_page: 1,
                            data: [],
                            last_page: 1,
                            total: 0
                        });
                    });
                });
            },
            onChangePage(page) {
                this.$refs.indexProjectsTable.changePage(page);
            },
            onPaginationData(paginationData) {
                this.$refs.indexProjectsPagination.setPaginationData(
                    paginationData);
            },
            updateProjectIndex() {
                this.$refs.indexProjectsTable.refresh();
            }
        },
        name: "IndexProjects"
    }
</script>

<style scoped>

</style>
