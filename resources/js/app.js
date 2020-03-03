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
import { moment } from "./modules";
import store from "./store";
import SubMenu from "./components/shared/navgiation/SubMenu";
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

Vue.component("sub-menu", SubMenu);
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
        Vue.axios.put("/api/v1/settings", {
            last_visited_view: to.fullPath
        });
    }
});
router.beforeEach((to, from, next) => {
    let toProjectId = to.params.projectId;
    let toWorkspaceId = to.params.workspaceId;
    let fromProjectId = from.params.projectId;
    let fromWorkspaceId = from.params.workspaceId;

    if(toWorkspaceId && fromWorkspaceId !== toWorkspaceId) {
        store.dispatch("workspaceChanged", toWorkspaceId);
    } else if(fromWorkspaceId && !toWorkspaceId) {
        store.dispatch("clearWorkspaceData");
    }
    if(toProjectId && fromProjectId !== toProjectId) {
        store.dispatch("projectChanged", toProjectId);
    } else if(fromProjectId && !toProjectId) {
        store.dispatch("clearProjectData");
    }

    next();
});

const i18n = new VueI18n({
    locale: "en",
    messages: loadLocaleMessages(),
    silentFallbackWarn: true
});

import App from "./views/App";

const app = new Vue({
    i18n,
    router,
    store,
    render: h => h(App)
}).$mount('#app');

