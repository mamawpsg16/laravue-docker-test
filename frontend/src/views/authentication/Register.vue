<template>
    <!-- <BaseModal :show="show" @close="$emit('close')"> -->
    <AuthLayout :class="{ 'border border-red-500': catchErrorMessage}">
      <h3 class="text-xl font-bold text-center text-gray-900 mt-3">Registration</h3> <!-- reduced from text-2xl and mt-5 -->
      <form @submit.prevent="register" class="space-y-1.5 p-4"> <!-- reduced from space-y-2.5 and p-6 -->

        <Label label="First Name" for="first_name"/>
        <BaseInput
          id="first_name"
          v-model="form.first_name"
          autocomplete="given-name"
          placeholder="Enter your first name"
          :frontend-error="getFrontendError('first_name')"
          :backend-error="getBackendError('first_name',  backendErrors)"
        />
        
        <Label label="Middle Name" for="middle_name" :required="false"/>
        <BaseInput
          id="middle_name"
          v-model="form.middle_name"
          autocomplete="additional-name"
          placeholder="Enter your middle name"
          :required="false"
          :backend-error="getBackendError('middle_name',  backendErrors)"
        />
        
        <Label label="Last Name" for="last_name"/>
        <BaseInput
          id="last_name"
          v-model="form.last_name"
          autocomplete="family-name"
          placeholder="Enter your last name"
          :frontend-error="getFrontendError('last_name')"
          :backend-error="getBackendError('last_name',  backendErrors)"
        />
  
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
          autocomplete="new-password"
          placeholder="Enter your password"
          minlength="8"
          maxlength="20"
          type="password"
          :frontend-error="getFrontendError('password')"
          :backend-error="getBackendError('password',  backendErrors)"
        />
  
        <Label label="Password Confirmation" for="password_confirmation"/>
        <BaseInput
          id="password_confirmation"
          v-model="form.password_confirmation"
          minlength="8"
          maxlength="20"
          autocomplete="new-password"
          placeholder="Confirm your password"
          type="password"
          :frontend-error="getFrontendError('password_confirmation')"
          :backend-error="getBackendError('password_confirmation',  backendErrors)"
        />
  
        <FieldWrapper label="Birth Date" :frontend-error="getFrontendError('birth_date')" :backend-error="getBackendError('birth_date',  backendErrors)">
          <VueDatePicker 
            v-model="form.birth_date"
            placeholder="Select your birth date"
            :enable-time-picker="false"
            format="MMMM d, yyyy"
            :auto-apply="true"
          />
        </FieldWrapper>
  
        <FieldWrapper label="Gender" :frontend-error="getFrontendError('gender')" :backend-error="getBackendError('gender',  backendErrors)">
          <multiselect
            v-model="form.gender"
            :options="genderOptions"
            label="label"
            placeholder="Select Gender"
          />
        </FieldWrapper>
  
       <Label label="Address" for="address" :required="false"/>
        <BaseInput
          id="address"
          :required="false"
          v-model="form.address"
          placeholder="Enter your address"
          :backend-error="getBackendError('address',  backendErrors)"
        />
  
        <button type="submit" :disabled="loading"
          class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-1.5 rounded disabled:opacity-50 disabled:cursor-not-allowed transition-colors mt-3"> <!-- reduced py-2 to py-1.5, added mt-3 -->
          <span v-if="loading" class="flex items-center justify-center">
            <span class="ml-2">Creating account...</span>
          </span>
          <span v-else>Create account</span>
        </button>
  
        <div v-if="errorMessage" class="rounded-md bg-red-50 p-2 text-sm text-red-800 text-center"> <!-- reduced p-4 to p-2 -->
          {{ errorMessage }}
        </div>
  
        <p class="text-xs text-center text-gray-600 pt-2"> <!-- reduced from text-sm and added pt-2 -->
          Already have an account?
          <router-link :to="{ name: 'login' }" class="text-indigo-600 hover:underline">Sign in</router-link>
        </p>
      </form>
    </AuthLayout>
</template>


<script setup>

defineProps({
  show: Boolean
})

defineEmits(['close'])

import { reactive, ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import BaseInput from '@/components/Form/BaseInput.vue'
import VueDatePicker from '@vuepic/vue-datepicker'
import Multiselect from 'vue-multiselect'
import { toDBFormat } from '@/utils/dateHelpers.js'
import { getValues } from '@/utils/dataExtractionHelper.js'
import { useFormValidation } from '@/composables/useFormValidation'
import { required, email, sameAs, helpers, minLength, maxLength } from '@vuelidate/validators'
import FieldWrapper from '@/components/Form/FieldWrapper.vue'
import { useNotify } from '@/composables/useNotify'
import AuthLayout from '@/components/AuthLayout.vue'
import Label from '@/components/Form/Label.vue';

const form = reactive({
  first_name: null,
  middle_name:null,
  last_name: null,
  email: null,
  password: null,
  password_confirmation: null,
  birth_date: null,
  gender: null,
  address: null
})

const { notifyToast } = useNotify()

const genderOptions = ref([
  { label: 'Male', value: 'male' },
  { label: 'Female', value: 'female' },
  { label: 'Other', value: 'other' }
])

const loading = ref(false)
const errorMessage = ref(null)

const backendErrors = ref(null)
const catchErrorMessage = ref(null);

const authStore = useAuthStore()

const router = useRouter()

const passwordValue = computed(() => form.password)

const rules = computed(() => ({
  first_name: { required },
  last_name: { required },
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
  password_confirmation: {
    required,
    sameAsPassword: helpers.withMessage(
      'Password does not match',
      sameAs(passwordValue)
    ),
    minLength:  helpers.withMessage(
      'Password must be at least 8 characters',
      minLength(8)
    ),
    maxLength:  helpers.withMessage(
      'Password must be at most 20 characters',
      maxLength(20)
    ),
  },
  birth_date: { required },
  gender: { required },
}))


const { $v, getFrontendError, getBackendError } = useFormValidation(form, rules)

async function register() {
  loading.value = true
  errorMessage.value = null
  catchErrorMessage.value = null;

  if (!(await $v.value.$validate())) {
    loading.value = false
    return
  }

  try {
    const data = getValues(form)

    await authStore.register({ ...data, birth_date: toDBFormat(form.birth_date) });

    notifyToast('Logged in!', 'success', {
      position: 'bottom-right',
      timeout: 3000,
      closeOnClick: false,
      pauseOnHover:false
    })
    router.push({ name: 'dashboard' })
  } catch (error) {
    const { errors, message } = error?.response?.data;

    backendErrors.value = errors;
    catchErrorMessage.value = message;

    notifyToast('Registration Failed!', 'error', {
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

