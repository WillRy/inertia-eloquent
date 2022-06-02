<template>
    <div class="layout-publico">
        <slot/>
    </div>
</template>

<script>
export default {
    name: "Publico",
    watch: {
        "$page.props.success": {
            deep: true,
            handler(valor) {
                this.exibirSuccess(valor);
            }
        },
        "$page.props.error": {
            deep: true,
            handler(valor) {
                if (!valor) {
                    return;
                }

                this.$toast.open({
                    message: valor,
                    type: "error",
                });
            }
        }
    },
    methods: {
        exibirSuccess(valor) {
            if (!valor) {
                return;
            }

            this.$toast.open({
                message: valor,
                type: "success",
            });
        },
        exibirError(valor) {
            if (!valor) {
                return;
            }

            this.$toast.open({
                message: valor,
                type: "error",
            });
        },
    },
    created() {
        if (this.$page.props.success) {
            this.exibirSuccess(this.$page.props.success);
        }
        if (this.$page.props.error) {
            this.exibirError(this.$page.props.error);
        }
    }
}
</script>

<style scoped>
.layout-publico {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--cor-principal);
}
</style>
