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
            <p v-if="plano && !loadingDados" class="modal-description">Deseja excluir o plano {{plano.name}}?</p>
            <Loader height="80px" width="80px" v-if="loadingDados" fill="#6d74ed"/>
        </template>
        <template #footer>
            <button class="btn btn-full btn-primary" @click.prevent="submit">
                <Loader v-if="loading" width="20px" height="20px"/>
                Excluir
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
            plano: null,
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
            'plano_id': 'planos_id_exclusao'
        })
    },
    methods: {
        ...mapMutations([
            'SET_PLANOS_RELOAD',
            'SET_PLANOS_ID_EXCLUSAO'
        ]),
        carregarFormulario(){
            this.loadingDados = true;
            axios.get(`/dashboard/plans/${this.plano_id}`).then((response)=>{
                this.plano = response.data.data;
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
            this.SET_PLANOS_ID_EXCLUSAO(null);
            this.$emit("onClose");
        },
        async submit() {
            this.loading = true;
            axios.delete(`/dashboard/plans/${this.plano_id}`).then((response)=>{
                this.fecharModal();
                this.$toast.open({
                    type: 'success',
                    message: 'Plano excluido com sucesso'
                });
                //envia sinal de reload para outros componentes
                this.SET_PLANOS_RELOAD(response.data);
            }).catch((error)=>{
                this.$laravelError(error, 'Não foi possível excluir o plano')
            }).finally(()=>{
                this.loading = false;
            })
        }
    }
}
</script>

<style scoped>

</style>
