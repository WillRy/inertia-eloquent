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
                    placeholder="Selecione o sexo"
                    :options="filtroSexo"
                    width="240px"
                    text-by="name"
                    track-by="id"
                    mb="0px"
                    v-model="genderField"
                    @change="reload(1)"
                />


                <BaseInput
                    type="text"
                    placeholder="Pesquisa por nome e email"
                    width="240px"
                    mb="0px"
                    @keyup.enter="reload(1)"
                    v-model="searchField"
                />
            </template>
        </PageHeader>
        <div class="box">
            <table :class="{loading: loading}">
                <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Gênero</th>
                    <th>Nascimento</th>
                    <th>Peso</th>
                    <th>Altura</th>
                    <th>Ações</th>
                </tr>
                </thead>
                <tbody>
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
        />
        <ModalEditStudent/>
        <ModalRemoveStudent/>
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

export default {
    name: "Index",
    components: {
        ModalRemoveStudent,
        BaseSelect,
        PaginacaoSemRouter,
        ModalAddStudent,
        ModalEditStudent,
        BaseInput,
        PageHeader
    },
    layout: Dashboard,
    props: ["students", "filters", "page"],
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
            this.$inertia.get('/dashboard/students', {
                page: page || this.page,
                ...(this.searchField ? {search: this.searchField} : {}),
                ...(this.genderField && this.genderField.id ? {gender: this.genderField.id} : {}),
            }, {
                preservesState: true,
                onStart: () => {
                    this.loading = true;
                },
                onFinish: () => {
                    this.loading = false;
                }
            });
        }
    },
    created() {
        this.normalizarFiltroGenero();
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
