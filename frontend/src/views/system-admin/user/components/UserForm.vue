<template>
<BaseModal
    v-model="modelValue"     
    max-width="6xl"
    @closed="resetForm"
  >
    <div class="modal-header">
      <h3 class="modal-subtitle">
        {{ isEditing ? 'Edit User' : 'Add New User' }}
      </h3>
    </div>

    <form @submit.prevent="saveUser" class="p-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Personal Information -->
        <div class="col-span-2">
          <h4 class="text-lg font-medium text-gray-900">Personal Information</h4>
        </div>

        <div class="col-span-2 md:col-span-1 md:col-start-2">
          <FieldWrapper label="Role" class="w-full" :frontend-error="getFrontendError('role')" :backend-error="getBackendError('role',  backendErrors)">
            <multiselect
              v-model="form.role"
              :options="roleOptions"
              label="label"
              placeholder="Select Role"
            />
          </FieldWrapper>
        </div>

        <div class="mb-1">
          <Label label="First Name" for="first_name" required/>
          <BaseInput
            id="first_name"
            autocomplete="given-name"
            v-model="form.first_name"
            placeholder="Enter first name"
            :error="errors.first_name"
            :frontend-error="getFrontendError('first_name')" 
            :backend-error="getBackendError('first_name',  backendErrors)"
            required
          />
        </div>

        <div class="mb-1">
          <Label label="Last Name" for="last_name" required/>
          <BaseInput
            id="last_name"
            v-model="form.last_name"
            autocomplete="family-name"
            placeholder="Enter last name"
            :frontend-error="getFrontendError('last_name')" 
            :backend-error="getBackendError('last_name',  backendErrors)"
            required
          />
        </div>

        <div class="mb-1">
          <Label label="Middle Name" for="middle_name"/>
          <BaseInput
            id="middle_name"
            autocomplete="additional-name"
            v-model="form.middle_name"
            placeholder="Enter middle name (optional)"
            :frontend-error="getFrontendError('middle_name')" 
            :backend-error="getBackendError('middle_name',  backendErrors)"
          />
        </div>

        <div class="mb-1">
          <Label label="Email" for="email" required/>
          <BaseInput
            id="email"
            type="email"
            autocomplete="email"
            v-model="form.email"
            placeholder="Enter email address"
            :error="errors.email"
            :frontend-error="getFrontendError('email')" 
            :backend-error="getBackendError('email',  backendErrors)"
            required
          />
        </div>

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
        
        <!-- Address -->
        <div class="col-span-2">
          <Label label="Address" for="address" :required="false"/>
          <BaseTextArea
            id="address"
            v-model="form.address"
            placeholder="Enter full address"
            :frontend-error="getFrontendError('address')" 
            :backend-error="getBackendError('address',  backendErrors)"
          />
        </div>

        <!-- Password (only for new users) -->
        <div v-if="!isEditing" class="col-span-2">
          <h4 class="text-lg font-medium text-gray-900 mb-2">Account Security</h4>
        </div>

        <div v-if="!isEditing">
          <Label label="Password" for="password" required/>
          <BaseInput
            id="password"
            placeholder="Enter password"
            type="password"
            v-model="form.password"
            :frontend-error="getFrontendError('password')"
            :backend-error="getBackendError('password', backendErrors)"
            autocomplete="new-password"
          />
        </div>

        <div v-if="!isEditing">
          <Label label="Confirm Password" for="password_confirmation" required/>
          <BaseInput
          id="password_confirmation"
          placeholder="Confirm password"
          type="password"
          v-model="form.password_confirmation"
          :frontend-error="getFrontendError('password_confirmation')"
          :backend-error="getBackendError('password_confirmation', backendErrors)"
          autocomplete="new-password"
          />
        </div>

        <!-- Status (only for editing) -->
        <div v-if="isEditing" class="col-span-2 flex justify-end">
          <FieldWrapper label="Status">
            <span class="text-sm mr-3 text-gray-600">
              {{ form.is_active? 'Active' : 'Inactive' }}
            </span>
            <div
              @click="form.is_active = form.is_active? 0 : 1"
              class="relative inline-flex h-6 w-11 items-center rounded-full cursor-pointer transition-colors"
              :class="form.is_active? 'bg-blue-600' : 'bg-gray-300'"
            >
              <span
                class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-300 ease-in-out"
                :class="form.is_active? 'translate-x-6' : 'translate-x-1'"
              />
            </div>
          </FieldWrapper>
        </div>
      </div>

      <!-- Buttons -->
      <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
        <button
          type="button"
          @click="modelValue = false"
          class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          Cancel
        </button>
        <button
          type="submit"
          :disabled="loading"
          class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50"
        >
          <span v-if="loading">{{ isEditing ? 'Updating...' : 'Creating...' }}</span>
          <span v-else>{{ isEditing ? 'Update User' : 'Create User' }}</span>
        </button>
      </div>
    </form>
  </BaseModal>
