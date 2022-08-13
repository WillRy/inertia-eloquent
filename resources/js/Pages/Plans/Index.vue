<template>
    <div>
        <PageHeader>
            <template #title>
                <h3>Planos</h3>
            </template>
            <template #actions>
                <button class="btn btn-min btn-primary" @click="abrirCadastro">
                    Cadastrar
                </button>

                <BaseInput
                    type="text"
                    placeholder="Pesquisa por nome ou valor"
                    mb="0px"
                    @keyup.enter="carregarDados(1)"
                    v-model="search"
                />
            </template>
        </PageHeader>
        <div class="content-table">
            <div class="tabela-container">
                <table :class="{loading: loading}" class="tabela">
                    <thead>
                    <tr>
                        <HeadSort
                            style="width: 500px"
                            @onSort="sortBy('name')"
                            nome="name"
                            texto="Nome"
                            :order="sortOrder"
                            :ordenando="sortName"
                        />
                        <HeadSort
                            style="width: 200px"
                            @onSort="sortBy('price')"
                            nome="price"
                            texto="Preço"
                            :order="sortOrder"
                            :ordenando="sortName"
                        />
                        <HeadSort
                            style="width: 200px"
                            @onSort="sortBy('duration')"
                            nome="duration"
                            texto="Periodo (em mêses)"
                            :order="sortOrder"
                            :ordenando="sortName"
                        />
                    </tr>
                    </thead>
                    <tbody v-if="plans && plans.data">
                    <tr v-for="(plan,index) in plans.data" :key="index">
                        <td>
                            <span class="item text-ellipsis">
                                {{ plan.name }}
                            </span>
                        </td>
                        <td>
                            <span class="item text-ellipsis">
                                {{ $filters.dinheiro(plan.price) }}
                            </span>
                        </td>
                        <td>
                            <span class="item text-ellipsis">
                                {{ plan.duration }}
                            </span>
                        </td>

                        <td class="coluna-acoes">
                            <DropdownAcoes>
                                <button @click="abrirEdicao(plan.id)">
                                    editar
                                </button>
                                <button @click="abrirExclusao(plan.id)">
                                    excluir
                                </button>
                            </DropdownAcoes>
                        </td>
                    </tr>
                    </tbody>
                    <tbody v-if="!loading && plans.data.length === 0">
                    <tr>
                        <td colspan="7" style="text-align: center">Não há registros para esta pesquisa</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <PaginacaoSemRouter
                v-if="plans"
                :pagina-atual="plans.current_page"
                :total="plans.total"
                :porPagina="plans.per_page"
                @onChange="updatePagina($event)"
            />
        </div>

        <ModalAddPlan/>
        <ModalEditPlan/>
        <ModalRemovePlan/>

    </div>
</template>

<script>
import Dashboard from "../../Layouts/Dashboard";
import PageHeader from "../../components/dashboard/PageHeader";
import BaseInput from "../../components/base/form/BaseInput";
import {mapActions, mapMutations, mapState} from 'vuex'
import PaginacaoSemRouter from "../../components/base/paginacao/PaginacaoSemRouter";
import BaseSelect from "../../components/base/form/BaseSelect";
import ModalAddPlan from '../../components/plans/ModalAddPlan';
import ModalEditPlan from '../../components/plans/ModalEditPlan';
import ModalRemovePlan from '../../components/plans/ModalRemovePlan';
import axios from "axios";
import HeadSort from "../../components/base/datatables/HeadSort";
import DropdownAcoes from "../../components/base/tabela/DropdownAcoes";

export default {
    name: "Index",
    components: {
        DropdownAcoes,
        HeadSort,
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
            loading: false,
            plans: null,
            sortName: "id",
            sortOrder: "asc",
            search: "",
            page: 1
        }
    },
    methods: {
        sortBy(campo) {
            this.sortName = campo;
            if (this.sortName !== campo) {
                this.sortOrder = "asc";
            } else {
                this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc';
            }
            this.page = 1;
            this.carregarDados();
        },
        abrirCadastro() {
            this.$eventBus.$emit("ModalAddPlan:config", {});
        },
        abrirEdicao(id) {
            this.$eventBus.$emit("ModalEditPlan:config", {
                plano_id: id
            });
        },
        abrirExclusao(id) {
            this.$eventBus.$emit("ModalRemovePlan:config", {
                plano_id: id
            });
        },
        updatePagina(page) {
            this.page = page;
            this.carregarDados();
        },
        carregarDados() {
            this.loading = true;
            /** retorna promise**/
            return axios.get("/dashboard/plans/list", {
                params: {
                    ...(this.search ? {search: this.search || ''} : {}),
                    ...(this.page ? {page: this.page || 1} : {}),
                    ...(this.sortName ? {sortName: this.sortName || 'id'} : {}),
                    ...(this.sortOrder ? {sortOrder: this.sortOrder || 'asc'} : {}),
                }
            }).then((response) => {
                this.plans = response.data.data;
            }).catch(() => {

            }).finally(() => {
                this.loading = false;
            });

        }
    },
    beforeUnmount() {
        this.$eventBus.$off("ModalAddPlan:reload");
        this.$eventBus.$off("ModalEditPlan:reload");
        this.$eventBus.$off("ModalRemovePlan:reload");
    },
    created() {
        this.carregarDados();

        this.$eventBus.$on("ModalAddPlan:reload", () => {
            this.carregarDados();
        });

        this.$eventBus.$on("ModalEditPlan:reload", () => {
            this.carregarDados();
        });

        this.$eventBus.$on("ModalRemovePlan:reload", () => {
            this.carregarDados();
        });
    }
}
</script>

<style scoped>
</style>
