<template>
    <BaseModal
        :aberta="config"
        @onOpen="carregarFormulario"
        @onClose="fecharModal"
    >
        <template #title>
            <h3>Cadastro de Aluno</h3>
        </template>
        <template #body>
            <form action="" @submit.prevent="submit">
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
                ></BaseInput>

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
                    v-mask="'?###'"
                    :class="{error: form.errors.weight}"
                    :error="form.errors.weight"
                ></BaseInput>

                <BaseInput
                    label="Altura"
                    type="text"
                    v-model="form.height"
                    v-mask="'#.##'"
                    :class="{error: form.errors.height}"
                    :error="form.errors.height"
                ></BaseInput>

                <BaseSelect
                    placeholder="Selecione o gênero"
                    label="Gênero"
                    :options="sexo"
                    text-by="name"
                    track-by="id"
                    v-model="form.gender"
                    :class="{error: form.errors.gender}"
                    :error="form.errors.gender"
                ></BaseSelect>

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
import {mapMutations} from 'vuex';
import BaseSelect from "../base/form/BaseSelect";
import {useForm} from '@inertiajs/inertia-vue3';


export default {
    name: "ModalAddStudent",
    components: {BaseSelect, BaseInput, BaseDate, BaseModal},
    props: {
        errors: Object,
        aberta: Boolean,
        reload: Array
    },
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
            sexo: [
                {name: 'Masculino', id: 'm'},
                {name: 'Feminino', id: 'f'},
                {name: 'Outro', id: 'o'},
            ],
        }
    },
    methods: {
        carregarFormulario() {

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
                    console.log(data);
                    return {
                        ...data,
                        gender: data.gender ? data.gender.id : ''
                    }
                })
                .post("/dashboard/students", {
                    only: this.reload,
                    onSuccess: () => {
                        this.fecharModal();
                        this.$eventBus.$emit("ModalAddStudent:reload", this.config);
                        this.loading = false;
                    },
                    onError: () => {
                        this.loading = false;
                    }
                });
        }
    },
    beforeUnmount() {
        this.$eventBus.$off("ModalAddStudent:config");
    },
    created() {
        this.$eventBus.$on("ModalAddStudent:config", (evento) => {
            this.config = evento;
        });
    },
}
</script>

<style scoped>

</style>
