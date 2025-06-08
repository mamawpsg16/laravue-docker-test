<template>
    <GenericList
      :items="users"
      :getKey="(user) => user.id"
    >
      <template #default="{ item }">
        <strong>{{ item.name }}</strong> — {{ item.email }}
      </template>
    </GenericList>
  </template>
  
  <script lang="ts">
  import { defineComponent } from 'vue';
  import GenericList from '@/components/GenericList.vue';
  
  interface User {
    id: number;
    name: string;
    email: string;
  }
  
  export default defineComponent({
    name: 'UserListView',
    components: { GenericList },
    setup() {
      const users: User[] = [
        { id: 1, name: 'Alice', email: 'alice@example.com' },
        { id: 2, name: 'Bob', email: 'bob@example.com' },
      ];
  
      return { users };
    }
  });
  </script>
  