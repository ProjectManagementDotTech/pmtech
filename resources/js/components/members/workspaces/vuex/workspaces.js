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
        update({ commit }, workspaceId) {
            console.log("VUEX::workspaces::update");
            console.log("workspaceId: '" + workspaceId + "'");
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
        }
    },
    namespaced: true,
    state: {
        workspaces: []
    },
};
