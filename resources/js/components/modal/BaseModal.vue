<template>
    <transition name="modal">
        <div class="base-modal-container" :class="{aberta: aberta}" v-if="aberta" @click.self="fecharModalClick">
            <div class="base-modal" data-modal="">
                <span class="btn-fechar-modal" @click="fecharModal" v-if="exibirBtnFechar"></span>
                <div class="base-modal-title">
                    <slot name="title"></slot>
                </div>

                <div class="base-modal-body">
                    <slot name="body"></slot>
                </div>

                <div class="base-modal-footer">
                    <slot name="footer">
                    </slot>
                </div>
            </div>
        </div>
    </transition>
</template>
<script>

export default {
    name: 'BaseModal',
    props: {
        aberta: {
            default: false
        },
        fecharClickOutside: {
            default: true
        },
        exibirBtnFechar: {
            default: true
        }
    },
    watch: {
        aberta(valor) {
            if (valor) {
                this.$emit('onOpen')
            }
        }
    },
    data() {
        return {
            handleClickOutside: null
        }
    },
    methods: {
        fecharModal() {
            this.$emit('onClose');
        },
        fecharModalClick() {
            if (this.fecharClickOutside) {
                this.$emit('onClose');
            }
        }
    },
}
</script>
<style>


.base-modal-container {
    display: flex;
    background: rgba(0, 0, 0, .5);
    height: 100vh;
    align-items: flex-start;
    justify-content: center;
    left: 0;
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 2000;
}

.base-modal {
    background: #fff;
    max-width: 650px;
    padding: 20px;
    width: 650px;
    margin: 120px;
    position: relative;
}

.base-modal-title h3 {
    margin: 0 0 20px 0;
    text-align: center;
    color: var(--cor-principal);
    font-size: 20px;
}

.base-modal .base-modal-body {
    margin-bottom: 20px;
    word-break: break-all;
}


.base-modal .base-modal-footer {
    display: flex;
    align-items: center;
    justify-content: center;
}

.base-modal .base-modal-footer > *:not(:last-child) {
    margin-right: 16px;
}

.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}

.modal-enter-active .base-modal {
    animation: fadeInDown 0.3s;
}


@keyframes fadeInDown {
    0% {
        opacity: 0;
        transform: translateZ(-20px)
    }

    to {
        opacity: 1;
        transform: translateZ(0px)
    }
}

.btn-fechar-modal {
    cursor: pointer;
    top: 20px;
    right: 20px;
    margin: 0;
    padding: 0;
    border: 0;
    background: none;
    position: absolute;
    width: 20px;
    height: 20px;
}


.btn-fechar-modal:after, .btn-fechar-modal:before {
    content: "";
    position: absolute;
    top: 10px;
    left: 0;
    right: 0;
    height: 1px;
    background: #000;
    border-radius: 1px;
    transform: rotate(-45deg);
}

.btn-fechar-modal:before {
    transform: rotate(45deg);
}

.btn-fechar-modal:hover {

    opacity: 0.3;

}


</style>
