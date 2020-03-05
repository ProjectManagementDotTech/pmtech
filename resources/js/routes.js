import AcceptInvitation from "./views/public/AcceptInvitation";
import Clients from "./views/members/clients/Clients";
import ClientDashboard from "./views/members/clients/ClientDashboard";
import ClientHome from "./views/members/clients/ClientHome";
import Error402 from "./views/errors/Error402";
import Error403 from "./views/errors/Error403";
import Error404 from "./views/errors/Error404";
import Home from "./views/public/Home";
import HowItWorks from "./views/public/HowItWorks";
import Login from "./views/public/Login";
import Logout from "./views/members/Logout";
import PricingAndPlans from "./views/public/PricingAndPlans";
import ProjectDashboard from "./views/members/projects/ProjectDashboard";
import ProjectHome from "./views/members/projects/ProjectHome";
import ProjectSettings from "./views/members/projects/ProjectSettings";
import Register from "./views/public/Register";
import Test from "./views/members/Test";
import TimesheetEditor from "./views/members/timesheet/TimesheetEditor";
import TimesheetHome from "./views/members/timesheet/TimesheetHome";
import TimesheetReport from "./views/members/timesheet/TimesheetReport";
import UnknownError from "./views/errors/UnknownError";
import UserHome from "./views/members/users/UserHome";
import UserSettings from "./views/members/users/UserSettings";
import UserSettingsBilling from "./views/members/users/UserSettingsBilling";
import UserSettingsBillingInvoices from
    "./views/members/users/UserSettingsBillingInvoices";
import UserSettingsBillingPayment from
    "./views/members/users/UserSettingsBillingPayment";
import UserSettingsBillingPaymentMethods from
        "./views/members/users/UserSettingsBillingPaymentMethods";
import WorkspaceDashboard from "./views/members/workspaces/WorkspaceDashboard";
import WorkspaceHome from "./views/members/workspaces/WorkspaceHome";
import WorkspaceSettings from "./views/members/workspaces/WorkspaceSettings";

export default {
    mode: "history",
    routes: [
        {
            component: Home,
            meta: {
                isMemberPage: false
            },
            path: "/"
        },
        {
            component: HowItWorks,
            meta: {
                isMemberPage: false
            },
            path: "/how-it-works"
        },
        {
            component: AcceptInvitation,
            meta: {
                isMemberPage: false,
            },
            path: "/invitation/accept/:invitationNonce/:cacheNonce"
        },
        {
            component: Login,
            meta: {
                isMemberPage: false
            },
            path: "/login"
        },
        {
            component: Logout,
            meta: {
                isMemberPage: true
            },
            path: "/logout"
        },
        {
            component: PricingAndPlans,
            meta: {
                isMemberPage: false
            },
            path: "/pricing-and-plans"
        },
        {
            component: Register,
            meta: {
                isMemberPage: false
            },
            path: "/register"
        },
        {
            component: Error402,
            meta: {
                isMemberPage: true
            },
            path: "/402"
        },
        {
            component: Error403,
            meta: {
                isMemberPage: true
            },
            path: "/403"
        },
        {
            component: Error404,
            meta: {
                isMemberPage: true
            },
            path: "/404"
        },
        {
            component: UnknownError,
            meta: {
                isMemberPage: true
            },
            path: "/unknown-error"
        },
        {
            children: [
                {
                    component: WorkspaceDashboard,
                    meta: {
                        isMemberPage: true
                    },
                    path: ""
                },
                {
                    component: WorkspaceSettings,
                    meta: {
                        isMemberPage: true
                    },
                    path: "settings"
                },
                {
                    children: [
                        {
                            children: [
                                {
                                    component: ClientDashboard,
                                    meta: {
                                        isMemberPage: true
                                    },
                                    path: ""
                                }
                            ],
                            component: ClientHome,
                            meta: {
                                isMemberPage: true
                            },
                            path: ":clientId"
                        },
                    ],
                    component: Clients,
                    meta: {
                        isMemberPage: true
                    },
                    path: "clients"
                },
                {
                    children: [
                        {
                            component: ProjectDashboard,
                            meta: {
                                isMemberPage: true
                            },
                            path: ""
                        },
                        {
                            component: ProjectSettings,
                            meta: {
                                isMemberPage: true
                            },
                            path: "settings"
                        }
                    ],
                    component: ProjectHome,
                    meta: {
                        isMemberPage: true
                    },
                    path: "projects/:projectId"
                },
                {
                    children: [
                        {
                            component: TimesheetEditor,
                            meta: {
                                isMemberPage: true
                            },
                            path: ""
                        },
                        {
                            component: TimesheetReport,
                            meta: {
                                isMemberPage: true
                            },
                            path: "report"
                        }
                    ],
                    component: TimesheetHome,
                    meta: {
                        isMemberPage: true
                    },
                    path: "timesheet"
                },
                {
                    children: [
                        {
                            children: [
                                {
                                    children: [
                                        {
                                            component: UserSettingsBillingInvoices,
                                            meta: {
                                                isMemberPage: true
                                            },
                                            path: "invoices"
                                        },
                                        {
                                            component: UserSettingsBillingPayment,
                                            meta: {
                                                isMemberPage: true
                                            },
                                            path: "payment"
                                        },
                                        {
                                            component: UserSettingsBillingPaymentMethods,
                                            meta: {
                                                isMemberPage: true
                                            },
                                            path: "payment-methods"
                                        }
                                    ],
                                    component:  UserSettingsBilling,
                                    meta: {
                                        isMemberPage: true
                                    },
                                    path: "billing"
                                }
                            ],
                            component: UserSettings,
                            meta: {
                                isMemberPage: true
                            },
                            path: "settings"
                        }
                    ],
                    component: UserHome,
                    meta: {
                        isMemberPage: true
                    },
                    path: "users/:userId"
                }
            ],
            component: WorkspaceHome,
            meta: {
                isMemberPage: true
            },
            path: "/workspaces/:workspaceId"
        }
    ]
}
