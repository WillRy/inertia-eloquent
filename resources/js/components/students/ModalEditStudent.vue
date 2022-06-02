<template>
    <BaseModal
        :aberta="aluno_id"
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
            <Loader height="80px" width="80px" v-if="loadingDados" fill="#6d74ed"/>
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
import BaseModal from "../modal/BaseModal";
import BaseInput from "../forms/BaseInput";
import BaseDate from "../forms/BaseDate";
import axios from 'axios';
import {mapMutations, mapState} from 'vuex';
import BaseSelect from "../forms/BaseSelect";
import {useForm} from "@inertiajs/inertia-vue3";

export default {
    name: "ModalEditStudent",
    components: {BaseSelect, BaseInput, BaseDate, BaseModal},
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
            loadingDados: false,
            sexo: [
                {name: 'Masculino', id: 'm'},
                {name: 'Feminino', id: 'f'},
                {name: 'Outro', id: 'o'},
            ],
        }
    },
    computed: {
        ...mapState({
            'aluno_id': 'alunos_id_edicao'
        })
    },
    methods: {
        ...mapMutations([
            'SET_ALUNOS_RELOAD',
            'SET_ALUNOS_ID_EDICAO'
        ]),
        carregarFormulario() {
            this.loadingDados = true;
            axios.get(`/dashboard/students/${this.aluno_id}`).then((response) => {
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
            this.form.reset();
            this.form.clearErrors();
            this.$emit("onClose");
            this.SET_ALUNOS_ID_EDICAO(null);
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
                .put(`/dashboard/students/${this.aluno_id}`, {
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
