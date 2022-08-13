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
import {Inertia} from "@inertiajs/inertia";

export default {
    name: "Dashboard",
    data(){
        return {
            naoExibirFlashAoVoltarBrowser: false
        }
    },
    components: {
        Header,
    },
    watch: {
        "$page.props.flash.success": {
            deep: true,
            handler(valor) {
                this.exibirSuccess(valor);
            }
        },
        "$page.props.flash.error": {
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
            if (!valor || this.naoExibirFlashAoVoltarBrowser) {
                this.naoExibirFlashAoVoltarBrowser = false;
                return;
            }

            this.$toast.open({
                message: valor,
                type: "success",
            });
        },
        exibirError(valor) {
            if (!valor || this.naoExibirFlashAoVoltarBrowser) {
                this.naoExibirFlashAoVoltarBrowser = false;
                return;
            }

            this.$toast.open({
                message: valor,
                type: "error",
            });
        },
    },
    created() {
        if (this.$page.props.flash.success) {
            this.exibirSuccess(this.$page.props.flash.success);
        }
        if (this.$page.props.flash.error) {
            this.exibirError(this.$page.props.flash.error);
        }
        /**
         * Corrigir bug importante do inertia em relação ao history do browser. Passos para reproduzir o bug
         *
         * 1 - Receber uma flash message do backend
         * 2 - Usar o botão de voltar do browser
         * 3 - Usar o botão de avançar do browser
         * 4 - A flash message vai aparecer novamente, sendo que não deveria
         *
         * O bug acontece devido a forma que o inertia.js trata o botão de voltar do browser,
         * restaurando o history.state da página para o estado anterior
         */
        this.naoExibirFlashAoVoltarBrowser = false;

        window.addEventListener('popstate', () => {
            this.naoExibirFlashAoVoltarBrowser = true;
        });

        Inertia.on('before', (event) => {
            this.naoExibirFlashAoVoltarBrowser = false;
        });

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
