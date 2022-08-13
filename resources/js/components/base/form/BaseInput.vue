<template>
    <div :class="{'form-group-has-icon': $slots.icon}" :style="{'margin-bottom': mb, width: width}" class="form-group">
        <label v-if="label" :for="$attrs.id">{{ label }}</label>
        <div class="form-group-container">
            <div v-if="$slots.icon" class="form-group-icon">
                <slot name="icon"></slot>
            </div>
            <input v-bind="attrs" :value="modelValue"  @input="updateValue">
            <div v-if="$slots.legenda">
                <span class="legenda"><slot name="legenda"></slot></span>
            </div>
        </div>
        <div v-if="error || $slots.error" class="errorMessage">
            <div>{{ error }}</div>
            <slot name="error"></slot>
        </div>

    </div>
</template>

<script>
export default {
    name: "BaseInput",
    inheritAttrs: false,
    props: {
        label: {
            type: String,
            default: ''
        },
        modelValue: [String, Number],
        mb: {
            default: '20px'
        },
        width: {
            default: 'auto'
        },
        error: {
            type: String,
        }
    },
    computed: {
        attrs() {
            return {
                ...this.$attrs,
                input: this.updateValue
            }
        }
    },
    methods: {
        updateValue(event) {
            this.$emit('update:modelValue', event.target.value)
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

input:disabled {
    background: #e4e4e4;
    cursor: not-allowed;
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

.form-group-container:deep(.legenda) {
    font-size: 15px;
    color: #787878;
}

</style>
