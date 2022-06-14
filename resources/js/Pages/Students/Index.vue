<template>
    <div>
        <PageHeader>
            <template #title>
                <h3>Alunos</h3>
            </template>
            <template #actions>
                <button class="btn btn-min btn-primary" @click="abrirModalCadastro = true">
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
        <div class="box">
            <table :class="{loading: loading}">
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
                    <th>Ações</th>
                </tr>
                </thead>
                <tbody v-if="students && students.data.length">
                <tr v-for="(student,index) in students.data" :key="index">
                    <td>{{ student.name }}</td>
                    <td>{{ student.email }}</td>
                    <td>{{ $filters.genero(student.gender) }}</td>
                    <td>{{ $filters.data(student.date_birth) }}</td>
                    <td>{{ student.weight }}</td>
                    <td>{{ student.height }}</td>
                    <td>
                        <button class="link-action link-action-primary" @click="abrirEdicao(student.id)">
                            editar
                        </button>
                        <button class="link-action link-action-danger" @click="abrirExclusao(student.id)">
                            excluir
                        </button>
                    </td>
                </tr>
                </tbody>
                <tbody v-if="!loading && students && students.data.length === 0">
                <tr>
                    <td colspan="7" style="text-align: center">Não há registros para esta pesquisa</td>
                </tr>
                </tbody>
            </table>
            <PaginacaoSemRouter
                v-if="students"
                :pagina-atual="students.current_page"
                :total="students.total"
                :porPagina="students.per_page"
                @onChange="reload($event)"
            />
        </div>
        <ModalAddStudent
            :aberta="abrirModalCadastro"
            @onClose="abrirModalCadastro = false"
            :reload="['total','students','flash']"
        />
        <ModalEditStudent :reload="['total','students','flash']"/>
        <ModalRemoveStudent :reload="['total','students','flash']"/>
    </div>
</template>

<script>
import Dashboard from "../../Layouts/Dashboard";
import PageHeader from "../../components/dashboard/PageHeader";
import BaseInput from "../../components/forms/BaseInput";
import ModalAddStudent from "../../components/students/ModalAddStudent";
import ModalEditStudent from "../../components/students/ModalEditStudent";
import {mapMutations, mapState} from 'vuex'
import PaginacaoSemRouter from "../../components/paginacao/PaginacaoSemRouter";
import BaseSelect from "../../components/forms/BaseSelect";
import ModalRemoveStudent from "../../components/students/ModalRemoveStudent";
import HeadSort from "../../components/datatables/HeadSort";

export default {
    name: "Index",
    components: {
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
            abrirModalCadastro: false,
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
    computed: {
        ...mapState({
            'alunos_reload': 'alunos_reload'
        }),
    },
    watch: {
        alunos_reload() {
            this.reload();
        },
        "filters.gender": function () {
            this.normalizarFiltroGenero();
        }
    },
    methods: {
        ...mapMutations([
            'SET_ALUNOS_ID_EDICAO',
            'SET_ALUNOS_ID_EXCLUSAO',
        ]),
        sortBy(campo) {
            this.sortName = campo;
            if (this.sortName !== campo) {
                this.sortOrder = "asc";
            } else {
                this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc';
            }
            this.reload(1);
        },
        abrirEdicao(id) {
            this.SET_ALUNOS_ID_EDICAO(id);
        },
        abrirExclusao(id) {
            this.SET_ALUNOS_ID_EXCLUSAO(id);
        },
        normalizarFiltroGenero() {
            /** Normaliza o filtro ao carregar pagina, pois o filtro deve ser um objeto no campo de select **/
            this.genderField = this.filtroSexo.find((item) => item.id == this.filters.gender);
        },
        reload(page) {
            this.loading = true;

            this.$inertia.get("/dashboard/students", {
                page: page || this.page,
                ...(this.searchField ? {search: this.searchField} : {}),
                ...(this.genderField && this.genderField.id ? {gender: this.genderField.id} : {}),
                ...(this.sortName ? {sortName: this.sortName || 'id'} : {}),
                ...(this.sortOrder ? {sortOrder: this.sortOrder || 'asc'} : {}),
            }, {
                only: ['total','students','flash'],
                preserveState: true,
                onSuccess: () => {
                    this.loading = false
                },
            });
        }
    },
    created() {
        console.log("created executou");
        this.normalizarFiltroGenero();
    }
}
</script>

<style scoped>
.box {
    padding: 30px;
    background: #FFFFFF;
    border-radius: 4px;
    overflow-x: auto;
}

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
