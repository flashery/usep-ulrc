/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");
require("./navigation-active");

window.Vue = require("vue");

import ElementUI from "element-ui";
import locale from "element-ui/lib/locale/lang/en";

Vue.use(ElementUI, { locale });

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component("home", require("./components/Home.vue").default);
Vue.component("about", require("./components/About.vue").default);
Vue.component("bibs", require("./components/Bibs.vue").default);
Vue.component(
    "profile-avatar",
    require("./components/ProfileAvatar.vue").default
);
Vue.component("dcs", require("./components/DCS.vue").default);
Vue.component("profile", require("./components/Profile.vue").default);
Vue.component("search", require("./components/Search.vue").default);
Vue.component("reports", require("./components/Reports.vue").default);
Vue.component("users", require("./components/Users.vue").default);

Vue.filter("noImage", image => {
    let image1 = "";
    image1 = image && image !== "" ? image : "/images/default.jpg";
    return image1;
});
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: "#app",
    events: {
        closeEvent: function() {
            console.log("close event called");
            this.hide();
        }
    }
});
