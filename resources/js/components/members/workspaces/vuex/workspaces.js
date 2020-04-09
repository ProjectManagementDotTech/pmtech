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
                Vue.axios.get("/api/v1/workspaces")
                    .then(response => {
                        commit("set", {
                            data: response.data,
                            etags: response.headers.etag
                        });
                        Vue.utils.setupLaravelEcho()
                            .then(() => {
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
                            });
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
            Vue.axios.get("/api/v1/workspaces/" + workspaceId)
                .then(response => {
                    dispatch("workspaceChanged", workspaceId, { root: true });
                    commit("updateWorkspace", {
                        newWorkspace: response.data,
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
            return state.workspaces;
        },
        byId: (state) => (id) => {
            return state.workspaces.find(w => w.id == id);
        },
        byOwnerUserId: (state) => (ownerUserId) => {
            return state.workspaces.filter(w => w.owner_user_id == ownerUserId);
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
        },
        eTag: (state) => (id) => {
            return state.etags[id];
        }
    },
    mutations: {
        set(state, payload) {
            state.workspaces = payload.data;
            let eTagString = payload.etags.replace(/"/g, "");
            let eTagArray = eTagString.split(";");
            for(let i = 0; i < eTagArray.length; i++) {
                let eTagParts = eTagArray[i].split(":");
                state.etags[eTagParts[0]] = eTagParts[1];
            }
        },
        updateWorkspace(state, payload) {
            let workspace = state.workspaces.find(
                w => w.id == payload.newWorkspace.id
            );
            if(workspace) {
                Vue.set(workspace, "name", newWorkspace.name);
            }
            state.etags[payload.newWorkspace.id] = payload.etag;
        }
    },
    namespaced: true,
    state: {
        etags: {},
        workspaces: []
    },
};
