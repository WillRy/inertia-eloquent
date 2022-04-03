<template>
    <BaseModal
        :aberta="aberta"
        @onOpen="carregarFormulario"
        @onClose="fecharModal"
    >
        <template #title>
            <h3>Cadastro de Aluno</h3>
        </template>
        <template #body>
            <form action="" @submit.prevent="submit">
                <BaseInput
                    label="Nome completo"
                    type="text"
                    placeholder=""
                    v-model="form.name"
                    :class="{error: v$.form.name.$error}"
                >
                    <template #error v-if="v$.form.name.$error">
                        <div v-if="v$.form.name.required.$invalid" class="errorMessage">Informe o nome</div>
                    </template>
                </BaseInput>

                <BaseInput
                    label="E-mail"
                    type="email"
                    placeholder="exemplo@exemplo.com"
                    v-model="form.email"
                    :class="{error: v$.form.email.$error}"
                >
                    <template #error v-if="v$.form.email.$error">
                        <div v-if="v$.form.email.required.$invalid" class="errorMessage">Informe o email</div>
                    </template>
                </BaseInput>

                <BaseDate
                    label="Data de Nascimento"
                    v-model="form.date_birth"
                    :class="{error: v$.form.date_birth.$error}"
                >
                    <template #error v-if="v$.form.date_birth.$error">
                        <div v-if="v$.form.date_birth.required.$invalid" class="errorMessage">Informe a data de nascimento</div>
                    </template>
                 </BaseDate>

                <BaseInput
                    label="Peso (kg)"
                    type="text"
                    v-model="form.weight"
                    :class="{error: v$.form.weight.$error}"
                    v-mask="'?###'"
                >
                    <template #error v-if="v$.form.weight.$error">
                        <div v-if="v$.form.weight.required.$invalid" class="errorMessage">Informe o peso</div>
                    </template>
                </BaseInput>

                <BaseInput
                    label="Altura"
                    type="text"
                    v-model="form.height"
                    :class="{error: v$.form.height.$error}"
                    v-mask="'#.##'"
                >
                    <template #error v-if="v$.form.height.$error">
                        <div v-if="v$.form.height.required.$invalid" class="errorMessage">Informe a altura</div>
                    </template>
                </BaseInput>

                <BaseSelect
                    placeholder="Selecione o gênero"
                    label="Gênero"
                    :options="sexo"
                    text-by="name"
                    track-by="id"
                    v-model="form.gender"
                    :class="{error: v$.form.gender.$error}"
                >
                    <template #error v-if="v$.form.gender.$error">
                        <div v-if="v$.form.gender.required.$invalid" class="errorMessage">Informe o sexo</div>
                    </template>
                </BaseSelect>

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
import {required, email} from '@vuelidate/validators'
import axios from 'axios';
import {mapMutations} from 'vuex';
import BaseSelect from "../forms/BaseSelect";

export default {
    name: "ModalAddStudent",
    components: {BaseSelect, BaseInput, BaseDate, BaseModal},
    props: {
        errors: Object,
        aberta: Boolean
    },
    setup: () => ({v$: useVuelidate()}),
    data(){
        return {
            form: {
                name: '',
                email: '',
                date_birth: '',
                height: '',
                weight: '',
                gender: null
            },
            loading: false,
            sexo: [
                {name: 'Masculino', id: 'm'},
                {name: 'Feminino', id: 'f'},
                {name: 'Outro', id: 'o'},
            ],
        }
    },
    validations() {
        return {
            form: {
                name: {required},
                email: {required, email},
                date_birth: {required},
                height: {required},
                weight: {required},
                gender: {required}
            }
        }
    },
    methods: {
        ...mapMutations([
            'SET_ALUNOS_RELOAD'
        ]),
        carregarFormulario(){},
        fecharModal() {
            this.$emit("onClose");
        },
        async submit() {
            const result = await this.v$.$validate();
            if (result) {
                this.loading = true;

                let data = {
                    ...this.form,
                    date_birth: this.form.date_birth.toISOString().split('T')[0],
                    gender: this.form.gender.id
                }

                axios.post("/dashboard/students", data).then((response)=>{
                    this.fecharModal();
                    this.$toast.open({
                        type: 'success',
                        message: 'Aluno cadastrado com sucesso'
                    });
                    //envia sinal de reload para outros componentes
                    this.SET_ALUNOS_RELOAD(response.data);
                }).catch((error)=>{
                    this.$laravelError(error, 'Não foi possível cadastrar o aluno')
                }).finally(()=>{
                    this.loading = false;
                })
            }
        }
    }
}
</script>

<style scoped>

</style>
