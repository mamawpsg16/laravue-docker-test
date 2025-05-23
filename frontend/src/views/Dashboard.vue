<template>
  <div class="container mt-5">
    <div v-if="user.email_verified_at" class="text-center">
      <h3>DASHBOARD</h3>
    </div>
    <div v-if="!user.email_verified_at">
      <p class="lead">Your email is not verified yet!</p>
      <button @click="resendVerificationEmail">Resend Verification Email</button>
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

