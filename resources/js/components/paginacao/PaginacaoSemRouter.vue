<template>
    <ul v-if="paginasTotal > 1">
        <li>
            <a
                @click.prevent="query(1)"
                :class="{active: paginaEstaAtiva(1)}"
                title="Primeira"
            >
                &lt;&lt;
            </a>
        </li>
        <li v-for="pagina in paginas" :key="pagina">
            <a
                @click.prevent="query(pagina)"
                :class="{active: paginaEstaAtiva(pagina)}"
            >
                {{ pagina }}
            </a>
        </li>
        <li>
            <a
                @click.prevent="query(paginasTotal)"
                :class="{active: paginaEstaAtiva(paginasTotal)}"
                title="Última"
            >
                &gt;&gt;
            </a>
        </li>
    </ul>
</template>

<script>
export default {
    name: "PaginacaoSemRouter",
    props: {
        porPagina: {
            type: Number,
            default: 1
        },
        total: {
            type: Number,
            default: 1
        },
        paginaAtual: {
            type: Number,
            default: 1
        }
    },
    methods: {
        query(pagina) {
            this.$emit("onChange", pagina);
        },
        paginaEstaAtiva(pagina) {
            return pagina === this.paginaAtual;
        }
    },
    computed: {
        paginas() {
            const current = this.paginaAtual;
            const range = 9;
            const offset = Math.ceil(range / 2);
            const total = this.paginasTotal;
            const pagesArray = [];

            for (let i = 1; i <= total; i++) {
                pagesArray.push(i);
            }

            pagesArray.splice(0, current - offset);
            pagesArray.splice(range, total);

            return pagesArray;
        },
        paginasTotal() {
            const total = this.total / this.porPagina;
            return total !== Infinity ? Math.ceil(total) : 0;
        },
    }
}
</script>

<style scoped>
ul {
    margin: 20px 0;
    text-align: center;
}

li {
    display: inline-block;
}

li a {
    cursor: pointer;
    padding: 2px 8px;
    border-radius: 2px;
    margin: 4px;
}

li a.router-link-exact-active, li a:hover, .active {
    background: var(--cor-principal);
    color: #fff;
}
</style>
