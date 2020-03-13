<template>
    <div class="pt-1 px-2">
        <vuetable pagination-path="" ref="indexProjectsTable" :css="tableStyles"
                  :fields="fields" :http-fetch="fetchProjectIndex"
                  @vuetable:pagination-data="onPaginationData">
            <template slot="client" slot-scope="props">
                <span v-if="props.rowData.client_id !== null">
                    {{ $store.getters['clients/byId'](props.rowData.client_id).name }}
                </span>
            </template>
            <template slot="color" slot-scope="props">
                <div :style="'background-color: #' + props.rowData.color + '; display: block; min-height: 16px;'" />
            </template>
            <template slot="name" slot-scope="props">
                <router-link :to="'/workspaces/' + $route.params.workspaceId + '/projects/' + props.rowData.id">
                    {{ props.rowData.name }}
                </router-link>
            </template>
            <template slot="start_date" slot-scope="props">
                <span v-if="props.rowData.start_date !== null">
                    {{ $moment(props.rowData.start_date, "YYYY-MM-DD").format("DD MMM YYYY") }}
                </span>
            </template>
        </vuetable>
        <vuetable-pagination ref="indexProjectsPagination"
                             :css="paginationStyles"
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
                        name: "__slot:client",
                        title: "Client"
                    },
                    {
                        name: "__slot:color",
                        title: "Color"
                    },
                    {
                        name: "abbreviation",
                        sortField: "abbreviation",
                        title: "Abbreviation"
                    },
                    {
                        name: "__slot:name",
                        sortField: "name",
                        title: "Project name"
                    },
                    {
                        name: "__slot:start_date",
                        sortField: "start_date",
                        title: "Start date"
                    }
                ],
                paginationStyles: {
                    wrapperClass: "mb-2 bg-white border border-gray-200 rounded-lg inline-flex",
                    activeClass: "bg-indigo-400 text-white",
                    disabledClass: "text-gray-200 cursor-not-allowed",
                    pageClass: "cursor-pointer px-2 py-1",
                    linkClass: "cursor-pointer px-2 py-1",
                    paginationClass: "",
                    paginationInfoClass: "",
                    dropdownClass: "",
                    icons: {
                        first: "fas fa-angle-double-left",
                        prev: "fas fa-angle-left",
                        next: "fas fa-angle-right",
                        last: "fas fa-angle-double-right",
                    }
                },
                tableStyles: {
                    tableClass: "",
                    loadingClass: "",
                    ascendingIcon: "",
                    descendingIcon: "",
                    detailsRowClass: "",
                    handleIcon: "",
                    sortableIcon: ""
                }
            }
        },
        methods: {
            fetchProjectIndex(apiUri, httpOptions) {
                return new Promise((resolve, reject) => {
                    this.$axios.get("/api/v1/workspaces/" +
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
