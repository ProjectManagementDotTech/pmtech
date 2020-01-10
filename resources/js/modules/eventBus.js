export default function(Vue) {
    Vue.eventBus = new Vue();

    Object.defineProperties(Vue.prototype, {
        $eventBus: {
            get() {
                return Vue.eventBus;
            }
        }
    });
};
