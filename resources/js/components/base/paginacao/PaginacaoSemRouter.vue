<template>
    <div>
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
        <p class="total-registros" v-if="exibirTotal && total > 0">
            <strong>Total:</strong> {{total}} resultados
        </p>
    </div>
</template>

<script>
export default {
    name: "PaginacaoSemRouter",
    props: {
        exibirTotal: false,
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
    margin: 20px 0 8px 0px;
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
    user-select: none;
}

li a.router-link-exact-active, li a:hover, .active {
    background: var(--cor-principal);
    color: #fff;
}

.total-registros {
    border: 2px solid #bbbfc4;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 400;
    margin: 0 auto;
    max-width: max-content;
    padding: 4px 10px;
    text-align: center;
    background: #ebedf0;
}
</style>
