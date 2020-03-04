import Vue from "vue";
import Vuex from "vuex";

import authn from "./modules/authn";
import { clients } from "../components/members/clients";
import { projects } from "../components/members/projects";
import { tasks } from "../components/members/tasks";
import { workspaces } from "../components/members/workspaces";

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        authn, clients, projects, tasks, workspaces
    },
});

