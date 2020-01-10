import Vue from "vue";
import Vuex from "vuex";

import authn from "./modules/authn";
import { projects } from "../components/members/projects";
import { workspaces } from "../components/members/workspaces";

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        authn, projects, workspaces
    },
});

