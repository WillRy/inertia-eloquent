<template>
    <VDropdown
        :distance="6"
        :shown="open"
    >

        <img src="/img/icons/dots.svg" alt="" @click="open = true">

        <!-- This will be the content of the popover -->
        <template #popper>
            <div v-click-away="() => open = false" class="dropdown-acoes" @click="open = false">
                <slot></slot>
            </div>
        </template>
    </VDropdown>
</template>

<script>
export default {
    name: "DropdownAcoes",
    data() {
        return {
            open: false
        }
    },
}
</script>

<style scoped>
.v-popper {
    display: flex;
    justify-content: center;
}
img {
    height: 22px;
    min-width: 28px;
    display: block;
    cursor: pointer;
}

.dropdown-acoes {
    width: auto;
    display: flex;
    flex-direction: column;
}

.dropdown-acoes::v-deep button,
.dropdown-acoes::v-deep a {
    display: flex;
    align-items: center;
    background: none;
    border: none;
    cursor: pointer;
    padding: 12px;
    text-decoration: none;
    color: #444444;
}

.dropdown-acoes::v-deep button:hover,
.dropdown-acoes::v-deep a:hover {
    background: #f2f2f2;
}


.dropdown-acoes::v-deep button:disabled,
.dropdown-acoes::v-deep a:disabled {
    opacity: 0.6 !important;
    cursor: not-allowed !important;
}

.dropdown-acoes::v-deep img{
    height: 24px;
    width: 24px;
    margin-right: 8px;
    display: block;
}
</style>
