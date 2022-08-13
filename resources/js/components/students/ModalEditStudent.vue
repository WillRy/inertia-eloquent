<template>
    <BaseModal
        :aberta="config"
        @onOpen="carregarFormulario"
        @onClose="fecharModal"
    >
        <template #title>
            <h3>Edição de Aluno</h3>
        </template>
        <template #body>
            <form action="" @submit.prevent="submit" v-if="!loadingDados">
                <BaseInput
                    label="Nome completo"
                    type="text"
                    placeholder=""
                    v-model="form.name"
                    :class="{error: form.errors.name}"
                    :error="form.errors.name"
                ></BaseInput>

                <BaseInput
                    label="E-mail"
                    type="email"
                    placeholder="exemplo@exemplo.com"
                    v-model="form.email"
                    :class="{error: form.errors.email}"
                    :error="form.errors.email"
                >
                </BaseInput>

                <BaseDate
                    label="Data de Nascimento"
                    v-model:formatado="form.date_birth"
                    :class="{error: form.errors.date_birth}"
                    :error="form.errors.date_birth"
                >
                </BaseDate>

                <BaseInput
                    label="Peso (kg)"
                    type="text"
                    v-model="form.weight"
                    :class="{error: form.errors.weight}"
                    :error="form.errors.weight"
                    v-mask="'##.##'"
                >
                </BaseInput>

                <BaseInput
                    label="Altura"
                    type="text"
                    v-model="form.height"
                    :class="{error: form.errors.height}"
                    :error="form.errors.height"
                    v-mask="'#.##'"
                >
                </BaseInput>

                <BaseSelect
                    placeholder="Selecione o gênero"
                    label="Gênero"
                    :options="sexo"
                    text-by="name"
                    track-by="id"
                    v-model="form.gender"
                    :class="{error: form.errors.gender}"
                    :error="form.errors.gender"
                >
                </BaseSelect>

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
import BaseDate from "../base/form/BaseDate";
import axios from 'axios';
import {mapMutations, mapState} from 'vuex';
import BaseSelect from "../base/form/BaseSelect";
import {useForm} from "@inertiajs/inertia-vue3";

export default {
    name: "ModalEditStudent",
    components: {BaseSelect, BaseInput, BaseDate, BaseModal},
    props: ['reload'],
    setup() {
        const form = useForm({
            name: '',
            email: '',
            date_birth: '',
            height: '',
            weight: '',
            gender: null
        });
        return {form};
    },
    data() {
        return {
            config: null,
            loading: false,
            loadingDados: false,
            sexo: [
                {name: 'Masculino', id: 'm'},
                {name: 'Feminino', id: 'f'},
                {name: 'Outro', id: 'o'},
            ],
        }
    },
    computed: {},
    methods: {

        carregarFormulario() {
            this.loadingDados = true;
            axios.get(`/dashboard/students/${this.config.aluno_id}`).then((response) => {
                Object.assign(this.form, response.data.data);
                /** pré carrega select de genero **/
                this.form.gender = this.sexo.find((s) => s.id === response.data.data.gender);
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
            this.form.reset();
            this.form.clearErrors();
            this.$emit("onClose");
        },
        async submit() {
            this.loading = true;
            this.form
                .transform(data => {
                    return {
                        ...data,
                        gender: data.gender ? data.gender.id : ''
                    }
                })
                .put(`/dashboard/students/${this.config.aluno_id}`, {
                    only: this.reload,
                    onSuccess: () => {
                        this.fecharModal();
                        this.loading = false;
                        this.$eventBus.$emit("ModalEditStudent:reload", this.config);

                    },
                    onError: () => {
                        this.loading = false;
                    }
                });
        }
    },
    beforeUnmount() {
        this.$eventBus.$off("ModalEditStudent:config");
    },
    created() {
        this.$eventBus.$on("ModalEditStudent:config", (evento) => {
            this.config = evento;
        });
    },
}
</script>

<style scoped>

</style>
