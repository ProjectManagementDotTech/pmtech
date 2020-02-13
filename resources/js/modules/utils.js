window.Pusher = require("pusher-js");
import Echo from "laravel-echo";

export default function(Vue) {
    Vue.utils = {
        setupLaravelEcho() {
            return new Promise((resolve) => {
                if(window.Echo === undefined) {
                    window.Echo = new Echo({
                        authorizer: function(channel, options) {
                            return {
                                authorize: function(socketId, callback) {
                                    Vue.axios.post("/broadcasting/auth", {
                                        socket_id: socketId,
                                        channel_name: channel.name
                                    })
                                        .then(response => {
                                            callback(false, response.data);
                                        })
                                        .catch(error => {
                                            callback(true, error);
                                        });
                                }
                            }
                        },
                        broadcaster: "pusher",
                        cluster: process.env.MIX_PUSHER_APP_CLUSTER,
                        encrypted: true,
                        key: process.env.MIX_PUSHER_APP_KEY
                    });
                    resolve();
                } else {
                    resolve();
                }
            });
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
