<template>
    <div class="form-group" :style="{'margin-bottom': mb, width: width}">
        <label v-if="label" :for="$attrs.id">{{ label }}</label>
        <DatePicker
            v-model="data"
            mode="date"
            is24hr
            v-bind="attrs"
        >
            <template v-slot="{ inputValue, inputEvents }">
                <input
                    :value="inputValue"
                    v-on="inputEvents"
                    v-if="!disabled"
                />
                <input
                    :value="formatado"
                    disabled
                    v-else
                />
            </template>
        </DatePicker>
        <div class="errorMessage" v-if="error">
            <div>{{error}}</div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';

export default {
    name: "BaseDate",
    inheritAttrs: false,
    props: {
        error: {
            default: null,
            type: String
        },
        label: {
            type: String,
            default: ''
        },
        modelValue: [String, Date],
        mb: {
            default: '20px'
        },
        width: {
            default: 'auto'
        },
        disabled: {
            default: false
        },
        formatado: {
            type: [String, Date]
        }
    },
    computed: {
        attrs() {
            return {
                ...this.$attrs,
            }
        },
        data: {
            set(valor) {
                if(valor) {
                    let objeto = moment(valor);
                    let string = moment(valor).format("Y-MM-DD");
                    this.$emit('update:modelValue', objeto);
                    this.$emit('update:formatado', string);
                }
                return '';
            },
            get() {
                let valor = this.modelValue || this.formatado;
                return moment(valor).toDate();
            }
        },
    },
    methods: {
        emitirData() {
            if(this.data) {
              this.$emit('update:modelValue', this.data);
              this.$emit('change', this.data);

              this.$emit('update:formatado', this.formatado);
              this.$emit('changeFormatado', this.formatado);
            }
        },
    },
    created() {
        this.emitirData();
    }
}
</script>
<style scoped>
.form-group label {
    font-family: 'Roboto', sans-serif;
    font-style: normal;
    font-weight: bold;
    font-size: 14px;
    color: #444444;
    margin-bottom: 8px;
    text-transform: uppercase;
    display: block;
}
.form-group input {
    background: #FFFFFF;
    border: 1px solid var(--cor-borda-principal);
    border-radius: var(--radius-principal);
    padding: 13px 15px;
    display: block;
    width: 100%;
    height: 100%;
    color: var(--cor-texto-secundario);
}
.form-group input::placeholder {
    font-family: 'Roboto', sans-serif;
    font-style: normal;
    font-weight: normal;
    font-size: 16px;
    color: var(--cor-texto-terciario);
}

</style>
