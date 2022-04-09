<template>
    <BaseModal
        :aberta="matricula_id"
        @onOpen="carregarFormulario"
        @onClose="fecharModal"
    >
        <template #title>
            <h3>Exclusão de Matrícula</h3>
        </template>
        <template #body>
            <p v-if="subscription && !loadingDados" class="modal-description">Deseja excluir a matrícula do aluno
                {{ subscription.student.name }}?</p>
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
import axios from 'axios';
import {mapMutations, mapState} from 'vuex';
import BaseSelect from "../forms/BaseSelect";

export default {
    name: "ModalRemoveSubscription",
    components: {BaseSelect, BaseInput, BaseDate, BaseModal},
    data() {
        return {
            subscription: null,
            loading: false,
            loadingDados: false
        }
    },
    computed: {
        ...mapState({
            'matricula_id': 'matriculas_id_exclusao'
        })
    },
    methods: {
        ...mapMutations([
            'SET_MATRICULAS_ID_EXCLUSAO',
            'SET_MATRICULAS_RELOAD'
        ]),
        carregarFormulario() {
            this.loadingDados = true;
            axios.get(`/dashboard/subscriptions/${this.matricula_id}`).then((response) => {
                this.subscription = response.data.data;
            }).catch(() => {
                this.$toast.open({
                    type: 'error',
                    message: 'Não foi possível exibir a matrícula!'
                });
                this.fecharModal();
            }).finally(() => {
                this.loadingDados = false;
            })
        },
        fecharModal() {
            this.SET_MATRICULAS_ID_EXCLUSAO(null);
            this.$emit("onClose");
        },
        async submit() {
            this.loading = true;
            axios.delete(`/dashboard/subscriptions/${this.matricula_id}`).then((response) => {
                this.fecharModal();
                this.$toast.open({
                    type: 'success',
                    message: 'Matrícula excluido com sucesso'
                });
                //envia sinal de reload para outros componentes
                this.SET_MATRICULAS_RELOAD({
                    ...this.subscription,
                    tipo: 'exclusao'
                });
            }).catch((error) => {
                this.$laravelError(error, 'Não foi possível excluir a matrícula')
            }).finally(() => {
                this.loading = false;
            });
        }
    }
}
</script>

<style scoped>

</style>
