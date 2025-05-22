<template>
    <div>
        <h3>SLOTS</h3>
        <Card id="main-card" class="custom-card">
            <template #header="{ headerMessage }">
            <h2>Header says: {{ headerMessage }}</h2>
            </template>

            This is the body of the card.

            <template #footer="{ footerMessage }">
            <p>Footer says: {{ footerMessage }}</p>
            </template>
        </Card>
    </div>
</template>

<script setup>
import Card from '@/components/Card.vue'
</script>

<style lang="scss" scoped>

</style>