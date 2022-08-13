<template>
    <div>
        <VDropdown
            :distance="6"
            :shown="open"
            :hide="!open"
        >

            <slot name="botao"></slot>

            <!-- This will be the content of the popover -->
            <template #popper="{hide}">
                <div v-click-away="fechar" class="dropdown-botao" @click.stop="hide">
                    <slot name="acoes"></slot>
                </div>
            </template>
        </VDropdown>
    </div>
</template>

<script>
export default {
    name: "DropdownBotao",
    props: ['marcados'],
    data() {
        return {
            open: false
        }
    },
    methods: {
        fechar(){
            this.open = false;
        },
        emitir(){
            this.$emit("remover");
            this.fechar();
        }
    }
}
</script>

<style scoped>


img {
    height: 24px;
    min-width: 24px;
    display: block;
    cursor: pointer;
}

.dropdown-botao {
    width: auto;
    display: flex;
    flex-direction: column;
}

.dropdown-botao::v-deep button,
.dropdown-botao::v-deep a {
    display: flex;
    align-items: center;
    background: none;
    border: none;
    cursor: pointer;
    padding: 12px;
    text-decoration: none;
    color: #444444;
}

.dropdown-botao::v-deep button:hover,
.dropdown-botao::v-deep a:hover {
    background: #f2f2f2;
}


.dropdown-botao::v-deep button:disabled,
.dropdown-botao::v-deep a:disabled {
    opacity: 0.6 !important;
    cursor: not-allowed !important;
}

.dropdown-botao::v-deep img{
    height: 24px;
    width: 24px;
    margin-right: 8px;
    display: block;
}
</style>
