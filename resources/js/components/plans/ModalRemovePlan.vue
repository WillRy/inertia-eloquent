<template>
    <BaseModal
        :aberta="config"
        @onOpen="carregarFormulario"
        @onClose="fecharModal"
    >
        <template #title>
            <h3>Edição de Plano</h3>
        </template>
        <template #body>
            <p v-if="plano && !loadingDados" class="modal-description">
                Deseja excluir o plano {{ plano.name }}?
            </p>
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
import useVuelidate from '@vuelidate/core'
import axios from 'axios';
import {mapMutations, mapState} from 'vuex';
import BaseSelect from "../base/form/BaseSelect";

export default {
    name: "ModalRemovePlan",
    components: {BaseSelect, BaseInput, BaseModal},
    data() {
        return {
            config: null,
            plano: null,
            loading: false,
            loadingDados: false
        }
    },
    methods: {
        carregarFormulario() {
            this.loadingDados = true;
            axios.get(`/dashboard/plans/${this.config.plano_id}`).then((response) => {
                this.plano = response.data.data;
            }).catch(() => {
                this.$toast.open({
                    type: 'error',
                    message: 'Não foi possível exibir o plano!'
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
            this.$inertia.delete(`/dashboard/plans/${this.config.plano_id}`, {
                only: this.reload,
                onSuccess: () => {
                    this.fecharModal();
                    this.$eventBus.$emit("ModalRemovePlan:reload", this.config);
                },
                onFinish: () => {
                    this.loading = false;
                }
            })
        }
    },
    beforeUnmount() {
        this.$eventBus.$off("ModalRemovePlan:config");
    },
    created() {
        this.$eventBus.$on("ModalRemovePlan:config", (evento) => {
            this.config = evento;
        });
    },
}
</script>

<style scoped>

</style>
