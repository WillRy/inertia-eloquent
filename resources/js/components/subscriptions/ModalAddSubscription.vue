<template>
    <BaseModal
        :aberta="config"
        @onOpen="carregarFormulario"
        @onClose="fecharModal"
    >
        <template #title>
            <h3>Cadastro de Matrícula</h3>
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
                    @search-change="pesquisarAlunos"
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
                    @search-change="pesquisarPlanos"
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
                Cadastrar
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
import {mapMutations} from 'vuex';
import BaseSelect from "../base/form/BaseSelect";
import {useForm} from "@inertiajs/inertia-vue3";

export default {
    name: "ModalAddSubscription",
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
        }
    },
    methods: {
        carregarFormulario() {
            Object.assign(this.form, {
                student_id: null,
                plan_id: null,
                dt_start: new Date()
            });
        },
        fecharModal() {
            this.config = null;
            this.form.reset();
            this.form.clearErrors();
            this.$emit("onClose");
        },
        pesquisarAlunos(pesquisa) {
            axios.get("/dashboard/students/list", {params: {search: pesquisa}}).then((response) => {
                this.alunos = response.data.data.data;
            });
        },
        pesquisarPlanos(pesquisa) {
            axios.get("/dashboard/plans/list", {params: {search: pesquisa}}).then((response) => {
                this.planos = response.data.data.data;
            });
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
                .post("/dashboard/subscriptions", {
                    onSuccess: () => {
                        this.fecharModal();
                        this.$eventBus.$emit("ModalAddSubscription:reload", {
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
        this.$eventBus.$off("ModalAddSubscription:config");
    },
    created() {
        this.$eventBus.$on("ModalAddSubscription:config", (evento) => {
            this.config = evento;
        });
    },
}
</script>

<style scoped>

</style>
