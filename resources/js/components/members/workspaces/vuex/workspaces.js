import Vue from "vue";

export default {
    actions: {
        authenticated: {
            handler({ dispatch }) {
                return dispatch("fetchAll");
            },
            root: true
        },
        fetchAll({ commit, dispatch }) {
            return new Promise((resolve, reject) => {
                Vue.axios.get("/workspaces")
                    .then(response => {
                        commit("set", response.data);
                        Vue.utils.setupLaravelEcho();
                        response.data.forEach(workspace => {
                            window.Echo.private("App.Workspace." + workspace.id)
                                .listen(".App\\Events\\WorkspaceUpdated", (event) => {
                                    dispatch("update", event.workspaceId);
                                })
                                .notification((notification) => {
                                    console.log("Workspace received notification");
                                    console.dir(notification);
                                });
                        });
                        resolve();
                    })
                    .catch(error => {
                        reject(error);
                    });
            });
        },
        loggedOut: {
            handler({ commit }) {
                commit("set", []);
            },
            root: true
        },
        update({ commit, dispatch }, workspaceId) {
            Vue.axios.get("/workspaces/" + workspaceId)
                .then(response => {
                    dispatch("workspaceChanged", workspaceId, { root: true });
                    commit("updateWorkspace", response.data);
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
            return state.workspaces;
        },
        byId: (state) => (id) => {
            return state.workspaces.find(w => w.id == id);
        },
        current(state) {
            return state.workspaces.find(
                w => w.id == Vue.router.currentRoute.params.workspaceId
            );
        },
        currentName(state) {
            let workspace = state.workspaces.find(
                w => w.id == Vue.router.currentRoute.params.workspaceId
            );
            if(workspace)
                return workspace.name;
            else
                return "N/A";
        }
    },
    mutations: {
        set(state, payload) {
            state.workspaces = payload;
        },
        updateWorkspace(state, newWorkspace) {
            let workspace = state.workspaces.find(w => w.id == newWorkspace.id);
            if(workspace) {
                Vue.set(workspace, "name", newWorkspace.name);
            }
        }
    },
    namespaced: true,
    state: {
        workspaces: []
    },
};
