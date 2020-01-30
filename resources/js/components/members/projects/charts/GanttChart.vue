<template>
    <div class="w-full flex">
        <div class="w-full md:w-1/2">
            <grid-table :data="$store.getters['tasks/all']"
                        :empty-object="newTask" :fields="gridFields"
                        @input="onInput" />
        </div>
        <div class="hidden md:inline-block w-1/2">
        </div>
    </div>
</template>

<script>
    import GridTable from "../../general/GridTable";

    export default {
        data() {
            return {
                gridFields: [
                    {
                        attribute: "name",
                        sortable: "name",
                        title: "Name",
                        type: "text"
                    }
                ],
                newTask: {
                    id: undefined,
                    name: "",
                    wbs: "",
                }
            };
        },
        components: {
            GridTable
        },
        methods: {
            onInput(newTaskObject) {
                if (newTaskObject.name != "") {
                    if (newTaskObject.id !== undefined && newTaskObject.id !== "") {
                        console.dir(newTaskObject);
                        this.$axios.put("/tasks/" + newTaskObject.id,
                            newTaskObject);
                        this.$store.commit("tasks/update", newTaskObject);
                    } else {
                        this.$axios.post("/projects/" +
                            this.$route.params.projectId + "/tasks",
                            newTaskObject)
                            .then(response => {
                                let loc = response.headers.location;
                                let newTaskId = loc.substring(
                                    loc.lastIndexOf("/") + 1);
                                this.$axios.get("/tasks/" + newTaskId)
                                    .then(response => {
                                        this.$store.commit("tasks/add",
                                            response.data);
                                    })
                            });
                    }
                }
            }
        },
        name: "GanttChart"
    }
</script>

<style scoped>

</style>
