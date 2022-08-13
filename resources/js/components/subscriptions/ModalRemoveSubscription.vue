<template>
    <BaseModal
        :aberta="config"
        @onOpen="carregarFormulario"
        @onClose="fecharModal"
    >
        <template #title>
            <h3>Exclusão de Matrícula</h3>
        </template>
        <template #body>
            <p v-if="subscription && !loadingDados" class="modal-description">Deseja excluir a matrícula do aluno
                {{ subscription.student.name }}?</p>
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
import BaseInput from "../base/form/BaseInput";
import BaseDate from "../base/form/BaseDate";
import axios from 'axios';
import {mapMutations, mapState} from 'vuex';
import BaseSelect from "../base/form/BaseSelect";

export default {
    name: "ModalRemoveSubscription",
    components: {BaseSelect, BaseInput, BaseDate, BaseModal},
    data() {
        return {
            config: null,
            subscription: null,
            loading: false,
            loadingDados: false
        }
    },
    computed: {},
    methods: {
        carregarFormulario() {
            this.loadingDados = true;
            axios.get(`/dashboard/subscriptions/${this.config.id}`).then((response) => {
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
            this.config = null;
            this.$emit("onClose");
        },
        async submit() {
            this.loading = true;
            this.$inertia.delete(`/dashboard/subscriptions/${this.config.id}`, {
                only: this.reload,
                onSuccess: () => {
                    this.fecharModal();
                    this.$eventBus.$emit("ModalRemoveSubscription:reload", {
                        ...this.config,
                        dados: this.subscription
                    });
                },
                onFinish: () => {
                    this.loading = false;
                }
            })
        }
    },
    beforeUnmount() {
        this.$eventBus.$off("ModalRemoveSubscription:config");
    },
    created() {
        this.$eventBus.$on("ModalRemoveSubscription:config", (evento) => {
            this.config = evento;
        });
    },
}
</script>

<style scoped>

</style>
