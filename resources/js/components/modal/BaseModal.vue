<template>
    <transition name="modal">
        <div v-if="aberta" :class="{aberta: aberta, center: textCenter}" class="base-modal-container"
             @click.self="fecharModalClick">
            <div :style="{padding: padding}" class="base-modal" data-modal="">
                <span class="btn-fechar-modal" @click="fecharModal" v-if="exibirBtnFechar"></span>

                <div v-if="$slots.title" class="base-modal-title">
                    <slot name="title"></slot>
                </div>

                <div class="base-modal-body">
                    <slot name="body"></slot>
                </div>

                <div v-if="$slots.footer" class="base-modal-footer">
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
        padding: {
            type: String,
            default: "30px"
        },
        aberta: {
            default: false
        },
        fecharClickOutside: {
            default: true
        },
        exibirBtnFechar: {
            default: true
        },
        textCenter: {
            default: false
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
    align-items: flex-start;
    justify-content: center;
    left: 0;
    position: fixed;
    top: 0;
    z-index: 1000;
    height: 100vh;
    width: 100vw;

    overflow-y: scroll;

    background: rgba(51, 47, 47, 0.42);
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(7px);
    -webkit-backdrop-filter: blur(7px);
    border: 1px solid rgba(51, 47, 47, 0.31);
}

.base-modal {
    border-radius: var(--radius-principal);
    background: #fff;
    padding: 30px;
    /* width: 650px; */
    margin: 120px 0;
    position: relative;
    max-width: 650px;
    width: 65vw;
}

@media all and (max-width: 620px) {
    .base-modal {
        width: 90%;
    }
}

.base-modal-title h3 {
    margin: 0;
    text-align: center;
    color: var(--cor-principal);
    font-size: 20px;
    word-break: break-word;
}

.base-modal .base-modal-body {
    word-break: break-word;
    padding: 30px 0;
}

.base-modal .base-modal-body.center {
    text-align: center;
}

.base-modal .base-modal-footer {
    /*display: flex;*/
    justify-content: center;

    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(156px, max-content));
    justify-content: center;
    gap: 16px;
}

/* display: grid; */
/* grid-template-columns: repeat(auto-fit, minmax(156px, 33%));

}

/*.base-modal .base-modal-footer > *:not(:last-child) {*/
/*    margin-right: 16px;*/
/*}*/

/*.base-modal .base-modal-footer > * {*/
/*    margin-bottom: 16px;*/
/*}*/

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
    opacity: 0.6;
}
</style>
