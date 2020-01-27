import Error402 from "./views/errors/Error402";
import Error403 from "./views/errors/Error403";
import Error404 from "./views/errors/Error404";
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
import UnknownError from "./views/errors/UnknownError";

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
            component: Error402,
            path: "/402"
        },
        {
            component: Error403,
            path: "/403"
        },
        {
            component: Error404,
            path: "/404"
        },
        {
            component: UnknownError,
            path: "/unknown-error"
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
