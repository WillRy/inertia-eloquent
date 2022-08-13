<template>
    <div style="max-width: 44px">
        <VDropdown
            :distance="6"
            :shown="open"
        >

            <button class="btn-filter">
                <img src="/img/icons/filters-branco.svg" alt="" @click="open = true">
            </button>

            <!-- This will be the content of the popover -->
            <template #popper>
                <div v-click-away="() => open = false" class="dropdown-selecao" @click="open = false">
                    <button @click="selecionado('marcar')">
                        Selecionar todos
                    </button>
                    <button @click="selecionado('desmarcar')">
                        Desmarcar todos
                    </button>
                    <button @click="selecionado('inverter')">
                        Inverter seleção
                    </button>
                </div>
            </template>
        </VDropdown>
    </div>
</template>

<script>
export default {
    name: "DropdownFiltros",
    data() {
        return {
            open: false
        }
    },
    methods: {
        selecionado(evento){
            this.$emit("selecionado", evento);
            this.open = false;
        }
    }
}
</script>

<style scoped>

.btn-filter {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px;
    min-height: 42px;
    border: none;
    font-weight: normal;
    cursor: pointer;
    font-size: 16px;
    border-radius: var(--radius-principal);
    box-shadow: 0px 2px 4px #00000029;
    background-color: var(--cor-principal);
    color: #fff;
}

.btn-filter:hover {
    opacity: 0.6;
}

img {
    height: 24px;
    min-width: 24px;
    display: block;
    cursor: pointer;
}

.dropdown-selecao {
    width: auto;
    display: flex;
    flex-direction: column;
}

.dropdown-selecao::v-deep button,
.dropdown-selecao::v-deep a {
    display: flex;
    align-items: center;
    background: none;
    border: none;
    cursor: pointer;
    padding: 12px;
    text-decoration: none;
    color: #444444;
}

.dropdown-selecao::v-deep button:hover,
.dropdown-selecao::v-deep a:hover {
    background: #f2f2f2;
}


.dropdown-selecao::v-deep button:disabled,
.dropdown-selecao::v-deep a:disabled {
    opacity: 0.6 !important;
    cursor: not-allowed !important;
}

.dropdown-selecao::v-deep img{
    height: 24px;
    width: 24px;
    margin-right: 8px;
    display: block;
}
</style>
