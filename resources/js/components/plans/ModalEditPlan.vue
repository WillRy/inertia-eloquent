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
            <form action="" @submit.prevent="submit" v-if="!loadingDados">
                <BaseInput
                    label="Nome"
                    type="text"
                    placeholder=""
                    v-model="form.name"
                    :class="{error: form.errors.name}"
                    :error="form.errors.name"
                ></BaseInput>

                <BaseInput
                    label="Preço"
                    type="text"
                    v-mask="'###.##'"
                    v-model="form.price"
                    :class="{error: form.errors.price}"
                    :error="form.errors.price"
                ></BaseInput>

                <BaseInput
                    label="Duração (Meses)"
                    type="number"
                    min="1"
                    v-model="form.duration"
                    :class="{error: form.errors.duration}"
                    :error="form.errors.duration"
                ></BaseInput>

            </form>
            <Loader height="80px" width="80px" v-if="loadingDados" :cor-principal="true"/>
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
import BaseModal from "../base/modal/BaseModal";
import BaseInput from "../base/form/BaseInput";
import useVuelidate from '@vuelidate/core'
import {required} from '@vuelidate/validators'
import axios from 'axios';
import {mapMutations, mapState} from 'vuex';
import BaseSelect from "../base/form/BaseSelect";
import {useForm} from "@inertiajs/inertia-vue3";

export default {
    name: "ModalEditPlan",
    components: {BaseSelect, BaseInput, BaseModal},
    setup() {
        const form = useForm({
            name: '',
            price: '',
            duration: '',
        });
        return {form};
    },
    data() {
        return {
            config: null,
            loading: false,
            loadingDados: false,
        }
    },

    computed: {},
    methods: {
        carregarFormulario() {
            this.loadingDados = true;
            axios.get(`/dashboard/plans/${this.config.plano_id}`).then((response) => {
                // this.plano = response.data.data;
                Object.assign(this.form, response.data.data)
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
            this.form.reset();
            this.form.clearErrors();
            this.$emit("onClose");
        },
        async submit() {
            this.loading = true;
            this.form
                .put(`/dashboard/plans/${this.config.plano_id}`, {
                    only: this.reload,
                    onSuccess: () => {
                        this.fecharModal();
                        this.loading = false;
                        this.$eventBus.$emit("ModalEditPlan:reload", this.config);
                    },
                    onError: () => {
                        this.loading = false;
                    }
                });
        }
    },
    beforeUnmount() {
        this.$eventBus.$off("ModalEditPlan:config");
    },
    created() {
        this.$eventBus.$on("ModalEditPlan:config", (evento) => {
            this.config = evento;
        });
    },
}
</script>

<style scoped>

</style>
