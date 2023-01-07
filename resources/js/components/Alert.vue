<template>
    <div :class="estilo" role="alert">
        <span>{{ detalhes.mensagem }}</span>

        <template v-if="detalhes.dados">
            <hr />
            <ul>
                <template v-for="(errors, key) in detalhes.dados">
                    <li
                        v-for="(error, secondKey) in errors"
                        :key="key + secondKey"
                    >
                        {{ error }}
                    </li>
                </template>
            </ul>
        </template>
    </div>
</template>

<script>
export default {
    props: ["tipo", "detalhes"],
    computed: {
        estilo() {
            return `alert alert-${this.tipo}`;
        },
    },
};
</script>
