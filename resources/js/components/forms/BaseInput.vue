<template>
    <div class="form-group" :style="{'margin-bottom': mb, width: width}">
        <label v-if="label" :for="$attrs.id">{{ label }}</label>
        <input type="text" :value="modelValue" @input="updateValue" v-bind="attrs">
        <div class="errorMessage" v-if="error || $slots.error">
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
    color: #444444;
    text-transform: uppercase;
    margin-bottom: 8px;
    display: block;
}

input {
    background: #FFFFFF;
    border: 1px solid #DDDDDD;
    box-sizing: border-box;
    border-radius: 4px;
    width: 100%;
    padding: 13px;
    min-height: 50px;
}

/deep/ .errorMessage > div {
    margin: 3px 0;
    color: red;
}

</style>
