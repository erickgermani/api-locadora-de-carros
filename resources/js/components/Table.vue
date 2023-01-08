<template>
    <div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col" v-for="(t, key) in titulos" :key="key">
                        {{ t.titulo }}
                    </th>
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
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: ["dados", "titulos"],
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
