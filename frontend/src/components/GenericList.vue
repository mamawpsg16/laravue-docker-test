<template>
    <ul>
      <li v-for="item in items" :key="getKey(item)">
        <slot :item="item" />
      </li>
    </ul>
  </template>
  
  <script lang="ts">
  import { defineComponent, PropType } from 'vue';
  
  export default defineComponent({
    name: 'GenericList',
    props: {
      items: {
        type: Array as PropType<any[]>, // We'll use generic typing when we consume this
        required: true
      },
      getKey: {
        type: Function as PropType<(item: any) => string | number>,
        required: true
      }
    }
  });
  </script>
  