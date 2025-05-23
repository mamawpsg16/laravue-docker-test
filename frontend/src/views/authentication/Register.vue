<template>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-4">
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
                <span v-if="loading">
                  <LoadingSpinner/>
                </span>
                <button v-else type="submit" class="btn btn-success">Register</button>
              </div>
            </form>
            <p v-if="errorMessage" class="text-danger mt-3">{{ errorMessage }}</p>
            <p class="mt-3 text-center">
              Already have an account?
              <router-link :to="{ name: 'login' }" class="btn btn-link p-0">Login</router-link>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import BaseInput from '@/components/Form/BaseInput.vue';
import LoadingSpinner from '@/components/AsyncComponents/LoadingSpinner.vue'

const name = ref('');
const email = ref('');
const password = ref('');
const password_confirmation = ref('');
const loading = ref(false);
const errorMessage = ref<string | null>(null);

const authStore = useAuthStore();
const router = useRouter();

async function register() {
  loading.value = true;
  try {
    await authStore.register({
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: password_confirmation.value,
    });
    router.push({ name: 'dashboard' });
  } catch (error: unknown) {
    if (error instanceof Error) {
      errorMessage.value = error.message;
    } else {
      errorMessage.value = String(error) || 'Registration failed';
    }
  }finally {
    loading.value = false;
  }
}
</script>
