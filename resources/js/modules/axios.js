import axios from "axios";

export default function(Vue) {
    Vue.axios = new axios.create();
    Vue.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
    Vue.axios.defaults.headers.common["Accept"] = "application/json";

    /* Setup interceptors, if we need to for things like 401 / 403 / 404 */

    Vue.axios.interceptors.response.use((response) => {
        return response;
    }, (error) => {
        if(error.response !== undefined) {
            if(error.response.status == 401) {
                if(error.response.config.url == "/broadcasting/auth") {
                    return Promise.reject(error);
                }

                /*
                 * For now (January 4, 2020), when we get a 401, we let the user
                 * login again, and provide a back-link to push them back to
                 * where they were...
                 */
                let route = window.router.currentRoute;
                if (route.meta.isMemberPage) {
                    if (route.query.back != undefined) {
                        window.router.push("/login?back=" + route.query.back);
                    } else {
                        window.router.push("/login?back=" + route.fullPath);
                    }
                } else {
                    return Promise.reject(error);
                }
            } else if(error.response.status == 405) {
                Vue.axios.post("/errors", {
                    error: {
                        data: error.response.config.data,
                        method: error.response.config.method,
                        status: error.response.status,
                        statusText: error.response.statusText,
                        url: error.response.config.url
                    }
                }, {
                    baseURL: "https://" + window.location.hostname
                });
                return Promise.resolve();
            } else if(error.response.status == 422) {
                return Promise.reject(error);
            } else {
                Vue.axios.post("/errors", {
                    error: {
                        data: error.response.config.data,
                        method: error.response.config.method,
                        status: error.response.status,
                        statusText: error.response.statusText,
                        url: error.response.config.url
                    }
                }, {
                    baseURL: "https://" + window.location.hostname
                });
                window.router.push("/" + error.response.status);
            }
        } else {
            Vue.axios.post("/errors", {
                error: {
                    data: error.response.config.data,
                    method: error.response.config.method,
                    status: error.response.status,
                    statusText: error.response.statusText,
                    url: error.response.config.url
                }
            }, {
                baseURL: "https://" + window.location.hostname
            });
            window.router.push("/unknown-error");
        }
    });

    window.connectionInterval = window.setInterval(() => {
        Vue.axios.get("/api/v1/user");
    }, 3600000);

    Object.defineProperties(Vue.prototype, {
        $axios: {
            get() {
                return Vue.axios;
            }
        }
    });
}
