import axios from "axios";

export default function(Vue) {
    Vue.axios = new axios.create({
        baseURL: "https://" + window.location.hostname + "/api/v1"
    });
    Vue.axios.defaults.headers.common["X-Application-ID"] = "PMTechVue";
    Vue.axios.defaults.headers.common["Accept"] = "application/json";

    /* Setup interceptors, if we need to for things like 401 / 403 / 404 */

    let accessToken = localStorage.getItem("access_token");
    let tokenType = localStorage.getItem("token_type");
    if(accessToken && tokenType) {
        Vue.axios.defaults.headers.common["Authorization"] = tokenType + " " +
            accessToken;
    }

    Vue.axios.interceptors.response.use((response) => {
        return response;
    }, (error) => {
        if(error.response !== undefined) {
            if(error.response.status == 401) {
                /*
                 * For now (January 4, 2020), when we get a 401, we let the user
                 * login again, and provide a back-link to push them back to
                 * where they were...
                 */
                let route = window.router.currentRoute;
                window.router.push("/login?back=" + route.fullPath);
                return Promise.resolve();
            } else {
                console.log("Vue.axios.interceptor.response::error");
                console.dir(error);
                return Promise.reject(error);
            }
        } else {
            console.log("Vue.axios.interceptor.response::error");
            console.dir(error);
            return Promise.reject(error);
        }
    });
    Object.defineProperties(Vue.prototype, {
        $axios: {
            get() {
                return Vue.axios;
            }
        }
    });
}
