<template>
    <div>
        <PageHeader>
            <template #title>
                <h3>Alunos</h3>
            </template>
            <template #actions>
                <button class="btn btn-min btn-primary" @click="abrirCadastro">
                    Cadastrar
                </button>

                <BaseSelect
                    placeholder="Selecione o gênero"
                    :options="filtroSexo"
                    text-by="name"
                    track-by="id"
                    mb="0px"
                    v-model="genderField"
                    @change="reload(1)"
                />


                <BaseInput
                    type="text"
                    placeholder="Pesquisa por nome e email"
                    mb="0px"
                    @keyup.enter="reload(1)"
                    v-model="searchField"
                />
            </template>
        </PageHeader>
        <div class="total-container" v-if="total">
            <div class="total-box">
                <img src="/img/qtd.svg" alt="">
                Total: {{ total.qtd }}
            </div>
            <div class="total-box">
                <img src="/img/m.svg" alt="">
                Masculino: {{ total.m }}
            </div>
            <div class="total-box">
                <img src="/img/f.svg" alt="">
                Feminino: {{ total.f }}
            </div>
            <div class="total-box">
                <img src="/img/o.svg" alt="">
                Outros: {{ total.o }}
            </div>
        </div>
        <div class="content-table">
            <div class="tabela-container">
                <table :class="{loading: loading}" class="tabela">
                    <thead>
                    <tr>
                        <HeadSort
                            @onSort="sortBy('name')"
                            nome="name"
                            texto="Nome"
                            :order="sortOrder"
                            :ordenando="sortName"
                        />
                        <HeadSort
                            @onSort="sortBy('email')"
                            nome="email"
                            texto="Email"
                            :order="sortOrder"
                            :ordenando="sortName"
                        />
                        <HeadSort
                            @onSort="sortBy('gender')"
                            nome="gender"
                            texto="Gênero"
                            :order="sortOrder"
                            :ordenando="sortName"
                        />
                        <HeadSort
                            @onSort="sortBy('date_birth')"
                            nome="date_birth"
                            texto="Nascimento"
                            :order="sortOrder"
                            :ordenando="sortName"
                        />
                        <HeadSort
                            @onSort="sortBy('weight')"
                            nome="weight"
                            texto="Peso"
                            :order="sortOrder"
                            :ordenando="sortName"
                        />
                        <HeadSort
                            @onSort="sortBy('height')"
                            nome="height"
                            texto="Altura"
                            :order="sortOrder"
                            :ordenando="sortName"
                        />
                        <th>

                        </th>
                    </tr>
                    </thead>
                    <tbody v-if="students && students.data.length">
                    <tr v-for="(student,index) in students.data" :key="index">
                        <td>
                            <span class="item text-ellipsis">{{ student.name }}</span>
                        </td>
                        <td>
                            <span class="item text-ellipsis">
                                {{ student.email }}
                             </span>
                        </td>
                        <td>
                            <span class="item text-ellipsis">
                                {{ $filters.genero(student.gender) }}
                            </span>
                        </td>
                        <td>
                            <span class="item text-ellipsis">
                                {{ $filters.data(student.date_birth) }}
                            </span>
                        </td>
                        <td>
                            <span class="item text-ellipsis">
                                {{ student.weight }}
                            </span>
                        </td>
                        <td>
                            <span class="item text-ellipsis">
                                {{ student.height }}
                            </span>
                        </td>
                        <td class="coluna-acoes">
                            <DropdownAcoes>
                                <button @click="abrirEdicao(student.id)">
                                    editar
                                </button>
                                <button @click="abrirExclusao(student.id)">
                                    excluir
                                </button>
                            </DropdownAcoes>
                        </td>
                    </tr>
                    </tbody>
                    <tbody v-if="!loading && students && students.data.length === 0">
                    <tr>
                        <td colspan="7" style="text-align: center">Não há registros para esta pesquisa</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <PaginacaoSemRouter
                v-if="students"
                :pagina-atual="students.current_page"
                :total="students.total"
                :porPagina="students.per_page"
                @onChange="reload($event)"
            />
        </div>
        <ModalAddStudent
            :reload="['total','students','flash','errors']"
        />
        <ModalEditStudent :reload="['total','students','flash','errors']"/>
        <ModalRemoveStudent :reload="['total','students','flash','errors']"/>
    </div>
</template>

<script>
import Dashboard from "../../Layouts/Dashboard";
import PageHeader from "../../components/dashboard/PageHeader";
import BaseInput from "../../components/base/form/BaseInput";
import ModalAddStudent from "../../components/students/ModalAddStudent";
import ModalEditStudent from "../../components/students/ModalEditStudent";
import {mapMutations, mapState} from 'vuex'
import PaginacaoSemRouter from "../../components/base/paginacao/PaginacaoSemRouter";
import BaseSelect from "../../components/base/form/BaseSelect";
import ModalRemoveStudent from "../../components/students/ModalRemoveStudent";
import HeadSort from "../../components/base/datatables/HeadSort";
import DropdownAcoes from "../../components/base/tabela/DropdownAcoes";

export default {
    name: "Index",
    components: {
        DropdownAcoes,
        HeadSort,
        ModalRemoveStudent,
        BaseSelect,
        PaginacaoSemRouter,
        ModalAddStudent,
        ModalEditStudent,
        BaseInput,
        PageHeader
    },
    layout: Dashboard,
    props: ["students", "total", "filters", "page"],
    data() {
        return {
            searchField: this.filters.search,
            genderField: null,
            loading: false,
            filtroSexo: [
                {name: 'Todos', id: ''},
                {name: 'Masculino', id: 'm'},
                {name: 'Feminino', id: 'f'},
                {name: 'Outro', id: 'o'},
            ],
            sortName: this.filters.sortName,
            sortOrder: this.filters.sortOrder,
        }
    },
    watch: {
        "filters": {
            deep: true,
            handler() {
                this.normalizarFiltroGenero();
            }
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
            this.reload(1);
        },
        abrirCadastro() {
            this.$eventBus.$emit("ModalAddStudent:config", {});
        },
        abrirEdicao(id) {
            this.$eventBus.$emit("ModalEditStudent:config", {
                aluno_id: id
            });
        },
        abrirExclusao(id) {
            this.$eventBus.$emit("ModalRemoveStudent:config", {
                aluno_id: id
            });
        },
        normalizarFiltroGenero() {
            /** Normaliza o filtro ao carregar pagina, pois o filtro deve ser um objeto no campo de select **/
            this.genderField = this.filtroSexo.find((item) => item.id == this.filters.gender);
            this.sortName = this.filters.sortName;
            this.sortOrder = this.filters.sortOrder;
        },
        reload(page) {
            this.loading = true;

            this.$inertia.get("/dashboard/students", {
                page: page || this.page,
                ...(this.searchField ? {search: this.searchField} : {}),
                ...(this.genderField && this.genderField.id ? {gender: this.genderField.id} : {}),
                ...(this.sortName ? {sortName: this.sortName} : {}),
                ...(this.sortOrder ? {sortOrder: this.sortOrder} : {}),
            }, {
                only: ['total', 'students', 'flash', 'filters'],
                onSuccess: () => {
                    this.loading = false
                },
            });
        }
    },
    created() {
        this.normalizarFiltroGenero();
    }
}
</script>

<style scoped>

.total-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.total-box {
    background: #fff;
    border-radius: 4px;
    padding: 20px;
    display: flex;
    align-items: center;
    font-weight: bold;
}

.total-box img {
    width: 60px;
    margin-right: 5px;
}
</style>
