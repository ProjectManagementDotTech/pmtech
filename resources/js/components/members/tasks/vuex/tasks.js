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
                Vue.axios.get("/projects/" + projectId + "/tasks")
                    .then(response => {
                        commit("set", response.data);
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
            state.tasks = payload;
        },
        update(state, aTask) {
            let knownTask = state.tasks.find(t => t.id == aTask.id);
            if(knownTask) {
                Vue.set(knownTask, "name", aTask.name);
                Vue.set(knownTask, "wbs", aTask.wbs);
            }
        }
    },
    namespaced: true,
    state: {
        tasks: []
    }
};
