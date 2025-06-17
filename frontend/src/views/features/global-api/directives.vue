<template>
    <div>
        <h3>DIRECTIVES</h3>
        <input type="text" v-focus v-log="directiveValue" v-model="directiveValue" name="input-directives"/>
        <button v-permission="['admin']">Delete User</button>
        <button v-permission="['admin', 'editor']">Edit Post</button>
    </div>
</template>

<script setup>

</script>

<style lang="scss" scoped>

</style>