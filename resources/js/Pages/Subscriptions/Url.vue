<template>
    <div>
        <PageHeader>
            <template #title>
                <h3>Matrículas</h3>
            </template>
            <template #actions>
                <button class="btn btn-min btn-primary" @click="abrirModalCadastro">
                    Cadastrar
                </button>

                <BaseSelect
                    placeholder="Pesquise o plano"
                    v-model="planFilter"
                    track-by="id"
                    text-by="name"
                    :options="plans"
                    :internal-search="false"
                    :limit="3"
                    @search-change="pesquisarPlanos"
                    @change="pesquisar"
                    mb="0px"
                    :clear="true"
                    noOptions="Pesquise o plano"
                >
                </BaseSelect>

                <BaseInput
                    type="text"
                    placeholder="Pesquisa por aluno"
                    mb="0px"
                    @keyup.enter="pesquisar"
                    v-model="search"
                />
            </template>
        </PageHeader>
        <div class="box-grid" v-if="subscriptions && subscriptions.data.length && !esconderLoading">
            <SubscriptionCard
                v-for="(dados,index) in subscriptions.data"
                :data="dados"
                :key="dados.id"
            />
        </div>
        <Loader v-if="loading" width="120px" height="120px" :cor-principal="true"/>

        <ModalAddSubscription/>

        <ModalRemoveSubscription/>
        <ModalEditSubscription/>
    </div>
</template>

<script>
import Dashboard from "../../Layouts/Dashboard";
import PageHeader from "../../components/dashboard/PageHeader";
import BaseInput from "../../components/base/form/BaseInput";
import {mapActions, mapState} from 'vuex'
import PaginacaoSemRouter from "../../components/base/paginacao/PaginacaoSemRouter";
import BaseSelect from "../../components/base/form/BaseSelect";
import ModalAddPlan from '../../components/plans/ModalAddPlan';
import ModalEditPlan from '../../components/plans/ModalEditPlan';
import ModalRemovePlan from '../../components/plans/ModalRemovePlan';
import ModalAddSubscription from '../../components/subscriptions/ModalAddSubscription';
import ModalEditSubscription from '../../components/subscriptions/ModalEditSubscription';

import axios from "axios";
import SubscriptionCard from "../../components/subscriptions/SubscriptionCard";
import Loader from "../../components/Loader";
import ModalRemoveSubscription from "../../components/subscriptions/ModalRemoveSubscription";
import {debounce} from '../../helpers';

export default {
    name: "Index",
    components: {
        ModalAddSubscription,
        ModalEditSubscription,
        ModalRemoveSubscription,
        Loader,
        SubscriptionCard,
        BaseSelect,
        PaginacaoSemRouter,
        BaseInput,
        PageHeader,
        ModalAddPlan,
        ModalEditPlan,
        ModalRemovePlan
    },
    layout: Dashboard,
    props: [
        "searchURL",
        "planURL",
        "pageURL"
    ],
    data() {
        return {
            plans: [{id: '', name: 'Todos'}],
            planFilter: null,
            loading: false,
            subscriptions: null,
            search: null,
            page: 1,
            plan: null
        }
    },
    computed: {
        esconderLoading() {
            return this.loading && this.page < 2;
        },
        paginaTotal() {
            return this.subscriptions && this.subscriptions.last_page || 1;
        }
    },
    methods: {
        pesquisarPlanos(pesquisa) {
            axios.get("/dashboard/plans/list", {params: {search: pesquisa}}).then((response) => {
                this.plans = response.data.data.data;
                this.plans.unshift({
                    id: '',
                    name: 'Todos'
                });
            });
        },
        pesquisar() {
            this.page = 1;
            this.$inertia.get("/dashboard/subscriptions/url", {
                ...(this.search ? {search: this.search} : {}),
                ...(this.planFilter && this.planFilter.id ? {plan: this.planFilter.id} : {}),
            });
        },
        aumentarPagina: debounce(function () {
            let bottomOfWindow = Math.ceil(window.innerHeight + window.scrollY) + 60 >= document.body.scrollHeight;
            if (bottomOfWindow && this.page < this.paginaTotal && !this.loading) {
                this.page++;
                this.carregarDados();
            }
        }, 500),
        abrirModalCadastro(){
            this.$eventBus.$emit("ModalAddSubscription:config", {});
        },
        async carregarDados() {
            this.loading = true;

            axios.get("/dashboard/subscriptions/list", {
                params: {
                    ...(this.search ? {search: this.search} : {}),
                    ...(this.page ? {page: this.page} : {}),
                    ...(this.planFilter && this.planFilter.id ? {plan: this.planFilter.id} : {}),
                }
            }).then((response) => {

                let oldValues = this.subscriptions ? this.subscriptions.data : [];
                let newValues = response.data.data.data ? response.data.data.data : [];
                let data = [];

                //se nao for primeira pagina, concatena novos itens
                //se primeira pagina, pega só os novos itens
                if (response.data.data.current_page > 1) {
                    data = [...oldValues, ...newValues];
                } else {
                    data = [...newValues];
                }

                // console.log(data);
                this.subscriptions = Object.assign({}, this.subscriptions, response.data.data);
                // console.log(this.subscriptions);

                this.subscriptions.data = data;

                this.loading = false;

                //aumenta a página caso existam mais espaço em tela para exibir mais paginas
                //em caso de monitores muito altos
                this.aumentarPagina();
            }).catch((e) => {
                console.log(e);
            }).finally(() => {
                this.loading = false;
            })

        }
    },
    beforeUnmount() {
        window.removeEventListener("scroll", this.aumentarPagina);
        this.$eventBus.$off("ModalAddSubscription:reload");
    },
    created() {
        this.planFilter = this.planURL;
        this.search = this.searchURL;

        /**
         * carrega os dados via AJAX ao invés do inertiajs
         *
         * Depois monitora a mudança de URL com "preserveState"
         * */
        this.carregarDados();

        window.addEventListener("scroll", this.aumentarPagina);

        this.$eventBus.$on("ModalAddSubscription:reload", (e) => {
            if(e.dados) this.subscriptions.data.unshift(e.dados);
        });
        this.$eventBus.$on("ModalEditSubscription:reload", (e) => {
            if(e.dados) {
                let index = this.subscriptions.data.findIndex((item) => item.id === e.dados.id);
                this.subscriptions.data[index] = e.dados;
            }
        });
        this.$eventBus.$on("ModalRemoveSubscription:reload", (e) => {
            if(e.dados) {
                let index = this.subscriptions.data.findIndex((item) => item.id === e.dados.id);
                this.subscriptions.data.splice(index, 1);
            }
        });
    },
}
</script>

<style scoped>
.box {
    padding: 30px;
    background: #FFFFFF;
    border-radius: 4px;
}

</style>
