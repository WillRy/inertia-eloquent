<template>
    <BaseModal
        :aberta="config"
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
                    v-model="form.student_id"
                    track-by="id"
                    text-by="name"
                    :options="alunos"
                    :internal-search="false"
                    :limit="3"
                    :disabled="true"
                    :class="{error: form.errors.student_id}"
                    :error="form.errors.student_id"
                ></BaseSelect>

                <BaseSelect
                    label="Plano"
                    placeholder="Pesquise o plano"
                    v-model="form.plan_id"
                    track-by="id"
                    text-by="name"
                    :options="planos"
                    :internal-search="false"
                    :limit="3"
                    :disabled="true"
                    :class="{error: form.errors.plan_id}"
                    :error="form.errors.plan_id"
                ></BaseSelect>

                <BaseDate
                    label="Data de Início"
                    v-model:formatado="form.dt_start"
                    :class="{error: form.errors.dt_start}"
                    :error="form.errors.dt_start"
                ></BaseDate>


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
import BaseModal from "../base/modal/BaseModal";
import BaseInput from "../base/form/BaseInput";
import BaseDate from "../base/form/BaseDate";
import useVuelidate from '@vuelidate/core'
import {required} from '@vuelidate/validators'
import axios from 'axios';
import {mapMutations, mapState} from 'vuex';
import BaseSelect from "../base/form/BaseSelect";
import {useForm} from "@inertiajs/inertia-vue3";

export default {
    name: "ModalEditSubscription",
    components: {BaseSelect, BaseInput, BaseDate, BaseModal},
    setup() {
        const form = useForm({
            student_id: null,
            plan_id: null,
            dt_start: ''
        });
        return {form};
    },
    data() {
        return {
            alunos: [],
            planos: [],
            config: null,
            loading: false,
            loadingDados: false
        }
    },
    methods: {
        carregarFormulario() {
            this.loadingDados = true;
            axios.get(`/dashboard/subscriptions/${this.config.id}`).then((response) => {
                Object.assign(this.form, response.data.data);
                this.form.student_id = response.data.data.student;
                this.form.plan_id = response.data.data.plan;
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
            this.config = null;
            this.form.reset();
            this.form.clearErrors();
            this.$emit("onClose");
        },
        async submit() {
            this.loading = true;
            this.form
                .transform((data) => {
                    return {
                        ...data,
                        student_id: this.form.student_id ? this.form.student_id.id : null,
                        plan_id: this.form.plan_id ? this.form.plan_id.id : null,
                    }
                })
                .put(`/dashboard/subscriptions/${this.config.id}`, {
                    onSuccess: () => {
                        this.fecharModal();
                        this.$eventBus.$emit("ModalEditSubscription:reload", {
                            ...this.config,
                            dados: this.$page.props.dados
                        });
                        this.loading = false;
                    },
                    onError: () => {
                        this.loading = false;
                    }
                });
        }
    },
    beforeUnmount() {
        this.$eventBus.$off("ModalEditSubscription:config");
    },
    created() {
        this.$eventBus.$on("ModalEditSubscription:config", (evento) => {
            this.config = evento;
        });
    },
}
</script>

<style scoped>

</style>
