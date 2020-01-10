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
        },
        uuid() {
            if(window.uuidv4 === undefined) {
                window.uuidv4 = require("uuid/v4");
            }

            return window.uuidv4();
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
