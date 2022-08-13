<template>
    <BaseModal
        :aberta="config"
        @onOpen="carregarFormulario"
        @onClose="fecharModal"
    >
        <template #title>
            <h3>Cadastro de Plano</h3>
        </template>
        <template #body>
            <form action="" @submit.prevent="submit">
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
                    v-mask="currencyMask"
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
import useVuelidate from '@vuelidate/core'
import {required} from '@vuelidate/validators'
import axios from 'axios';
import {mapMutations} from 'vuex';
import BaseSelect from "../base/form/BaseSelect";

import createNumberMask from 'text-mask-addons/dist/createNumberMask';
import {useForm} from "@inertiajs/inertia-vue3";

const currencyMask = createNumberMask({
    prefix: '',
    allowDecimal: true,
    includeThousandsSeparator: true,
    allowNegative: false,
})

export default {
    name: "ModalAddPlan",
    components: {BaseSelect, BaseInput, BaseModal},
    setup() {
        const form = useForm({
            name: '',
            price: '',
            duration: ''
        });
        return {form};
    },
    data() {
        return {
            config: null,
            currencyMask: currencyMask,
            loading: false,
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
                .post("/dashboard/plans", {
                    onSuccess: () => {
                        this.fecharModal();
                        this.$eventBus.$emit("ModalAddPlan:reload", this.config);
                        this.loading = false;
                    },
                    onError: () => {
                        this.loading = false;
                    }
                });
        }
    },
    beforeUnmount() {
        this.$eventBus.$off("ModalAddPlan:config");
    },
    created() {
        this.$eventBus.$on("ModalAddPlan:config", (evento) => {
            this.config = evento;
        });
    },
}
</script>

<style scoped>

</style>