</template>

<script setup>
import BaseInput from '@/components/Form/BaseInput.vue'
import BaseModal from '@/components/BaseModal.vue'
import FieldWrapper from '@/components/Form/FieldWrapper.vue'
import Label from '@/components/Form/Label.vue'
import BaseTextArea from '@/components/Form/BaseTextArea.vue'
import VueDatePicker from '@vuepic/vue-datepicker'
import Multiselect from 'vue-multiselect'
import { useNotify } from '@/composables/useNotify'
import { useFormValidation } from '@/composables/useFormValidation'
import { required, email, sameAs, helpers, minLength, maxLength } from '@vuelidate/validators'
import { ref, computed, watch, reactive } from 'vue'

// Define the v-model prop using defineModel
const modelValue = defineModel({ type: Boolean, default: false });

// Define other non-v-model props as usual
const props = defineProps({
  isEditing: {
    type: Boolean,
    default: false
  },
  initialUser: {
    type: Object,
    default: null
  }
})

// Define other non-v-model emits as usual
const emit = defineEmits(['saved', 'closed']);

const { notifyToast } = useNotify()

const roleOptions = ref([
  { value: 'patient', label: 'Patient' },
  { value: 'doctor', label: 'Doctor' },
  { value: 'admin', label: 'Admin' },
  { value: 'receptionist', label: 'Receptionist' }
])

const genderOptions = ref([
  { value: 'male', label: 'Male' },
  { value: 'female', label: 'Female' },
  { value: 'other', label: 'Other' },
]);

// Use reactive instead of ref for better reactivity with nested objects
const form = reactive({
  first_name: '',
  last_name: '',
  middle_name: '',
  email: '',
  phone: '',
  birth_date: null,
  gender: null,
  role: null,
  address: '',
  emergency_contact_name: '',
  emergency_contact_phone: '',
  password: '',
  password_confirmation: '',
  is_active: 1,
});

const errors = reactive({
  first_name: '',
  last_name: '',
  email: '',
  role: '',
  password: '',
  password_confirmation: ''
})

const backendErrors = ref(null)
const loading = ref(false)

// Fix the validation rules - use computed for better reactivity
const rules = computed(() => {
  const baseRules = {
    first_name: { required },
    last_name: { required },
    email: { required, email },
    birth_date: { required },
    gender: { required },
    role: { required },
  };

  // Only add password rules if not editing
  if (!props.isEditing) {
    baseRules.password = {
      required,
      minLength: helpers.withMessage(
        'Password must be at least 8 characters',
        minLength(8)
      ),
      maxLength: helpers.withMessage(
        'Password must be at most 20 characters',
        maxLength(20)
      ),
    };
    baseRules.password_confirmation = {
      required,
      sameAsPassword: helpers.withMessage(
        'Password does not match',
        sameAs(computed(() => form.password)) // Use computed for reactivity
      ),
      minLength: helpers.withMessage(
        'Password must be at least 8 characters',
        minLength(8)
      ),
      maxLength: helpers.withMessage(
        'Password must be at most 20 characters',
        maxLength(20)
      ),
    };
  }

  return baseRules;
});

