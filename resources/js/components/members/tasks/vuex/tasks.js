import Vue from "vue";

export default {
    actions: {
        clearProjectData: {
            handler({ commit }) {
                commit("set", []);
            },
            root: true
        },
        fetchAll({ commit }, projectId) {
            return new Promise((resolve, reject) => {
                Vue.axios.get("/api/v1/projects/" + projectId + "/tasks")
                    .then(response => {
                        commit("set", {
                            data: response.data,
                            etags: response.headers.etag
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
            state.tasks = payload.data;
            let eTagString = payload.etags.replace(/"/g, "");
            let eTagArray = eTagString.split(";");
            for(let i = 0; i < eTagArray.length; i++) {
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
