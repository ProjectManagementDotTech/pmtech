export default {
    getters: {
        isNewVersionAvailable(state) {
            return state.newVersionAvailable;
        }
    },
    mutations: {
        newVersionAvailable(state) {
            state.newVersionAvailable = true;
        }
    },
    state: {
        newVersionAvailable: false
    }
};