const { $v, getFrontendError, getBackendError } = useFormValidation(form, rules)

// Fixed watch function for proper form population
watch(() => [modelValue.value, props.initialUser], ([newModalValue, newInitialUser]) => {
  if (newModalValue) {
    // Reset validation and errors
    $v.value.$reset();
    backendErrors.value = null;

    if (props.isEditing && newInitialUser) {
      // Populate form with existing user data
      populateForm(newInitialUser);
    } else {
      // Reset form for new user
      resetForm();
    }
  }
}, { immediate: true });

// Helper function to populate form data
function populateForm(userData) {
  // Basic fields
  form.first_name = userData.first_name || '';
  form.last_name = userData.last_name || '';
  form.middle_name = userData.middle_name || '';
  form.email = userData.email || '';
  form.phone = userData.phone || '';
  form.address = userData.address || '';
  form.emergency_contact_name = userData.emergency_contact_name || '';
  form.emergency_contact_phone = userData.emergency_contact_phone || '';
  form.is_active = userData.is_active !== undefined ? userData.is_active : 1;

  // Handle birth_date
  if (userData.birth_date) {
    form.birth_date = new Date(userData.birth_date);
  } else {
    form.birth_date = null;
  }

  // Handle role (could be object or string)
  if (userData.role) {
    if (typeof userData.role === 'string') {
      form.role = roleOptions.value.find(opt => opt.value === userData.role) || null;
    } else {
      form.role = roleOptions.value.find(opt => opt.value === userData.role.value) || null;
    }
  } else {
    form.role = null;
  }

  // Handle gender (could be object or string)
  if (userData.gender) {
    if (typeof userData.gender === 'string') {
      form.gender = genderOptions.value.find(opt => opt.value === userData.gender) || null;
    } else {
      form.gender = genderOptions.value.find(opt => opt.value === userData.gender.value) || null;
    }
  } else {
    form.gender = null;
  }

  // Don't populate password fields when editing
  form.password = '';
  form.password_confirmation = '';
}

async function saveUser() {
  loading.value = true

  if (!(await $v.value.$validate())) {
    loading.value = false
    return
  }

  try {
    const data = getValues(form)

    await authStore.register({ ...data, birth_date: toDBFormat(form.birth_date) });
    emit('saved', response?.data);
    modelValue.value = false; // Close modal
    // notifyToast('Logged in!', 'success', {
    //   position: 'bottom-right',
    //   timeout: 3000,
    //   closeOnClick: false,
    //   pauseOnHover:false
    // })
    router.push({ name: 'dashboard' })
  } catch (error) {
    const { errors, message } = error?.response?.data;

    backendErrors.value = errors;
    // catchErrorMessage.value = message; // This variable wasn't defined

    notifyToast('Registration Failed!', 'error', {
      position: 'bottom-right',
      timeout: 3000,
      closeOnClick: false,
      pauseOnHover: false
    })

  } finally {
    loading.value = false
  }
}

function resetForm() {
  // Reset all form fields
  Object.assign(form, {
    first_name: '',
    last_name: '',
    middle_name: '',
    email: '',
    phone: '',
    birth_date: null,
    gender: null,
    role: null,
    address: '',
    emergency_contact_name: '',
    emergency_contact_phone: '',
    password: '',
    password_confirmation: '',
    is_active: 1,
  });
  
  // Reset validation state
  $v.value.$reset();
  // Clear backend errors
  backendErrors.value = null;
  // Emit the 'closed' event to parent after reset
  emit('closed');
}

</script>

<style lang="scss" scoped>

</style>