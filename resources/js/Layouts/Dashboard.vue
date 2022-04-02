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
    components: {Header},
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
}
</style>
