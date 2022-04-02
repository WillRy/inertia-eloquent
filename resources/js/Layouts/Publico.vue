<template>
    <div class="layout-publico">
        <slot/>
    </div>
</template>

<script>
export default {
    name: "Publico",
    watch: {
        "$page.props.flash.success": {
            deep: true,
            handler(valor) {
                if (!valor) return;
                this.$toast.open({
                    message: valor,
                    type: 'success',
                });
            }
        },
        "$page.props.flash.error": {
            deep: true,
            handler(valor) {
                if (!valor) return;
                this.$toast.open({
                    message: valor,
                    type: 'error',
                });
            }
        },
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
