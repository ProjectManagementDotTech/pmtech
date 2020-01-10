function loadLocaleMessages()
{
    const locales = require.context("./modules/locales", true,
        /[A-Za-z0-9-_,\s]+\.json$/i);
    const messages = {};
    locales.keys().forEach(key => {
        const matched = key.match(/([A-Za-z0-9-_]+)\./i);
        if(matched && matched.length > 1) {
            const locale = matched[1];
            messages[locale] = locales(key);
        }
    });
    return messages;
}

import routes from "./routes";
window.router = new VueRouter(routes);

import { axios } from "./modules";
import Echo from "laravel-echo";
import { eventBus } from "./modules";
import Footer from "./components/shared/Footer";
import { moment } from "./modules";
import Navigation from "./components/shared/Navigation";
import store from "./store";
import { utils } from "./modules";
import { ValidationObserver, ValidationProvider } from
    "vee-validate/dist/vee-validate.full";
import Vue from "vue";
import VueI18n from "vue-i18n";
import VueRouter from "vue-router";

Vue.use(axios);
Vue.use(eventBus);
Vue.use(moment);
Vue.use(utils);
Vue.use(VueI18n);
Vue.use(VueRouter);

Vue.component("pmtech-footer", Footer);
Vue.component("navigation", Navigation);
Vue.component("validation-observer", ValidationObserver);
Vue.component("validation-provider", ValidationProvider);

/*
|-------------------------------------------------------------------------------
| Route Guards
|-------------------------------------------------------------------------------
|
| * afterEach
|
|   * Update the settings after each route to make sure we record the last
|     visited view.
|
| * beforeEach
|   * Before loading a new route, check that the workspace (if any) in the new
|     route is the same as the one (if any) in the old route. If they differ,
|     dispatch("workspaceChanged"). If the new route has no workspace, but the
|     old route did, dispatch("clearWorkspaceData").
 */
router.afterEach((to, from) => {
    if(store.getters["currentUser"] != undefined && to.fullPath !== "/logout") {
        Vue.axios.put("/settings", {
            last_visited_view: to.fullPath
        });
    }
});
router.beforeEach((to, from, next) => {
    let toWorkspaceId = to.params.workspaceId;
    let fromWorkspaceId = from.params.workspaceId;

    if(toWorkspaceId && fromWorkspaceId !== toWorkspaceId) {
        store.dispatch("workspaceChanged", toWorkspaceId);
    } else if(fromWorkspaceId && !toWorkspaceId) {
        store.dispatch("clearWorkspaceData");
    }

    next();
});

let accessToken = localStorage.getItem("access_token");
let tokenType = localStorage.getItem("token_type");
if(accessToken && tokenType) {
    store.commit("loggedIn");
    store.dispatch("authenticated")
        .then(() => {
            if(router.currentRoute && router.currentRoute == '') {
                let user = store.getters["currentUser"];
                if (user.settings.last_visited_view != null) {
                    router.push(user.settings.last_visited_view);
                } else {
                    let workspaces =
                        store.getters['workspaces/all'];
                    debugger;
                    if (workspaces.length == 1) {
                        debugger;
                        router.push("/workspaces/" +
                            workspaces[0].id);
                    } else {
                        let defaultWorkspace = workspaces.find(
                            w => w.name == "Default"
                        );
                        debugger;
                        if (defaultWorkspace) {
                            debugger;
                            router.push("/workspaces/" +
                                defaultWorkspace.id);
                        } else {
                            debugger;
                            router.push("/workspaces/" +
                                workspaces[0].id);
                        }
                    }
                }
            }
        })
        .catch(error => {
            console.dir(error);
            debugger;
        });

}

const i18n = new VueI18n({
    locale: "en",
    messages: loadLocaleMessages(),
    silentFallbackWarn: true
});

const app = new Vue({
    el: "#app",
    i18n,
    router,
    store
});

const appEl = document.getElementById("app");
appEl.onclick = () => {
    Vue.eventBus.$emit("blur");
/*
    console.log("App::onclick");
*/
};
