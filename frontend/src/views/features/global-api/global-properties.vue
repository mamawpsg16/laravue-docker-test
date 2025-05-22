<template>
    <div>
        <h3>Fetch Date (GLOBAL AXIOS PROPERTY)</h3>
        <pre>{{ fetchDetails }}</pre>
        <button @click="fetchData">Fetch Data</button>
    </div>
</template>

<script setup>
import { onMounted, getCurrentInstance, ref } from 'vue';
import { useGlobalProperties } from '@/composables/useGlobalProperties';

const fetchDetails = ref(null);

const{ $axios, $hello } = useGlobalProperties();

onMounted(() => {
  console.log($hello(), "APP globalProperties"); // Logs: "Hello from global!"
});

const fetchData = async () => {
  const response = await $axios.get('https://dog.ceo/api/breeds/image/random');
  console.log(response.data, "FETCHED DATA");
  fetchDetails.value = response.data;
};
</script>

<style lang="scss" scoped>

</style>