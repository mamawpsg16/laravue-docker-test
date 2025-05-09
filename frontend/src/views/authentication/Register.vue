<template>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow">
          <div class="card-body">
            <h3 class="card-title mb-4 text-center">Register</h3>
            <form @submit.prevent="register">
              <div class="mb-3">
                <BaseInput
                  v-model="name"
                  label="Name"
                  type="text"
                  name="name"
                  placeholder="Enter your name"
                  required
                />
              </div>
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
                  autocomplete="new-password"
                />
              </div>
              <div class="mb-3">
                <BaseInput
                  v-model="password_confirmation"
                  label="Password Confirmation"
                  type="password"
                  name="password_confirmation"
                  placeholder="Enter your password confirmation"
                  required
                  autocomplete="new-password"
                />
              </div>
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-success">Register</button>
              </div>
            </form>
            <p v-if="errorMessage" class="text-danger mt-3">{{ errorMessage }}</p>
            <p class="mt-3 text-center">
              Already have an account?
              <router-link to="/login" class="btn btn-link p-0">Login</router-link>
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
      name: '',
      email: '',
      password: '',
      password_confirmation: '',
      errorMessage: null,
    };
  },
  components: {
    BaseInput,
  },
  methods: {
    async register() {
      const authStore = useAuthStore();
      console.log(authStore,'authStore');
      try {
        await authStore.register({
          name: this.name,
          email: this.email,
          password: this.password,
          password_confirmation: this.password_confirmation,
        });
        this.$router.push({ name: 'dashboard' });
      } catch (error) {
        this.errorMessage = error.message;
      }
    },
  },
};
</script>
