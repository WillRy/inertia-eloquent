<template>
    <BaseModal
        :aberta="aberta"
        @onOpen="carregarFormulario"
        @onClose="fecharModal"
    >
        <template #title>
            <h3>Cadastro de Matrícula</h3>
        </template>
        <template #body>
            <form action="" @submit.prevent="submit">
                <BaseSelect
                    label="Aluno"
                    placeholder="Pesquise o aluno"
                    v-model="form.aluno"
                    :class="{error: v$.form.aluno.$error}"
                    track-by="id"
                    text-by="name"
                    :options="alunos"
                    :internal-search="false"
                    :limit="3"
                    @search-change="pesquisarAlunos"
                >
                    <template v-slot:error>
                        <div v-if="v$.form.aluno.$error">
                            <p v-if="v$.form.aluno.required.$invalid" class="errorMessage">Informe o aluno</p>
                        </div>
                    </template>
                </BaseSelect>

                <BaseSelect
                    label="Plano"
                    placeholder="Pesquise o plano"
                    v-model="form.plano"
                    :class="{error: v$.form.plano.$error}"
                    track-by="id"
                    text-by="name"
                    :options="planos"
                    :internal-search="false"
                    :limit="3"
                    @search-change="pesquisarPlanos"
                >
                    <template v-slot:error>
                        <div v-if="v$.form.plano.$error">
                            <p v-if="v$.form.plano.required.$invalid" class="errorMessage">Informe o plano</p>
                        </div>
                    </template>
                </BaseSelect>

                <BaseDate
                    label="Data de Início"
                    v-model:formatado="form.dt_start"
                >
                    <template v-slot:error>
                        <div v-if="v$.form.dt_start.$error">
                            <p v-if="v$.form.dt_start.required.$invalid" class="errorMessage">Informe o início</p>
                        </div>
                    </template>
                </BaseDate>


            </form>
        </template>
        <template #footer>
            <button class="btn btn-full btn-primary" @click.prevent="submit">
                <Loader v-if="loading" width="20px" height="20px"/>
                Cadastrar
            </button>
            <button class="btn btn-full btn-secondary" @click.prevent="fecharModal">
                Cancelar
            </button>
        </template>
    </BaseModal>
</template>

<script>
import BaseModal from "../modal/BaseModal";
import BaseInput from "../forms/BaseInput";
import BaseDate from "../forms/BaseDate";
import useVuelidate from '@vuelidate/core'
import {required} from '@vuelidate/validators'
import axios from 'axios';
import {mapMutations} from 'vuex';
import BaseSelect from "../forms/BaseSelect";

export default {
    name: "ModalAddSubscription",
    components: {BaseSelect, BaseInput, BaseDate, BaseModal},
    props: {
        aberta: Boolean
    },
    setup: () => ({v$: useVuelidate()}),
    data() {
        return {
            alunos: [],
            planos: [],
            form: {
                aluno: null,
                plano: null,
                dt_start: null
            },
            loading: false,
        }
    },
    validations() {
        return {
            form: {
                aluno: {required},
                plano: {required},
                dt_start: {required},
            }
        }
    },
    methods: {
        ...mapMutations([
            'SET_MATRICULAS_RELOAD'
        ]),
        carregarFormulario() {
            this.form = {
                aluno: null,
                plano: null,
                dt_start: new Date()
            };
        },
        fecharModal() {
            this.v$.$reset();
            this.$emit("onClose");
        },
        pesquisarAlunos(pesquisa) {
            axios.get("/dashboard/students/list", {params: {search: pesquisa}}).then((response) => {
                this.alunos = response.data.data;
            });
        },
        pesquisarPlanos(pesquisa) {
            axios.get("/dashboard/plans/list", {params: {search: pesquisa}}).then((response) => {
                this.planos = response.data.data;
            });
        },
        async submit() {
            const result = await this.v$.$validate();
            if (result) {
                this.loading = true;

                let dados = {
                    ...this.form,
                    student_id: this.form.aluno ? this.form.aluno.id : '',
                    plan_id: this.form.plano ? this.form.plano.id : '',
                }

                axios.post("/dashboard/subscriptions", dados).then((response) => {
                    this.fecharModal();
                    this.$toast.open({
                        type: 'success',
                        message: 'Matrícula cadastrada com sucesso'
                    });
                    //envia sinal de reload para outros componentes
                    //nao uso this.$emit pois as vezes a modal nao está no mesmo nivel
                    //no DOM, que os outros componentes
                    this.SET_MATRICULAS_RELOAD({
                        ...response.data.data,
                        tipo: 'criacao'
                    });
                }).catch((error) => {
                    this.$laravelError(error, 'Não foi possível cadastrar a matrícula')
                }).finally(() => {
                    this.loading = false;
                });
            }
        }
    }
}
</script>

<style scoped>

</style>
