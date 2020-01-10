window.Pusher = require("pusher-js");
import Echo from "laravel-echo";

export default function(Vue) {
    Vue.utils = {
        setupLaravelEcho() {
            if(window.Echo === undefined) {
                window.Echo = new Echo({
                    auth: {
                        headers: {
                            Authorization: localStorage.getItem("token_type") +
                                " " + localStorage.getItem("access_token")
                        }
                    },
                    broadcaster: "pusher",
                    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
                    encrypted: true,
                    key: process.env.MIX_PUSHER_APP_KEY
                })
            }
        }
    };

    Object.defineProperties(Vue.prototype, {
        $utils: {
            get() {
                return Vue.utils;
            }
        }
    });
};
