<template>
    <th>
        <div @click="$emit('onSort')">
            <img v-if="ordenando === nome" :src="`/img/${order}.svg`" alt="">
            <img v-else :src="`/img/sort.svg`" alt="">
            {{ texto }}
        </div>
    </th>
</template>

<script>
export default {
    name: "HeadSort",
    props: ['nome', 'order', 'texto', "ordenando"]
}
</script>

<style scoped>
th div {
    display: inline-flex;
    align-items: center;
    cursor: pointer;
}
</style>
