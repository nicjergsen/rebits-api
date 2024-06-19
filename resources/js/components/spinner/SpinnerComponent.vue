<template>
    <div class="spinner">
        <i class="fas fa-spinner fa-spin"></i>
        <span v-if="text" class="ml-2">{{ text }}</span>
    </div>
</template>

<script>
export default {
    name: 'Spinner',
    props: {
        text: {
            type: String,
            default: 'Cargando...'
        }
    }
};
</script>

<style scoped>
.spinner {
    display: flex;
    align-items: center;
}
</style>
