<template>
    <div class="page-header">
        <div class="page-header-title">
            <slot name="title"></slot>
        </div>
        <div class="page-header-actions">
            <slot name="actions"></slot>
        </div>
    </div>
</template>

<script>
export default {
    name: "PageHeader"
}
</script>

<style scoped>
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
}

/deep/ .page-header-title {
    color: #444444;
    font-weight: 700;
    font-size: 24px;
    line-height: 28px;
    text-align: right;
}

.page-header-actions {
    display: flex;
    align-items: center;
}

/deep/ .page-header-actions > *:not(:last-child) {
    margin-right: 16px;
}
</style>
