<template>
    <BaseModal
        :aberta="plano_id"
        @onOpen="carregarFormulario"
        @onClose="fecharModal"
    >
        <template #title>
            <h3>Edição de Plano</h3>
        </template>
        <template #body>
            <form action="" @submit.prevent="submit" v-if="!loadingDados">
                <BaseInput
                    label="Nome"
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
                    label="Preço"
                    type="text"
                    v-mask="'###.##'"
                    v-model="form.price"
                    :class="{error: v$.form.price.$error}"
                >
                    <template #error v-if="v$.form.price.$error">
                        <div v-if="v$.form.price.required.$invalid" class="errorMessage">Informe o preço</div>
                    </template>
                </BaseInput>

                <BaseInput
                    label="Duração (Meses)"
                    type="number"
                    min="1"
                    v-model="form.duration"
                    :class="{error: v$.form.duration.$error}"
                >
                    <template #error v-if="v$.form.duration.$error">
                        <div v-if="v$.form.duration.required.$invalid" class="errorMessage">Informe a duração</div>
                    </template>
                </BaseInput>

            </form>
            <Loader height="80px" width="80px" v-if="loadingDados" fill="#6d74ed"/>
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
    name: "Plan",
    components: {BaseSelect, BaseInput, BaseDate, BaseModal},
    props: {
        aberta: Boolean
    },
    setup: () => ({v$: useVuelidate()}),
    data(){
        return {
            form: {
                name: '',
                price: '',
                duration: '',
            },
            loading: false,
            loadingDados: false
        }
    },
    validations() {
        return {
            form: {
                name: {required},
                price: {required},
                duration: {required},
            }
        }
    },
    computed:{
        ...mapState({
            'plano_id': 'planos_id_edicao'
        })
    },
    methods: {
        ...mapMutations([
            'SET_PLANOS_RELOAD',
            'SET_PLANOS_ID_EDICAO'
        ]),
        carregarFormulario(){
            this.loadingDados = true;
            axios.get(`/dashboard/plans/${this.plano_id}`).then((response)=>{
                // this.plano = response.data.data;
                Object.assign(this.form,response.data.data)
            }).catch(()=>{
                this.$toast.open({
                    type: 'error',
                    message: 'Não foi possível exibir o plano!'
                });
                this.fecharModal();
            }).finally(()=>{
                this.loadingDados = false;
            })
        },
        fecharModal() {
            this.v$.$reset();
            this.SET_PLANOS_ID_EDICAO(null);
            this.$emit("onClose");
        },
        async submit() {
            const result = await this.v$.$validate();
            if (result) {
                this.loading = true;

                axios.put(`/dashboard/plans/${this.plano_id}`, this.form).then((response)=>{
                    this.fecharModal();
                    this.$toast.open({
                        type: 'success',
                        message: 'Plano editado com sucesso'
                    });
                    //envia sinal de reload para outros componentes
                    this.SET_PLANOS_RELOAD(response.data);
                }).catch((error)=>{
                    this.$laravelError(error, 'Não foi possível editar o plano')
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
