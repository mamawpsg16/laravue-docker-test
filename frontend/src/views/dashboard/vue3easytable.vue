<template>
  <div class="main-container">
    <!-- <div v-if="!user?.email_verified_at" class="bg-yellow-100 border border-yellow-300 text-yellow-800 p-4 rounded shadow">
      <p class="text-sm font-medium">Your email is not verified yet!</p>
      <p class="text-xs mt-1">Please check your email and verify to access the full features.</p>
      <button
        @click="resendVerificationEmail"
        class="mt-3 bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded text-sm"
      >
        Resend Verification Email
      </button>
    </div> -->

    <div class="space-y-6">
      <h3 class="text-2xl font-bold text-center">Welcome to your Dashboard</h3>

      <component :is="dashboardComponent" />
      <Vue3EasyDatatable  :headers="headers" :items="items" :rows-per-page="5" :show-index="true">
        <template #item-action="item">
          <FieldWrapper :required="false">
            <span class="text-sm mr-3 text-gray-600">
              {{ item.status ? 'Active' : 'Inactive' }}
            </span>
            <div
              @click="toggleStatus(item)"
              class="relative inline-flex h-6 w-11 items-center rounded-full cursor-pointer transition-colors"
              :class="item.status ? 'bg-blue-600' : 'bg-red-600'"
            >
              <span
                class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-300 ease-in-out"
                :class="item.status ? 'translate-x-6' : 'translate-x-1'"
              />
            </div>
          </FieldWrapper>
        </template>

      </Vue3EasyDatatable>
    </div>

  </div>
</template>

<script setup>
import Swal from 'sweetalert2/dist/sweetalert2.js'
import { onMounted, computed, ref } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { useGlobalProperties } from '@/composables/useGlobalProperties';
import { getPreviousRoute } from '@/router';
import Vue3EasyDatatable from '@/components/Table/Vue3EasyDatatable.vue';
import FieldWrapper from '@/components/Form/FieldWrapper.vue'
// Auth
const authStore = useAuthStore();
const $axios = useGlobalProperties().$axios;

const user = computed(() => authStore.user);
const role = computed(() => authStore.userRole);
const items = ref([
  { name: 'Alice', age: 30, email: 'alice@example.com', status: true },
  { name: 'Bob', age: 25, email: 'bob@example.com', status: false },
]);
const headers= ref([
        { text: 'Name', value: 'name' },
        { text: 'Age', value: 'age' },
        { text: 'Email', value: 'email' },
        { text: 'Action', value: 'status' }, // this enables the slot
      ]);

function toggleStatus(item) {
  console.log(item,'item');
  const index = items.value.findIndex(i => i.email === item.email);
  if (index !== -1) {
    items.value[index].status = !items.value[index].status;
    // Trigger reactivity
    // items.value = [...items.value];
  }
}


// Dynamically import dashboard components
import PatientDashboard from './components/PatientDashboard.vue';
import DoctorDashboard from './components/DoctorDashboard.vue';
import AdminDashboard from './components/AdminDashboard.vue';
import ReceptionistDashboard from './components/ReceptionistDashboard.vue';

const dashboardComponent = computed(() => {
  switch (role.value) {
    case 'admin':
      return AdminDashboard;
    case 'doctor':
      return DoctorDashboard;
    case 'receptionist':
      return ReceptionistDashboard;
    case 'patient':
    default:
      return PatientDashboard;
  }
});

// Handle email verification alert + resend
const cameFromRegister = ref(false);

const resendVerificationEmail = async () => {
  try {
    const response = await $axios.post('/email/verification-notification');
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
