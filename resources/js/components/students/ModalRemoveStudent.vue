<template>
    <BaseModal
        :aberta="config"
        @onOpen="carregarFormulario"
        @onClose="fecharModal"
    >
        <template #title>
            <h3>Exclusão de Aluno</h3>
        </template>
        <template #body>
            <p class="modal-description" v-if="student && !loadingDados">Deseja excluir o aluno {{ student.name }}?</p>
            <Loader height="80px" width="80px" v-if="loadingDados" :cor-principal="true"/>
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
import BaseModal from "../base/modal/BaseModal";
import axios from 'axios';
import {mapMutations, mapState} from 'vuex';

export default {
    name: "ModalRemoveStudent",
    components: {BaseModal},
    data() {
        return {
            config: null,
            student: null,
            loading: false,
            loadingDados: false,
        }
    },
    computed: {},
    methods: {
        carregarFormulario() {
            this.loadingDados = true;
            axios.get(`/dashboard/students/${this.config.aluno_id}`).then((response) => {
                this.student = response.data.data;
            }).catch((e) => {
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
            this.config = null;
            this.$emit("onClose");
        },
        async submit() {
            this.loading = true;
            this.$inertia.delete(`/dashboard/students/${this.config.aluno_id}`, {
                only: this.reload,
                onSuccess: () => {
                    this.fecharModal();
                    this.$eventBus.$emit("ModalRemoveStudent:reload", this.config);
                },
                onFinish: () => {
                    this.loading = false;
                }
            })
        }
    },
    beforeUnmount() {
        this.$eventBus.$off("ModalRemoveStudent:config");
    },
    created() {
        this.$eventBus.$on("ModalRemoveStudent:config", (evento) => {
            this.config = evento;
        });
    },
}
</script>

<style scoped>

</style>
