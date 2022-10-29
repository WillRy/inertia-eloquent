<template>
    <th style="{'max-width': width}">
        <button @click="$emit('onSort')" >
            <img v-if="(ordenando === nome || ordenando === order) && ordenando" :src="`/img/${orderMinuscula}.svg`" alt="">
            <img v-else :src="`/img/sort.svg`" alt="">
            {{ texto }}

            <img v-if="info" src="/img/information-azul.svg" alt="" class="info" v-tooltip="{content: info}">
        </button>
    </th>
</template>

<script>
export default {
    name: "HeadSort",
    props: ['nome', 'order', 'texto', "ordenando", 'width', 'info'],
    computed: {
        orderMinuscula() {
            if(!this.order) return "sort";
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

.info {
    width: 16px;
    height: 16px;
    margin-left: 6px;
}
</style>
