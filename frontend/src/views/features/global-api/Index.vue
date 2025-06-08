<template>
    <div>
        <div class="d-flex justify-content-center">
            <router-link :to="{name: 'properties'}" class="btn btn-link p-0">Global Properties</router-link> |
            <router-link :to="{name: 'directives'}" class="btn btn-link p-0">Directives</router-link>
        </div>
        <router-view/>
    </div>
</template>

<script setup>

</script>

<style lang="scss" scoped>

</style>