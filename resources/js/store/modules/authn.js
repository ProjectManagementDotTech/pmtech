import Vue from "vue";

export default {
    actions: {
        authenticated({ commit }) {
            return new Promise((resolve, reject) => {
                Vue.axios.get("user")
                    .then(response => {
                        commit("currentUser", response.data);
                        resolve();
                    })
                    .catch(error => {
                        reject(error);
                    });
            });
        },
        loggedOut({ commit }) {
            commit("loggedOut");
            commit("currentUser", { name: '...' });
        }
    },
    getters: {
        authenticated(state) {
            return state.authenticated;
        },
        currentUser(state) {
            return state.currentUser;
        }
    },
    mutations: {
        currentUser(state, payload) {
            state.currentUser = payload;
        },
        loggedIn(state) {
            state.authenticated = true;
        },
        loggedOut(state) {
            state.authenticated = false;
        }
    },
    state: {
        authenticated: false,
        currentUser: {
            name: "..."
        }
    }
};
