<template>
  <AuthLayout :class="{ 'border border-red-500': catchErrorMessage}">
    <h3 class="text-3xl font-bold mb-6 text-center text-gray-900">Login</h3>
    <form @submit.prevent="login" class="space-y-3">
      <Label label="Email Address" for="email"/>
      <BaseInput
        id="email"
        v-model="form.email"
        autocomplete="email"
        placeholder="Enter your email"
        :frontend-error="getFrontendError('email')"
        :backend-error="getBackendError('email',  backendErrors)"
      />

      <Label label="Password" for="password"/>
      <BaseInput
        id="password"
        v-model="form.password"
        autocomplete="current-password"
        placeholder="Enter your password"
        minlength="8"
        maxlength="20"
        type="password"
        :frontend-error="getFrontendError('password')"
        :backend-error="getBackendError('password',  backendErrors)"
      />
      <div v-if="catchErrorMessage"  class="mt-1 text-sm text-red-600 space-y-0.5">
        {{ catchErrorMessage }}
      </div>

      <div>
        <button
          type="submit"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded"
          :disabled="loading"
          :class="{ 'opacity-50 cursor-not-allowed': loading }"
        >
          {{ loading ? 'Logging In...' : 'Login' }}
        </button>

      </div>

      <p class="mt-3 text-center text-sm text-gray-600">
        Don't have an account?
        <router-link :to="{ name: 'register' }" class="text-indigo-600 hover:underline">Register</router-link>
      </p>
    </form>

    <!-- Show Register modal -->
    <Register v-if="showRegister" :show="showRegister" @close="showRegister = false" />
  </AuthLayout>
</template>

<script setup>
import AuthLayout from '@/components/AuthLayout.vue'
import Register from './Register.vue'
import { ref, reactive } from 'vue'
import { useAuthStore } from '@/stores/auth'
import BaseInput from '@/components/Form/BaseInput.vue'
import { useRouter } from 'vue-router'
import { useNotify } from '@/composables/useNotify'
import { useFormValidation } from '@/composables/useFormValidation'
import { required, email, helpers, minLength, maxLength } from '@vuelidate/validators'
import Label from '@/components/Form/Label.vue';

const loading = ref(false)

const form = reactive({
  email: null,
  password: null,
})

const catchErrorMessage = ref(null);

const backendErrors = ref(null)

const showRegister = ref(false)

const authStore = useAuthStore()
const router = useRouter()

const { notifyToast } = useNotify()

const rules = {
  email: { required, email },
  password: { 
    required,
    minLength:  helpers.withMessage(
      'Password must be at least 8 characters',
      minLength(8)
    ),
    maxLength:  helpers.withMessage(
      'Password must be at most 20 characters',
      maxLength(20)
    ),
  },
}


const { $v, getFrontendError, getBackendError } = useFormValidation(form, rules)

async function login() {
  loading.value = true
  if (!(await $v.value.$validate())) {
    loading.value = false
    return
  }
  try {
    await authStore.login({ ...form })
    router.push({ name: 'dashboard' })
  } catch (error) {
     const { errors, message } = error?.response?.data;

     backendErrors.value = errors;
     catchErrorMessage.value = message;

     notifyToast('Enter valid email and password', 'error', {
        position: 'bottom-right',
        timeout: 3000,
        closeOnClick: false,
        pauseOnHover:false
      })
  } finally {
    loading.value = false
  }
}
</script>
