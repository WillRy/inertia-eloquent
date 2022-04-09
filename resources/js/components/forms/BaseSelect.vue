<template>
    <div class="form-group" :style="{'margin-bottom': mb, width: width}">
        <label v-if="label" :for="$attrs.id">{{ label }}</label>
        <VueMultiselect
            @update:model-value="updateValue"
            :model-value="modelValue"
            :track-by="trackBy"
            :options="options"
            :searchable="search"
            :allow-empty="empty"
            :label="textBy"
            v-bind="attrs"
            selectLabel=""
            selectGroupLabel=""
            selectedLabel=""
            deselectLabel=""
            deselectGroupLabel=""
        >
            <template #noOptions v-if="noOptions">
                {{ noOptions }}
            </template>
        </VueMultiselect>
        <div class="errorMessage" v-if="error || $slots.error">
            <div>{{ error }}</div>
            <slot name="error"></slot>
        </div>
    </div>
</template>

<script>
import VueMultiselect from 'vue-multiselect'

export default {
    name: "BaseSelect",
    inheritAttrs: false,
    components: {
        VueMultiselect
    },
    props: {
        noOptions: {
            type: String,
            default: 'Digite sua pesquisa'
        },
        label: {
            type: String,
            default: ''
        },
        modelValue: {
            type: Object
        },
        mb: {
            default: '20px'
        },
        width: {
            default: 'auto'
        },
        trackBy: {
            type: String
        },
        textBy: {
            type: String
        },
        options: {
            type: Array
        },
        search: {
            type: Boolean,
            default: true
        },
        empty: {
            type: Boolean,
            default: false
        },
        error: {
            type: String,
            default: null
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
            this.$emit('update:modelValue', event)
            this.$emit('change', event)
        }
    },
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

/deep/ .errorMessage > div {
    margin: 3px 0;
    color: red;
}

</style>
