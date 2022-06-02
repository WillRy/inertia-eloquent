<template>
    <div class="layout-dashboard">
        <Header/>
        <div class="layout-dashboard-container">
            <slot/>
        </div>
    </div>
</template>

<script>
import Header from "../components/dashboard/Header";

export default {
    name: "Dashboard",
    components: {
        Header,
    },
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
.layout-dashboard {
    min-height: 100vh;
    background: #F5F5F5;
    padding-top: 64px;
}

.layout-dashboard-container {

    max-width: 1200px;
    margin: 30px auto 0px auto;
    padding: 0 30px;
}
</style>
