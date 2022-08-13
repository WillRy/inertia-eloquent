<template>
    <div :class="{'form-group-has-icon': $slots.icon}" :style="{'margin-bottom': mb, width: width}" class="form-group">
        <label v-if="label" :for="$attrs.id">{{ label }}</label>
        <div class="form-group-container">
            <div v-if="$slots.icon" class="form-group-icon">
                <slot name="icon"></slot>
            </div>
            <input @input="updateValue" v-bind="$attrs" type="file" ref="input">
        </div>
        <div v-if="error || $slots.error" class="errorMessage">
            <div>{{ error }}</div>
            <slot name="error"></slot>
        </div>

    </div>
</template>

<script>
export default {
    name: "BaseFile",
    inheritAttrs: false,
    props: {
        label: {
            type: String,
            default: ''
        },
        modelValue: [String, Number, File, Object],
        mb: {
            default: '20px'
        },
        width: {
            default: 'auto'
        },
        error: {
            type: String,
        },
    },
    computed: {
        attrs() {
            return {
                ...this.$attrs,
                input: this.updateValue
            }
        }
    },
    data() {
        return {
            file: null
        }
    },
    watch: {
      modelValue: {
        deep: true,
        handler(valor) {
          this.file = valor;

          //limpa ao zerar valor
          if (!valor) this.limparCampo();
        }
      },
    },
    methods: {
        limparCampo(){
            this.$refs.input.value = "";
            this.file = null;
            this.$emit('update:modelValue', null)
        },
        updateValue(event) {
            if(event.target.files) {
                this.file = event.target.files;
                this.$emit('update:modelValue', event.target.files)
            } else {
                this.limparCampo();
                this.$emit('update:modelValue', null)
            }

        }
    },

}
</script>

<style scoped>
.form-group {
    margin-bottom: 20px;
}

label {
    font-weight: 700;
    font-size: 14px;
    color: var(--cor-label);
    margin-bottom: 8px;
    display: block;
    text-transform: capitalize;
}

.form-group-container {
    position: relative;
}

.form-group-icon {
    display: none;
}

input {
    background: #FFFFFF;
    border: 1px solid var(--cor-borda-principal);
    border-radius: var(--radius-principal);
    width: 100%;
    font-size: 16px;
    padding: 10px;
    min-height: 42px;
    color: #444444;
    font-weight: normal;
}

.form-group-has-icon input {
    padding-left: 45px !important;
}

.form-group-has-icon .form-group-icon {
    display: block;
    /*centraliza vertical*/
    position: absolute;
    top: 50%;
    left: 15px;
    transform: translateY(-50%);
}


input::placeholder {
    font-size: 16px;
    color: var(--cor-placeholder);
}


/deep/ .errorMessage > div {
    margin: 3px 0;
    color: var(--cor-input-error);
}

</style>
