<template>
    <BaseModal
        :aberta="aberta"
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
                    :class="{error: v$.form.name.$error}"
                >
                    <template #error v-if="v$.form.name.$error">
                        <div v-if="v$.form.name.required.$invalid" class="errorMessage">Informe o nome</div>
                    </template>
                </BaseInput>

                <BaseInput
                    label="Preço"
                    type="text"
                    v-mask="currencyMask"
                    v-model="form.price"
                    :class="{error: v$.form.price.$error}"
                >
                    <template #error v-if="v$.form.price.$error">
                        <div v-if="v$.form.price.required.$invalid" class="errorMessage">Informe o preço</div>
                    </template>
                </BaseInput>

                <BaseInput
                    label="Duração (Meses)"
                    type="number"
                    min="1"
                    v-model="form.duration"
                    :class="{error: v$.form.duration.$error}"
                >
                    <template #error v-if="v$.form.duration.$error">
                        <div v-if="v$.form.duration.required.$invalid" class="errorMessage">Informe a duração</div>
                    </template>
                </BaseInput>

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
import useVuelidate from '@vuelidate/core'
import {required} from '@vuelidate/validators'
import axios from 'axios';
import {mapMutations} from 'vuex';
import BaseSelect from "../forms/BaseSelect";

import createNumberMask from 'text-mask-addons/dist/createNumberMask';

const currencyMask = createNumberMask({
    prefix: '',
    allowDecimal: true,
    includeThousandsSeparator: true,
    allowNegative: false,
})

export default {
    name: "Plan",
    components: {BaseSelect, BaseInput, BaseModal},
    props: {
        aberta: Boolean
    },
    setup: () => ({v$: useVuelidate()}),
    data(){
        return {
            currencyMask: currencyMask,
            form: {
                name: '',
                price: '',
                duration: '',
            },
            loading: false,
        }
    },
    validations() {
        return {
            form: {
                name: {required},
                price: {required},
                duration: {required},
            }
        }
    },
    methods: {
        ...mapMutations([
            'SET_PLANOS_RELOAD'
        ]),
        carregarFormulario(){},
        fecharModal() {
            this.v$.$reset();
            this.$emit("onClose");
        },
        async submit() {
            const result = await this.v$.$validate();
            if (result) {
                this.loading = true;

                axios.post("/dashboard/plans", this.form).then((response)=>{
                    this.fecharModal();
                    this.$toast.open({
                        type: 'success',
                        message: 'Plano cadastrado com sucesso'
                    });
                    //envia sinal de reload para outros componentes
                    this.SET_PLANOS_RELOAD(response.data);
                }).catch((error)=>{
                    this.$laravelError(error, 'Não foi possível cadastrar o plano')
                }).finally(()=>{
                    this.loading = false;
                })
            }
        }
    }
}
</script>

<style scoped>

</style>
