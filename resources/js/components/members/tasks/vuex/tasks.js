import Vue from "vue";

export default {
    actions: {
        clearProjectData: {
            handler({ commit }) {
                commit("set", []);
            },
            root: true
        },
        fetchAll({ commit, dispatch }, projectId) {
            return new Promise((resolve, reject) => {
                Vue.axios.get("/api/v1/projects/" + projectId + "/tasks")
                    .then(response => {
                        commit("set", {
                            data: response.data,
                            etags: response.headers.etag
                        });
                        Vue.utils.setupLaravelEcho()
                            .then(() => {
                                response.data.forEach(task => {
                                    window.Echo.private("App.Task." + task.id)
                                        .listen(".App\\Events\\TaskUpdated", (event) => {
                                            dispatch("update", event.taskId);
                                        })
                                        .notification((notification) => {
                                            console.log("Task received notification");
                                            console.dir(notification);
                                        })
                                })
                            });
                        resolve();
                    })
                    .catch(error => {
                        reject(error);
                    });
            });
        },
        projectChanged: {
            handler({ dispatch }, projectId) {
                return dispatch("fetchAll", projectId);
            },
            root: true
        },
        update({ commit }, taskId) {
            Vue.axios.get("/api/v1/tasks/" + taskId)
                .then(response => {
                    commit("update", {
                        data: response.data,
                        etag: response.headers.etag
                    });
                })
                .catch(error => {
                    /*
                     * Log the error with the API, so it can report it in the
                     * log, DB and appropriate Slack channels.
                     */
                    console.log(error);
                    Vue.axios.post("/errors", error);
                });
        }
    },
    getters: {
        all(state) {
            return state.tasks;
        },
        byId: (state) => (id) => {
            return state.tasks.find(t => t.id == id);
        },
        eTag: (state) => (id) => {
            return state.etags[id];
        }
    },
    mutations: {
        add(state, newTask) {
            let existingTask = state.tasks.find(t => t.id == newTask.id);
            if(!existingTask) {
                state.tasks.push(newTask);
            }
        },
        set(state, payload) {
            if(payload.length != null && payload.length == 0) {
                return;
            }
            state.tasks = payload.data;
            let eTagString = payload.etags.replace(/"/g, "");
            let eTagArray = eTagString.split(";");
            for (let i = 0; i < eTagArray.length; i++) {
                let eTagParts = eTagArray[i].split(":");
                state.etags[eTagParts[0]] = eTagParts[1];
            }
        },
        update(state, payload) {
            let knownTask = state.tasks.find(t => t.id == payload.data.id);
            if(knownTask) {
                Vue.set(knownTask, "name", payload.data.name);
                Vue.set(knownTask, "wbs", payload.data.wbs);
                Vue.set(knownTask, "work_driven", payload.data.work_driven);
            }
            state.etags[payload.data.id] = payload.etag;
        }
    },
    namespaced: true,
    state: {
        etags: {},
        tasks: []
    }
};
