<template>
    <div>
        <h3>INJECTED LABEL</h3>
        <p>{{ injectedValue }}</p>
    </div>
</template>

<script setup>
import { inject } from 'vue'

const injectedValue = inject('message', 'Default Injected Value')

</script>

<style lang="scss" scoped>

</style>