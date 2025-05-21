<template>
  <div class="container mt-5">
    <div class="card shadow">
      <div class="card-body text-center">
        <h2 class="card-title mb-3">Welcome to your Dashboard</h2>
        <p class="lead">Logged in as: <strong>{{ user?.name }}</strong></p>
        <div class="d-flex justify-content-between align-items-center flex-column">
          <router-link :to="{ name: 'tasks' }" class="btn btn-primary mt-3">Go to Tasks</router-link>
          <router-link :to="{ name: 'features' }" class="btn btn-primary mt-3">Go to Features</router-link>
          <button @click="logout" class="btn btn-danger mt-3">Logout</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

import { useAuthStore } from '@/stores/auth';

export default {
  computed: {
    user() {
      return useAuthStore().user;
    },
  },
  methods: {
    async logout() {
      const authStore = useAuthStore();
      await authStore.logout();
      this.$router.push({ name: 'login' });
    },
  },
};
</script>
