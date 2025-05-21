<template>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow">
          <div class="card-body">
            <h3 class="card-title mb-4 text-center">Login</h3>
            <form @submit.prevent="login">
              <div class="mb-3">
                <BaseInput
                  v-model="email"
                  label="Email"
                  type="email"
                  name="email"
                  placeholder="Enter your email"
                  required
                  autocomplete="email"
                />
              </div>
              <div class="mb-3">
                <BaseInput
                  v-model="password"
                  label="Password"
                  type="password"
                  name="password"
                  placeholder="Enter your password"
                  required
                  autocomplete="current-password"
                />
              </div>
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Login</button>
              </div>
            </form>
            <p v-if="errorMessage" class="text-danger mt-3">{{ errorMessage }}</p>
            <p class="mt-3 text-center">
              Don't have an account?
              <router-link :to="{ name: 'register' }" class="btn btn-link p-0">Register</router-link>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import BaseInput from '@/components/Form/BaseInput.vue';
import { useAuthStore } from '@/stores/auth';

export default {
  data() {
    return {
      email: '',
      password: '',
      errorMessage: null,
    };
  },
  components: {
    BaseInput,
  },
  methods: {
    async login() {
      const authStore = useAuthStore();
      try {
        await authStore.login({ email: this.email, password: this.password });
        this.$router.push({ name: 'dashboard' });
      } catch (error) {
        this.errorMessage = error.message;
      }
    },
  },
};
</script>
