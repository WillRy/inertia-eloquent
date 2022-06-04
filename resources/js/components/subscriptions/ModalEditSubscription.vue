<template>
    <BaseModal
        :aberta="matricula_id"
        @onOpen="carregarFormulario"
        @onClose="fecharModal"
    >
        <template #title>
            <h3>Edição de Matrícula</h3>
        </template>
        <template #body>
            <form action="" @submit.prevent="submit">
                <BaseSelect
                    label="Aluno"
                    placeholder="Pesquise o aluno"
                    v-model="form.student"
                    track-by="id"
                    text-by="name"
                    :options="alunos"
                    :internal-search="false"
                    :limit="3"
                    :disabled="true"
                ></BaseSelect>

                <BaseSelect
                    label="Plano"
                    placeholder="Pesquise o plano"
                    v-model="form.plan"
                    track-by="id"
                    text-by="name"
                    :options="planos"
                    :internal-search="false"
                    :limit="3"
                    :disabled="true"
                ></BaseSelect>

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
                Editar
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
import {mapMutations, mapState} from 'vuex';
import BaseSelect from "../forms/BaseSelect";

export default {
    name: "ModalEditSubscription",
    components: {BaseSelect, BaseInput, BaseDate, BaseModal},
    setup: () => ({v$: useVuelidate()}),
    data() {
        return {
            alunos: [],
            planos: [],
            form: {
                student: null,
                plan: null,
                dt_start: null
            },
            loading: false,
            loadingDados: false
        }
    },
    validations() {
        return {
            form: {
                dt_start: {required},
            }
        }
    },
    computed: {
        ...mapState({
            'matricula_id': 'matriculas_id_edicao'
        })
    },
    methods: {
        ...mapMutations([
            'SET_MATRICULAS_RELOAD',
            'SET_MATRICULAS_ID_EDICAO'
        ]),
        carregarFormulario() {
            this.loadingDados = true;
            axios.get(`/dashboard/subscriptions/${this.matricula_id}`).then((response) => {
                Object.assign(this.form, response.data.data);
            }).catch((error) => {
                this.$toast.open({
                    type: 'error',
                    message: 'Não foi possível exibir a matrícula'
                });
                this.fecharModal();
            }).finally(() => {
                this.loadingDados = false;
            });
        },
        fecharModal() {
            this.v$.$reset();
            this.SET_MATRICULAS_ID_EDICAO(null);
            this.$emit("onClose");
        },
        async submit() {
            const result = await this.v$.$validate();
            if (result) {
                this.loading = true;

                axios.put(`/dashboard/subscriptions/${this.matricula_id}`, this.form).then((response) => {
                    this.fecharModal();
                    this.$toast.open({
                        type: 'success',
                        message: 'Matrícula editada com sucesso'
                    });
                    //envia sinal de reload para outros componentes
                    //nao uso this.$emit pois as vezes a modal nao está no mesmo nivel
                    //no DOM, que os outros componentes
                    this.SET_MATRICULAS_RELOAD({
                        ...response.data.data,
                        tipo: 'edicao'
                    });
                }).catch((error) => {
                    this.$laravelError(error, 'Não foi possível editar a matrícula')
                }).finally(() => {
                    this.loading = false;
                })
            }
        }
    }
}
</script>

<style scoped>

</style>
