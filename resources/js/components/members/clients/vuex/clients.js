import Vue from "vue";

export default {
    actions: {
        clearWorkspaceData: {
            handler({ commit }) {
                commit("set", []);
            },
            root: true
        },
        fetchAll({ commit }, workspaceId) {
            return new Promise((resolve, reject) => {
                Vue.axios.get("/api/v1/workspaces/" + workspaceId + "/clients")
                    .then(response => {
                        commit("set", response.data);
                        resolve();
                    })
                    .catch(error => {
                        reject(error);
                    });
            });
        },
        workspaceChanged: {
            handler({ dispatch }, workspaceId) {
                return dispatch("fetchAll", workspaceId)
            },
            root: true
        },
    },
    getters: {
        all(state) {
            return state.clients;
        },
        byId: (state) => (id) => {
            return state.clients.find(c => c.id === id);
        }
    },
    mutations: {
        set(state, payload) {
            state.clients = payload;
        }
    },
    namespaced: true,
    state: {
        clients: []
    }
};
