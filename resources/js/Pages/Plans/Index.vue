<template>
    <div>
        <PageHeader>
            <template #title>
                <h3>Planos</h3>
            </template>
            <template #actions>
                <button class="btn btn-min btn-primary" @click="abrirModalCadastro = true">
                    Cadastrar
                </button>

                <BaseInput
                    type="text"
                    placeholder="Pesquisa por nome ou valor"
                    width="240px"
                    mb="0px"
                    @keyup.enter="pesquisar"
                    v-model="search"
                />
            </template>
        </PageHeader>
        <div class="box">
            <table :class="{loading: loading}">
                <thead>
                <tr>
                    <th style="width: 500px">Nome</th>
                    <th style="width: 200px">Preço</th>
                    <th style="width: 200px">Periodo (em mêses)</th>
                    <th style="width: 200px">Ações</th>
                </tr>
                </thead>
                <tbody v-if="plans && plans.data">
                <tr v-for="(plan,index) in plans.data" :key="index">
                    <td>{{ plan.name }}</td>
                    <td>{{ $filters.dinheiro(plan.price) }}</td>
                    <td>{{ plan.duration }}</td>
                    <td>
                        <button class="link-action link-action-primary" @click="abrirEdicao(plan.id)">
                            editar
                        </button>
                        <button class="link-action link-action-danger" @click="abrirExclusao(plan.id)">
                            excluir
                        </button>
                    </td>
                </tr>
                </tbody>
                <tbody v-if="!loading && plans.data.length === 0">
                <tr>
                    <td colspan="7" style="text-align: center">Não há registros para esta pesquisa</td>
                </tr>
                </tbody>
            </table>
            <PaginacaoSemRouter
                v-if="plans"
                :pagina-atual="plans.current_page"
                :total="plans.total"
                :porPagina="plans.per_page"
                @onChange="updatePagina($event)"
            />
        </div>

        <ModalAddPlan
            :aberta="abrirModalCadastro"
            @onClose="abrirModalCadastro = false"
        />

        <ModalEditPlan/>
        <ModalRemovePlan/>

    </div>
</template>

<script>
import Dashboard from "../../Layouts/Dashboard";
import PageHeader from "../../components/dashboard/PageHeader";
import BaseInput from "../../components/forms/BaseInput";
import {mapMutations, mapState, mapActions} from 'vuex'
import PaginacaoSemRouter from "../../components/paginacao/PaginacaoSemRouter";
import BaseSelect from "../../components/forms/BaseSelect";
import ModalAddPlan from '../../components/plans/ModalAddPlan';
import ModalEditPlan from '../../components/plans/ModalEditPlan';
import ModalRemovePlan from '../../components/plans/ModalRemovePlan';

import axios from "axios";

export default {
    name: "Index",
    components: {
        BaseSelect,
        PaginacaoSemRouter,
        BaseInput,
        PageHeader,
        ModalAddPlan,
        ModalEditPlan,
        ModalRemovePlan
    },
    layout: Dashboard,
    data() {
        return {
            abrirModalCadastro: false,
            loading: false,
            plans: null
        }
    },
    computed: {
        ...mapState({
            'planos_reload': 'planos_reload',
            'planos_filtro_pesquisa': 'planos_filtro_pesquisa',
            'planos_filtro_pagina': 'planos_filtro_pagina',
        }),
        search: {
            get() {
                return this.planos_filtro_pesquisa;
            },
            set(value) {
                return this.SET_PLANOS_FILTRO_PESQUISA(value);
            }
        },
        page: {
            get() {
                return this.planos_filtro_pagina;
            },
            set(value) {
                return this.SET_PLANOS_FILTRO_PAGINA(value);
            }
        }
    },
    watch: {
        planos_reload() {
            this.carregarDados();
        },
    },
    methods: {
        ...mapMutations([
            'SET_PLANOS_ID_EDICAO',
            'SET_PLANOS_ID_EXCLUSAO',
            'SET_PLANOS_FILTRO_PESQUISA',
            'SET_PLANOS_FILTRO_PAGINA'
        ]),
        ...mapActions([
            'returnPlanos'
        ]),
        abrirEdicao(id) {
            this.SET_PLANOS_ID_EDICAO(id);
        },
        abrirExclusao(id) {
            this.SET_PLANOS_ID_EXCLUSAO(id);
        },
        pesquisar(){
            this.carregarDados();
        },
        updatePagina(page) {
            this.page = page;
            this.carregarDados();
        },
        carregarDados() {
            this.loading = true;
            this.returnPlanos().then((response) => {
                this.plans = response.data
            }).catch(()=>{

            }).finally(()=>{
                this.loading = false;
            })

        }
    },
    created() {
        this.carregarDados();
    }
}
</script>

<style scoped>
.box {
    padding: 30px;
    background: #FFFFFF;
    border-radius: 4px;
}

</style>
