require("./bootstrap");

import Vue from "vue";
import router from "./router";
import store from "./store";
import App from "./views/App";

import ElementUI from "element-ui";
import "element-ui/lib/theme-chalk/index.css";
/* Para cambiar los estilos descomente la siguiente linea */
//import "../sass/app.scss";
import locale from "element-ui/lib/locale/lang/es";
//import axios from 'axios';

Vue.use(ElementUI, { locale });

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        // this route requires auth, check if logged in
        // if not, redirect to login page.
        if (!store.getters.loggedIn) {
            next({
                name: "login"
            });
        } else {
            next();
        }
    } else {
        next(); // make sure to always call next()!
    }
});

/*router.beforeRouteUpdate((to, from, next)  => {
    console.log("mis pruebas");
    if (to.params.id !== from.params.id) {
        fetchDetails(router.params.id)
    }
    // called when the route that renders this component has changed.
    // This component being reused (by using an explicit `key`) in the new route or not doesn't change anything.
    // For example, for a route with dynamic params `/foo/:id`, when we
    // navigate between `/foo/1` and `/foo/2`, the same `Foo` component instance
    // will be reused (unless you provided a `key` to `<router-view>`), and this hook will be called when that happens.
    // has access to `this` component instance.
});*/

axios.interceptors.request.use(
    function (config) {
        const auth_token = localStorage.getItem("access_token");
        if (auth_token) {
            config.headers.Authorization = `Bearer ${auth_token}`; //auth_token
        }
        return config;
    },
    function (err) {
        return Promise.reject(err);
    }
);

axios.interceptors.response.use(
    function (response) {
        return response;
    },
    function (error) {
        // handle error
        if (error.response) {
            switch (error.response.status) {
                case 401:
                    localStorage.removeItem("access_token");
                    delete axios.defaults.headers.common["Authorization"];
                    router.push({ name: "login" });
                    break;
                default:
                    console.log(error.response);
            }
            return Promise.reject(error);
        }
    }
);

const app = new Vue({
    el: "#app",
    components: { App },
    router,
    store
});
