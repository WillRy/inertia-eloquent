<template>
    <div>
        <PageHeader>
            <template #title>
                <h3>Matrículas</h3>
            </template>
            <template #actions>
                <button class="btn btn-min btn-primary" @click="abrirModalCadastro = true">
                    Cadastrar
                </button>

                <BaseSelect
                    placeholder="Digite o plano"
                    v-model="planFilter"
                    track-by="id"
                    text-by="name"
                    :options="plans"
                    :internal-search="false"
                    :limit="3"
                    @search-change="pesquisarPlanos"
                    @change="pesquisar"
                    width="240px"
                    mb="0px"
                    :clear="true"
                >
                </BaseSelect>

                <BaseInput
                    type="text"
                    placeholder="Pesquisa por aluno"
                    width="240px"
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
        <Loader v-if="loading" width="120px" height="120px" fill="#020540"/>

        <ModalAddSubscription
            :aberta="abrirModalCadastro"
            @onClose="abrirModalCadastro = false"
        />

        <ModalRemoveSubscription/>
        <ModalEditSubscription/>
    </div>
</template>

<script>
import Dashboard from "../../Layouts/Dashboard";
import PageHeader from "../../components/dashboard/PageHeader";
import BaseInput from "../../components/forms/BaseInput";
import {mapActions, mapState} from 'vuex'
import PaginacaoSemRouter from "../../components/paginacao/PaginacaoSemRouter";
import BaseSelect from "../../components/forms/BaseSelect";
import ModalAddPlan from '../../components/plans/ModalAddPlan';
import ModalEditPlan from '../../components/plans/ModalEditPlan';
import ModalRemovePlan from '../../components/plans/ModalRemovePlan';
import ModalAddSubscription from '../../components/subscriptions/ModalAddSubscription';
import ModalEditSubscription from '../../components/subscriptions/ModalEditSubscription';

import axios from "axios";
import SubscriptionCard from "../../components/subscriptions/SubscriptionCard";
import Loader from "../../components/Loader";
import ModalRemoveSubscription from "../../components/subscriptions/ModalRemoveSubscription";

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
            abrirModalCadastro: false,
            loading: false,
            subscriptions: null,
            search: null,
            page: 1,
            plan: null
        }
    },
    computed: {
        ...mapState({
            'matriculas_reload': 'matriculas_reload'
        }),
        esconderLoading() {
            return this.loading && this.page < 2;
        },
        paginaTotal() {
            return this.subscriptions && this.subscriptions.last_page || 1;
        }
    },
    watch: {
        matriculas_reload(payload) {
            if (payload && payload.tipo === "exclusao") {
                let index = this.subscriptions.data.findIndex((item) => item.id === payload.id);
                this.subscriptions.data.splice(index, 1);
            } else if (payload && payload.tipo === "edicao") {
                let index = this.subscriptions.data.findIndex((item) => item.id === payload.id);
                this.subscriptions.data[index] = payload;
            } else if (payload && payload.tipo === "criacao") {
                this.subscriptions.data.unshift(payload);
            }
        },
    },
    methods: {
        ...mapActions([
            'returnPlanos'
        ]),
        pesquisarPlanos(pesquisa){
            axios.get("/dashboard/plans/list", {params: {search: pesquisa}}).then((response)=>{
                this.plans = response.data.data;
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
            }, {
                // preserveState: true
            })
        },
        aumentarPagina() {

            let bottomOfWindow = Math.ceil(window.innerHeight + window.scrollY) + 60 >= document.body.scrollHeight;
            if (bottomOfWindow && this.page < this.paginaTotal && !this.loading) {
                this.page++;
                this.carregarDados();
            }
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
                // this.aumentarPagina();
            }).catch((e) => {
                console.log(e);
            }).finally(() => {
                this.loading = false;
            })

        }
    },
    created() {
        this.planFilter = this.planURL;
        this.search = this.searchURL;
        this.carregarDados();

        window.addEventListener("scroll", this.aumentarPagina);
    },
    beforeUnmount() {
        window.removeEventListener("scroll", this.aumentarPagina);
    }
}
</script>

<style scoped>
</style>
