<template>
    <article>
        <main>
            <vuetable pagination-path="" ref="indexTasksTable" :fields="fields"
                      :http-fetch="fetchTaskIndex"
                      @vuetable:pagination-data="onPaginationData">
            </vuetable>
            <vuetable-pagination ref="indexTasksPagination"
                                 @vuetable-pagination:change-page="onChangePage" />
        </main>
        <footer>
            <add-task @add-task:update-index="onUpdateIndex" />
        </footer>
    </article>
</template>

<script>
    import AddTask from "./AddTask";
    import Vuetable from "vuetable-2/src/components/Vuetable";
    import VuetablePagination from
            "vuetable-2/src/components/VuetablePagination";

    export default {
        components: {
            AddTask,
            Vuetable,
            VuetablePagination
        },
        data() {
            return {
                fields: [
                    {
                        name: "name",
                        title: "Task"
                    }
                ]
            };
        },
        methods: {
            fetchTaskIndex(apiUri, httpOptions) {
                return new Promise((resolve, reject) => {
                    this.$axios.get("/api/v1/projects/" +
                        this.$route.params.projectId + "/tasks", httpOptions)
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
                        })
                })
            },
            onChangePage(page) {
                this.$refs.indexTasksTable.changePage(page);
            },
            onPaginationData(paginationData) {
                this.$refs.indexTasksPagination.setPaginationData(
                    paginationData);
            },
            onUpdateIndex() {
                this.$refs.indexTasksTable.refresh();
            }
        },
        name: "indexTasks"
    }
</script>

<style scoped>

</style>
