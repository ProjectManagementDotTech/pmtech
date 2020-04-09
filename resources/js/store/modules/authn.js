import Vue from "vue";

export default {
    actions: {
        authenticated({ commit }) {
            return new Promise((resolve, reject) => {
                Vue.axios.get("/api/v1/user")
                    .then(response => {
                        commit("currentUser", response.data);
                        console.log("After getting the user...");
                        Vue.axios.post("/api/v1/analytics", {
                            height: screen.availHeight,
                            user_agent: window.navigator.userAgent,
                            width: screen.availWidth
                        });
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
