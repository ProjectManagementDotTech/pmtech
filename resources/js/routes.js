import Home from "./views/public/Home";
import Login from "./views/public/Login";
import Logout from "./views/members/Logout";
import ProjectDashboard from "./views/members/projects/ProjectDashboard";
import ProjectHome from "./views/members/projects/ProjectHome";
import Register from "./views/public/Register";
import WorkspaceDashboard from "./views/members/workspaces/WorkspaceDashboard";
import WorkspaceHome from "./views/members/workspaces/WorkspaceHome";
import Test from "./views/members/Test";
import TimesheetEditor from "./views/members/timesheet/TimesheetEditor";
import TimesheetHome from "./views/members/timesheet/TimesheetHome";
import TimesheetReport from "./views/members/timesheet/TimesheetReport";

export default {
    mode: "history",
    routes: [
        {
            component: Home,
            path: "/"
        },
        {
            component: Login,
            path: "/login"
        },
        {
            component: Logout,
            path: "/logout"
        },
        {
            component: Register,
            path: "/register"
        },
        {
            component: WorkspaceHome,
            children: [
                {
                    component: WorkspaceDashboard,
                    path: ""
                },
                {
                    component: ProjectHome,
                    children: [
                        {
                            component: ProjectDashboard,
                            path: ""
                        }
                    ],
                    path: "projects/:projectId"
                },
                {
                    component: TimesheetHome,
                    children: [
                        {
                            component: TimesheetEditor,
                            path: ""
                        },
                        {
                            component: TimesheetReport,
                            path: "report"
                        }
                    ],
                    path: "timesheet"
                }
            ],
            path: "/workspaces/:workspaceId"
        }
    ]
}
