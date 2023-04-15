import "./bootstrap";
import { createApp } from "vue";

import Vuex from "vuex";

import ExampleComponent from "./components/ExampleComponent.vue";
import LoginComponent from "./components/Login.vue";
import HomeComponent from "./components/Home.vue";
import MarcasComponent from "./components/Marcas.vue";
import InputContainerComponent from "./components/InputContainer.vue";
import TableComponent from "./components/Table.vue";
import CardComponent from "./components/Card.vue";
import ModalComponent from "./components/Modal.vue";
import AlertComponent from "./components/Alert.vue";
import PaginateComponent from "./components/Paginate.vue";

const app = createApp({});

app.use(Vuex);

const store = new Vuex.Store({
    state: {
        item: {},
        transacao: {
            status: "",
            mensagem: "",
            dados: "",
        },
    },
});

app.use(store);

app.config.globalProperties.$filters = {
    formataDataTempo(d)  {
        if (!d) return "";

        d = d.split("T");

        let data = d[0];
        let tempo = d[1].split('.').shift();

        data = data.split('-').reverse().join('/');

        return `${data} ${tempo}`;
    }
}

app.component("example-component", ExampleComponent);
app.component("login-component", LoginComponent);
app.component("home-component", HomeComponent);
app.component("marcas-component", MarcasComponent);
app.component("input-container-component", InputContainerComponent);
app.component("table-component", TableComponent);
app.component("card-component", CardComponent);
app.component("modal-component", ModalComponent);
app.component("alert-component", AlertComponent);
app.component("paginate-component", PaginateComponent);

app.mount("#app");
