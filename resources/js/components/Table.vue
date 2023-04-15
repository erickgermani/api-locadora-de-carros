<template>
    <div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col" v-for="(t, key) in titulos" :key="key">
                        {{ t.titulo }}
                    </th>
                    <th v-if="visualizar.visivel || atualizar || remover.visivel"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(obj, chave) in dadosFiltrados" :key="chave">
                    <td v-for="(valor, chaveValor) in obj" :key="chaveValor">
                        <span v-if="titulos[chaveValor].tipo === 'texto'">
                            {{ valor }}
                        </span>
                        <span v-else-if="titulos[chaveValor].tipo === 'data'">
                            {{ valor }}
                        </span>
                        <span v-else-if="titulos[chaveValor].tipo === 'imagem'">
                            <img
                                width="50"
                                height="50"
                                :src="'/storage/' + valor"
                            />
                        </span>
                    </td>
                    <td v-if="visualizar.visivel || atualizar || remover.visivel">
                        <button
                            v-if="visualizar.visivel"
                            class="btn btn-outline-primary btn-sm"
                            :data-bs-toggle="visualizar.dataBsToggle"
                            :data-bs-target="visualizar.dataBsTarget"
                            @click="setStore(obj)"
                        >
                            Visualizar
                        </button>
                        <button
                            v-if="atualizar"
                            class="btn btn-outline-primary btn-sm"
                        >
                            Atualizar
                        </button>
                        <button
                            v-if="remover.visivel"
                            class="btn btn-outline-danger btn-sm"
                            :data-bs-toggle="remover.dataBsToggle"
                            :data-bs-target="remover.dataBsTarget"
                            @click="setStore(obj)"
                        >
                            Remover
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: ["dados", "titulos", "atualizar", "remover", "visualizar"],
    methods: {
        setStore(obj) {
            this.$store.state.transacao = {
                status: '',
                mensagem: ''
            };

            this.$store.state.item = obj;
        }
    },
    computed: {
        dadosFiltrados() {
            const colunas = Object.keys(this.titulos);

            const itensFiltrados = [];

            this.dados.map((item, indice) => {
                const itemFiltrado = {};
                colunas.forEach((campo) => {
                    itemFiltrado[campo] = this.dados[indice][campo];
                });

                itensFiltrados.push(itemFiltrado);
            });

            return itensFiltrados;
        },
    },
};
</script>
