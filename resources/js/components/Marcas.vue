<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Busca de marcas -->
                <card-component titulo="Busca de marcas">
                    <template v-slot:conteudo>
                        <div class="row">
                            <div class="col mb-3">
                                <input-container-component
                                    titulo="ID da marca"
                                    id="inputId"
                                    id-help="idHelp"
                                    texto-ajuda="Opcional. Informe o ID da marca."
                                >
                                    <input
                                        type="number"
                                        class="form-control"
                                        id="inputId"
                                        aria-describedby="idHelp"
                                        placeholder="ID"
                                        v-model="busca.id"
                                    />
                                </input-container-component>
                            </div>
                            <div class="col mb-3">
                                <input-container-component
                                    titulo="Nome da marca"
                                    id="inputNome"
                                    id-help="nomeHelp"
                                    texto-ajuda="Opcional. Informe o nome da marca."
                                >
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="inputNome"
                                        aria-describedby="nomeHelp"
                                        placeholder="Nome da marca"
                                        v-model="busca.nome"
                                    />
                                </input-container-component>
                            </div>
                        </div>
                    </template>

                    <template v-slot:rodape>
                        <button
                            type="submit"
                            class="btn btn-primary btn-sm float-end"
                            @click="pesquisar()"
                        >
                            Pesquisar
                        </button>
                    </template>
                </card-component>

                <!-- Listagem de marcas -->
                <card-component titulo="Rela????o de marcas">
                    <template v-slot:conteudo
                        ><table-component
                            :dados="marcas.data"
                            :titulos="{
                                id: { titulo: 'Id', tipo: 'texto' },
                                nome: { titulo: 'Nome', tipo: 'texto' },
                                imagem: { titulo: 'Logo', tipo: 'imagem' },
                                created_at: {
                                    titulo: 'Criado em',
                                    tipo: 'data',
                                },
                            }"
                        ></table-component
                    ></template>
                    <template v-slot:rodape>
                        <div class="row">
                            <div class="col-10">
                                <paginate-component>
                                    <li
                                        v-for="(l, key) in marcas.links"
                                        :key="key"
                                        :class="
                                            l.active
                                                ? 'page-item active'
                                                : 'page-item'
                                        "
                                        @click="paginacao(l)"
                                    >
                                        <a class="page-link" v-html="l.label">
                                        </a>
                                    </li>
                                </paginate-component>
                            </div>

                            <div class="col">
                                <button
                                    type="submit"
                                    class="btn btn-primary btn-sm float-end"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalMarca"
                                >
                                    Adicionar
                                </button>
                            </div>
                        </div>
                    </template>
                </card-component>
            </div>
        </div>

        <modal-component id="modalMarca" titulo="Adicionar marca">
            <template v-slot:alertas>
                <alert-component
                    tipo="success"
                    :detalhes="transacaoDetalhes"
                    v-if="transacaoStatus === 'adicionado'"
                ></alert-component>
                <alert-component
                    tipo="danger"
                    :detalhes="transacaoDetalhes"
                    v-if="transacaoStatus === 'erro'"
                ></alert-component>
            </template>

            <template v-slot:conteudo>
                <div class="form-group">
                    <input-container-component
                        titulo="Nome da marca"
                        id="novoNome"
                        id-help="novoNomeHelp"
                        texto-ajuda="Informe o nome da marca."
                    >
                        <input
                            type="text"
                            class="form-control"
                            id="inputNome"
                            aria-describedby="nomeHelp"
                            placeholder="Nome da marca"
                            v-model="nomeMarca"
                        />
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component
                        titulo="Imagem da marca"
                        id="novoImagem"
                        id-help="novoImagemHelp"
                        texto-ajuda="Selecione uma imagem no formato PNG"
                    >
                        <input
                            type="file"
                            class="form-control"
                            id="inputNome"
                            aria-describedby="nomeHelp"
                            placeholder="Selecione uma imagem"
                            @change="carregarImagem($event)"
                        />
                    </input-container-component>
                </div>
            </template>

            <template v-slot:rodape>
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Fechar
                </button>
                <button type="button" class="btn btn-primary" @click="salvar()">
                    Salvar
                </button>
            </template>
        </modal-component>
    </div>
</template>

<script>
export default {
    data() {
        return {
            urlBase: "http://localhost:8000/api/v1/marca",
            urlPaginacao: "",
            urlFiltro: "",
            nomeMarca: "",
            arquivoImagem: [],
            transacaoStatus: "",
            transacaoDetalhes: {},
            marcas: {
                data: [],
            },
            busca: {
                id: "",
                nome: "",
            },
        };
    },
    computed: {
        token() {
            const token = document.cookie
                .split(";")
                .find((cookie) => cookie.includes("token="))
                .split("=")
                .pop();

            return `bearer ${token}`;
        },
    },
    methods: {
        pesquisar() {
            let filtro = "";

            for (let chave in this.busca) {
                const valor = this.busca[chave];

                if (valor) {
                    if (filtro.length > 0) filtro += ";";

                    const operador =
                        typeof valor === "string" ? ":like:" : ":=:";

                    filtro += chave + operador + valor;
                }
            }

            if (filtro) {
                this.urlPaginacao = "page=1";
                this.urlFiltro = "filtro=" + filtro;
            } else this.urlFiltro = "";

            this.carregarLista();
        },
        paginacao(l) {
            console.log;
            if (l.url) {
                this.urlPaginacao = l.url.split("?").pop();
                this.carregarLista();
            }
        },
        carregarLista() {
            const config = {
                headers: {
                    Accept: "application/json",
                    Authorization: this.token,
                },
            };

            const url =
                this.urlBase + "?" + this.urlPaginacao + "&" + this.urlFiltro;

            console.log(url);

            axios
                .get(url, config)
                .then((res) => {
                    this.marcas = res.data;
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        carregarImagem(e) {
            this.arquivoImagem = e.target.files;
        },
        salvar() {
            const formData = new FormData();
            formData.append("nome", this.nomeMarca);
            formData.append("imagem", this.arquivoImagem[0]);

            const config = {
                headers: {
                    "Content-Type": "multipart/form-data",
                    Accept: "application/json",
                    Authorization: this.token,
                },
            };

            axios
                .post(this.urlBase, formData, config)
                .then(() => {
                    this.transacaoStatus = "adicionado";
                    this.transacaoDetalhes = {
                        mensagem: "Marca cadastrada com sucesso",
                    };
                })
                .catch((error) => {
                    this.transacaoStatus = "erro";
                    this.transacaoDetalhes = {
                        mensagem: "Erro ao tentar cadastrar a marca",
                        dados: error.response.data.errors,
                    };
                });
        },
    },
    mounted() {
        this.carregarLista();
    },
};
</script>
