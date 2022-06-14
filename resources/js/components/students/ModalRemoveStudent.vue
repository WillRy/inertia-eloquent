<template>
    <BaseModal
        :aberta="aluno_id"
        @onOpen="carregarFormulario"
        @onClose="fecharModal"
    >
        <template #title>
            <h3>Exclusão de Aluno</h3>
        </template>
        <template #body>
            <p class="modal-description" v-if="student && !loadingDados">Deseja excluir o aluno {{ student.name }}?</p>
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
import axios from 'axios';
import {mapMutations, mapState} from 'vuex';

export default {
    name: "ModalRemoveStudent",
    components: {BaseModal},
    props: ['reload'],
    data() {
        return {
            student: null,
            loading: false,
            loadingDados: false,
        }
    },
    computed: {
        ...mapState({
            'aluno_id': 'alunos_id_exclusao'
        })
    },
    methods: {
        ...mapMutations([
            'SET_ALUNOS_RELOAD',
            'SET_ALUNOS_ID_EXCLUSAO'
        ]),
        carregarFormulario() {
            this.loadingDados = true;
            axios.get(`/dashboard/students/${this.aluno_id}`).then((response) => {
                this.student = response.data.data;
            }).catch((e) => {
                console.log(e)
                this.$toast.open({
                    type: 'error',
                    message: 'Não foi possível exibir o aluno!'
                });
                this.fecharModal();
            }).finally(() => {
                this.loadingDados = false;
            })
        },
        fecharModal() {
            this.$emit("onClose");
            this.SET_ALUNOS_ID_EXCLUSAO(null);
        },
        async submit() {
            this.loading = true;
            this.$inertia.delete(`/dashboard/students/${this.aluno_id}`, {
                only: this.reload,
                onSuccess: () => {
                    this.fecharModal();
                },
                onFinish: () => {
                    this.loading = false;
                }
            })
            // axios.delete(`/dashboard/students/${this.aluno_id}`).then((response) => {
            //     this.fecharModal();
            //     this.$toast.open({
            //         type: 'success',
            //         message: 'Aluno excluido com sucesso'
            //     });
            //     //envia sinal de reload para outros componentes
            //     this.SET_ALUNOS_RELOAD(response.data);
            // }).catch((error) => {
            //     this.$laravelError(error, 'Não foi possível excluir o aluno')
            // }).finally(() => {
            //     this.loading = false;
            // })
        }
    }
}
</script>

<style scoped>

</style>
