import _ from "lodash";
window._ = _;

import "bootstrap";

import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

axios.interceptors.request.use(
    (config) => {
        config.headers.Accept = "application/json";

        const token = document.cookie
            .split(";")
            .find((cookie) => cookie.includes("token="))
            .split("=")
            .pop();

        config.headers.Authorization = `bearer ${token}`;

        return config;
    },
    (error) => {
        console.log("Erro na requisição: ", error);
        return Promise.reject(error);
    }
);

axios.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        return Promise.reject(error);
    }
);
