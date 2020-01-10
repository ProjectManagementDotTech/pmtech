export default function (Vue) {
    var moment = require("moment");
    Vue.moment = moment;

    Object.defineProperties(Vue.prototype, {
        $moment: {
            get() {
                return Vue.moment;
            }
        }
    });
}
