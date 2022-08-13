<template>
    <th>
        <button @click="$emit('onSort')" >
            <img v-if="ordenando === nome" :src="`/img/icons/${orderMinuscula}.svg`" alt="">
            <img v-else :src="`/img/icons/sort.svg`" alt="">
            {{ texto }}
        </button>
    </th>
</template>

<script>
export default {
    name: "HeadSort",
    props: ['nome', 'order', 'texto', "ordenando"],
    computed: {
        orderMinuscula() {
            return this.order.toLowerCase();
        }
    }
}
</script>

<style scoped>
th button {
    color: rgb(75 85 99);
    font-weight: 500;
    font-size: .875rem;
    line-height: 1.25rem;
    padding: 0.5rem 1rem;
    white-space: nowrap;
    display: flex;
    align-items: center;
    background: none;
    border: none;
    cursor: pointer;
}
</style>
