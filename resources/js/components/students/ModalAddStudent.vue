<template>
    <BaseModal
        :aberta="aberta"
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
                    v-model="form.date_birth"
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
import BaseModal from "../modal/BaseModal";
import BaseInput from "../forms/BaseInput";
import BaseDate from "../forms/BaseDate";
import {mapMutations} from 'vuex';
import BaseSelect from "../forms/BaseSelect";
import {useForm} from '@inertiajs/inertia-vue3';


export default {
    name: "ModalAddStudent",
    components: {BaseSelect, BaseInput, BaseDate, BaseModal},
    props: {
        errors: Object,
        aberta: Boolean
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
            loading: false,
            sexo: [
                {name: 'Masculino', id: 'm'},
                {name: 'Feminino', id: 'f'},
                {name: 'Outro', id: 'o'},
            ],
        }
    },
    methods: {
        ...mapMutations([
            'SET_ALUNOS_RELOAD'
        ]),
        carregarFormulario() {
            this.form.reset();
        },
        fecharModal() {
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
                        date_birth: data.date_birth ? data.date_birth.toISOString().split('T')[0] : '',
                        gender: data.gender ? data.gender.id : ''
                    }
                })
                .post("/dashboard/students", {
                    onSuccess: () => {
                        this.fecharModal();
                        this.loading = false;
                    },
                    onError: () => {
                        this.loading = false;
                    }
                });
        }
    }
}
</script>

<style scoped>

</style>
