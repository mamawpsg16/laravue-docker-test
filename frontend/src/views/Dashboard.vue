<template>
  <div class="container mt-5">
    <div class="card shadow">
      <div class="card-body text-center">
        <h2 class="card-title mb-3">Welcome to your Dashboard</h2>
        <div v-if="user.email_verified_at">
          <button @click="getUserDetails">Get User Details</button>
          <p>{{ userDetails }}</p>
          <p class="lead">Logged in as: <strong>{{ user?.name }}</strong></p>
          <div class="d-flex justify-content-between align-items-center flex-column">
            <router-link :to="{ name: 'tasks' }" class="btn btn-primary mt-3">Go to Tasks</router-link>
            <router-link :to="{ name: 'features' }" class="btn btn-primary mt-3">Go to Features</router-link>
          </div>
          <button @click="logout" class="btn btn-danger mt-3">Logout</button>
        </div>
        <div v-else>
          <h6 class="mb-3">Haven't verified your email yet?</h6>
          <button  class="btn btn-primary btn-sm" @click="resendVerificationEmail">Resend Verification Email</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import Swal from 'sweetalert2/dist/sweetalert2.js'
import { onMounted, ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import { getPreviousRoute } from '@/router';
import { useGlobalProperties } from '@/composables/useGlobalProperties';

const router = useRouter();
const authStore = useAuthStore();
const $axios = useGlobalProperties().$axios;
const userDetails = ref(null);
const cameFromRegister = ref(false);

const user = computed(() => authStore.user);

const logout = async () => {
  await authStore.logout();
  router.push({ name: 'login' });
};

const getUserDetails = async () => {
  try {
    userDetails.value = user.value;
  } catch (error) {
    console.error('Error fetching user details:', error);
    alert('Failed to fetch user details.');
  }
};

const resendVerificationEmail = async () => {
  const response = await $axios.post('/api/email/verification-notification');
  try {
    await Swal.fire({
      title: response.data.message,
      text: "Check your email to verify your account.",
      icon: "success",
    });
  } catch (error) {
    console.error('Error sending verification email:', error);
    alert('Failed to send verification email.');
  }
};

onMounted(() => {
  const prev = getPreviousRoute();

  cameFromRegister.value = prev && prev.name === 'register';

  if (cameFromRegister.value || !user.value?.email_verified_at) {
    Swal.fire({
      title: "Email is not verified yet!",
      text: "Check your email to verify your account.",
      icon: "warning",
    });
  }
});
</script>

