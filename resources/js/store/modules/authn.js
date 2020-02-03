import Vue from "vue";

export default {
    actions: {
        authenticated({ commit }) {
            return new Promise((resolve, reject) => {
                Vue.axios.get("/api/v1/user")
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
            commit("currentUser", undefined);
        }
    },
    getters: {
        currentUser(state) {
            return state.currentUser;
        }
    },
    mutations: {
        currentUser(state, payload) {
            state.currentUser = payload;
        },
    },
    state: {
        currentUser: undefined
    }
};
