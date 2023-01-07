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
                                    />
                                </input-container-component>
                            </div>
                        </div>
                    </template>

                    <template v-slot:rodape>
                        <button
                            type="submit"
                            class="btn btn-primary btn-sm float-end"
                        >
                            Pesquisar
                        </button>
                    </template>
                </card-component>

                <!-- Listagem de marcas -->
                <card-component titulo="Relação de marcas">
                    <template v-slot:conteudo
                        ><table-component></table-component
                    ></template>
                    <template v-slot:rodape
                        ><button
                            type="submit"
                            class="btn btn-primary btn-sm float-end"
                            data-bs-toggle="modal"
                            data-bs-target="#modalMarca"
                        >
                            Adicionar
                        </button></template
                    >
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
            nomeMarca: "",
            arquivoImagem: [],
            transacaoStatus: "",
            transacaoDetalhes: {},
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
};
</script>
