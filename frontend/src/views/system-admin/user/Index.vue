<template>
  <div class="page-container">
    <!-- Header -->
    <div class="content-card">
      <div class="card-header">
        <div class="header-content">
          <div>
            <h1 class="page-title">Users Management</h1>
            <p class="page-subtitle">Manage users and their information</p>
          </div>
          <button @click="openAddModal" class="btn-primary">
            <svg class="action-icon-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add User
          </button>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="stats-grid-4">
        <div class="stat-card">
          <div class="stat-card-content">
            <div class="stat-icon-blue">
              <svg class="stat-icon-blue-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h4M9 7h6m-6 4h6m-6 4h6" />
              </svg>
            </div>
            <div class="stat-details">
              <p class="section-label">Total Users</p>
              <p class="section-value">{{ data.length }}</p>
            </div>
          </div>
        </div>
         
        <div class="stat-card">
          <div class="stat-card-content">
            <div class="stat-icon-green">
              <svg class="stat-icon-green-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div class="stat-details">
              <p class="section-label">Active Users</p>
              <p class="section-value">{{ activeUsers }}</p>
            </div>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-card-content">
            <div class="stat-icon-green">
              <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            </div>
            <div class="stat-details">
              <p class="section-label">Patients</p>
              <p class="section-value">{{ patientCount }}</p>
            </div>
          </div>
        </div>
         
        <div class="stat-card">
          <div class="stat-card-content">
            <div class="stat-icon-orange">
              <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
              </svg>
            </div>
            <div class="stat-details">
              <p class="section-label">Doctors</p>
              <p class="section-value">{{ doctorCount }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <DataTable :data="formattedData" :columns="columns" :enable-row-selection="true"  @update:selectedRows="handleSelectedRowsChange" v-model="internalRowSelectionState"/>
    
    <div v-if="selectedUsers.length > 0" class="mt-4 p-4 border rounded-md bg-blue-50">
      <h3 class="font-semibold mb-2">Selected Users:</h3>
      <ul>
        <li v-for="user in selectedUsers" :key="user.id">
          {{ user.first_name }} {{ user.last_name }} ({{ user.email }})
        </li>
      </ul>
      <button @click="clearSelection" class="btn-primary mt-2">Clear Selection</button>
    </div>
  </div>

  <!-- Add/Edit Modal -->
  <BaseModal
    v-model="showModal"
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
            v-model="form.email"
            placeholder="Enter email address"
            :error="errors.email"
            :frontend-error="getFrontendError('email')" 
            :backend-error="getBackendError('email',  backendErrors)"
            required
          />
        </div>

        <div class="mb-1">
          <Label label="Phone" for="phone"/>
          <BaseInput
            id="phone"
            v-model="form.phone"
            placeholder="Enter phone number"
            :frontend-error="getFrontendError('phone')" 
            :backend-error="getBackendError('phone',  backendErrors)"
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
            :frontend-error="getFrontendError('phone')" 
            :backend-error="getBackendError('phone',  backendErrors)"
          />
        </div>

        <!-- Emergency Contact -->
        <div class="col-span-2">
          <h4 class="text-lg font-medium text-gray-900 mb-4 mt-6">Emergency Contact</h4>
        </div>

        <div>
          <Label label="Contact Name" for="emergency_contact_name"  :required="false"/>
          <BaseInput
            id="emergency_contact_name"
            v-model="form.emergency_contact_name"
            placeholder="Enter emergency contact name"
          />
        </div>

        <div>
          <Label label="Contact Phone" for="emergency_contact_phone" :required="false"/>
          <BaseInput
            :required="false"
            id="emergency_contact_phone"
            v-model="form.emergency_contact_phone"
            placeholder="Enter emergency contact phone"
          />
        </div>

        <!-- Password (only for new users) -->
        <div v-if="!isEditing" class="col-span-2">
          <h4 class="text-lg font-medium text-gray-900 mb-4 mt-6">Account Security</h4>
        </div>

        <div v-if="!isEditing">
          <Label label="Password" for="password" required/>
          <BaseInput
            id="password"
            type="password"
            v-model="form.password"
            placeholder="Enter password"
            :error="errors.password"
            required
          />
        </div>

        <div v-if="!isEditing">
          <Label label="Confirm Password" for="password_confirmation" required/>
          <BaseInput
            id="password_confirmation"
            type="password"
            v-model="form.password_confirmation"
            placeholder="Confirm password"
            :error="errors.password_confirmation"
            required
          />
        </div>

        <!-- Status (only for editing) -->
        <div v-if="isEditing" class="col-span-2 flex justify-end">
          <FieldWrapper label="Status">
            <span class="text-sm mr-3 text-gray-600">
              {{ form.is_active === 1 ? 'Active' : 'Inactive' }}
            </span>
            <div
              @click="form.is_active = form.is_active === 1 ? 0 : 1"
              class="relative inline-flex h-6 w-11 items-center rounded-full cursor-pointer transition-colors"
              :class="form.is_active === 1 ? 'bg-blue-600' : 'bg-gray-300'"
            >
              <span
                class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-300 ease-in-out"
                :class="form.is_active === 1 ? 'translate-x-6' : 'translate-x-1'"
              />
            </div>
          </FieldWrapper>
        </div>
      </div>

      <!-- Buttons -->
      <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
        <button
          type="button"
          @click="showModal = false"
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
import { ref, computed, reactive, onMounted, onUnmounted, h } from 'vue'
import BaseInput from '@/components/Form/BaseInput.vue'
import BaseModal from '@/components/BaseModal.vue'
import FieldWrapper from '@/components/Form/FieldWrapper.vue'
import Label from '@/components/Form/Label.vue'
import BaseTextArea from '@/components/Form/BaseTextArea.vue'
import VueDatePicker from '@vuepic/vue-datepicker'
import Multiselect from 'vue-multiselect'
import { useFormValidation } from '@/composables/useFormValidation'
import { required, email, sameAs, helpers, minLength, maxLength } from '@vuelidate/validators'
import { useAuthStore } from '@/stores/auth'
import { useNotify } from '@/composables/useNotify'
import VueGoodTableNext from '@/components/Table/VueGoodTableNext.vue'
import DataTable from '@/components/Table/TanStackTable.vue' 
import StatusButtonRenderer from './StatusButtonRenderer.vue'
import { formatDate } from '@/utils/dateHelpers';

const columns = ref([
  { label: 'First Name', field: 'first_name'},
  { label: 'Middle Name', field: 'middle_name'},
  { label: 'Last Name', field: 'last_name'},
  { label: 'Email', field: 'email'},
  { label: 'Phone', field: 'phone'},
  { label: 'Birth Date', field: 'birth_date'},
  { label: 'Gender', field: 'gender'},
  { label: 'Address', field: 'address'},
  { label: 'Emergency Contact #', field: 'address'},
  { label: 'Emergency Contact #', field: 'address'},
  { label: 'Role', field: 'role'},
  { label: 'Status', field: 'status', 
    cell: (info) => h(StatusButtonRenderer, {
      rowData: info.row.original,
      onUpdateStatus: (updatedRow) => {
        const index = data.value.findIndex(r => r.id === updatedRow.id)
        if (index !== -1) {
          data.value[index] = {...updatedRow}
        }
      },
      onEditRow: (row) => {
        console.log(row,'onEditRow');
        alert('Edit clicked for ' + row.first_name)
      },
 
      onDeleteRow: (row) => {
        console.log(row,'onEditRow');
        alert('Delete clicked for ' + row.first_name)
      }
    }),
  },
]);
// Role options
const roleOptions = ref([
  { value: 'patient', label: 'Patient' },
  { value: 'doctor', label: 'Doctor' },
  { value: 'admin', label: 'Admin' },
  { value: 'receptionist', label: 'Receptionist' }
])

// Test users data matching database structure
const data = ref([
  {
    id: 1,
    first_name: 'John',
    middle_name: 'Michael',
    last_name: 'Doe',
    email: 'john.doe@example.com',
    email_verified_at: '2024-01-15T10:30:00Z',
    phone: '+1234567890',
    birth_date: '1985-03-15',
    gender: 'male',
    address: '123 Main St, Anytown, ST 12345',
    emergency_contact_name: 'Jane Doe',
    emergency_contact_phone: '+1234567891',
    role: 'patient',
    avatar: null,
    is_active: true,
    last_login_at: '2024-02-01T09:15:00Z',
    created_at: '2024-01-10T08:00:00Z',
    updated_at: '2024-02-01T09:15:00Z'
  },
  {
    id: 2,
    first_name: 'Sarah',
    middle_name: null,
    last_name: 'Johnson',
    email: 'sarah.johnson@hospital.com',
    email_verified_at: '2024-01-20T14:00:00Z',
    phone: '+1234567892',
    birth_date: '1978-07-22',
    gender: 'female',
    address: '456 Oak Ave, Medical District, ST 12346',
    emergency_contact_name: 'Robert Johnson',
    emergency_contact_phone: '+1234567893',
    role: 'doctor',
    avatar: null,
    is_active: true,
    last_login_at: '2024-02-05T07:30:00Z',
    created_at: '2024-01-15T10:00:00Z',
    updated_at: '2024-02-05T07:30:00Z'
  },
  {
    id: 3,
    first_name: 'John',
    middle_name: 'Michael',
    last_name: 'Doe',
    email: 'john.doe@example.com',
    email_verified_at: '2024-01-15T10:30:00Z',
    phone: '+1234567890',
    birth_date: '1985-03-15',
    gender: 'male',
    address: '123 Main St, Anytown, ST 12345',
    emergency_contact_name: 'Jane Doe',
    emergency_contact_phone: '+1234567891',
    role: 'patient',
    avatar: null,
    is_active: true,
    last_login_at: '2024-02-01T09:15:00Z',
    created_at: '2024-01-10T08:00:00Z',
    updated_at: '2024-02-01T09:15:00Z'
  },
  {
    id: 4,
    first_name: 'Sarah',
    middle_name: null,
    last_name: 'Johnson',
    email: 'sarah.johnson@hospital.com',
    email_verified_at: '2024-01-20T14:00:00Z',
    phone: '+1234567892',
    birth_date: '1978-07-22',
    gender: 'female',
    address: '456 Oak Ave, Medical District, ST 12346',
    emergency_contact_name: 'Robert Johnson',
    emergency_contact_phone: '+1234567893',
    role: 'doctor',
    avatar: null,
    is_active: true,
    last_login_at: '2024-02-05T07:30:00Z',
    created_at: '2024-01-15T10:00:00Z',
    updated_at: '2024-02-05T07:30:00Z'
  },
  {
    id: 5,
    first_name: 'John',
    middle_name: 'Michael',
    last_name: 'Doe',
    email: 'john.doe@example.com',
    email_verified_at: '2024-01-15T10:30:00Z',
    phone: '+1234567890',
    birth_date: '1985-03-15',
    gender: 'male',
    address: '123 Main St, Anytown, ST 12345',
    emergency_contact_name: 'Jane Doe',
    emergency_contact_phone: '+1234567891',
    role: 'patient',
    avatar: null,
    is_active: true,
    last_login_at: '2024-02-01T09:15:00Z',
    created_at: '2024-01-10T08:00:00Z',
    updated_at: '2024-02-01T09:15:00Z'
  },
  {
    id: 6,
    first_name: 'Sarah',
    middle_name: null,
    last_name: 'Johnson',
    email: 'sarah.johnson@hospital.com',
    email_verified_at: '2024-01-20T14:00:00Z',
    phone: '+1234567892',
    birth_date: '1978-07-22',
    gender: 'female',
    address: '456 Oak Ave, Medical District, ST 12346',
    emergency_contact_name: 'Robert Johnson',
    emergency_contact_phone: '+1234567893',
    role: 'doctor',
    avatar: null,
    is_active: true,
    last_login_at: '2024-02-05T07:30:00Z',
    created_at: '2024-01-15T10:00:00Z',
    updated_at: '2024-02-05T07:30:00Z'
  },
  {
    id: 7,
    first_name: 'John',
    middle_name: 'Michael',
    last_name: 'Doe',
    email: 'john.doe@example.com',
    email_verified_at: '2024-01-15T10:30:00Z',
    phone: '+1234567890',
    birth_date: '1985-03-15',
    gender: 'male',
    address: '123 Main St, Anytown, ST 12345',
    emergency_contact_name: 'Jane Doe',
    emergency_contact_phone: '+1234567891',
    role: 'patient',
    avatar: null,
    is_active: true,
    last_login_at: '2024-02-01T09:15:00Z',
    created_at: '2024-01-10T08:00:00Z',
    updated_at: '2024-02-01T09:15:00Z'
  },
  {
    id: 8,
    first_name: 'Sarah',
    middle_name: null,
    last_name: 'Johnson',
    email: 'sarah.johnson@hospital.com',
    email_verified_at: '2024-01-20T14:00:00Z',
    phone: '+1234567892',
    birth_date: '1978-07-22',
    gender: 'female',
    address: '456 Oak Ave, Medical District, ST 12346',
    emergency_contact_name: 'Robert Johnson',
    emergency_contact_phone: '+1234567893',
    role: 'doctor',
    avatar: null,
    is_active: true,
    last_login_at: '2024-02-05T07:30:00Z',
    created_at: '2024-01-15T10:00:00Z',
    updated_at: '2024-02-05T07:30:00Z'
  },
  {
    id: 9,
    first_name: 'Michael',
    middle_name: 'David',
    last_name: 'Smith',
    email: 'admin@hospital.com',
    email_verified_at: '2024-01-01T00:00:00Z',
    phone: '+1234567894',
    birth_date: '1980-12-10',
    gender: 'male',
    address: '789 Pine St, Admin Block, ST 12347',
    emergency_contact_name: 'Lisa Smith',
    emergency_contact_phone: '+1234567895',
    role: 'admin',
    avatar: null,
    is_active: true,
    last_login_at: '2024-02-06T08:00:00Z',
    created_at: '2024-01-01T00:00:00Z',
    updated_at: '2024-02-06T08:00:00Z'
  },
  {
    id: 10,
    first_name: 'Emily',
    middle_name: 'Rose',
    last_name: 'Davis',
    email: 'emily.davis@hospital.com',
    email_verified_at: '2024-01-25T12:00:00Z',
    phone: '+1234567896',
    birth_date: '1992-05-18',
    gender: 'female',
    address: '321 Elm St, Reception Area, ST 12348',
    emergency_contact_name: 'Mark Davis',
    emergency_contact_phone: '+1234567897',
    role: 'receptionist',
    avatar: null,
    is_active: true,
    last_login_at: '2024-02-05T16:45:00Z',
    created_at: '2024-01-20T09:00:00Z',
    updated_at: '2024-02-05T16:45:00Z'
  },
  {
    id: 11,
    first_name: 'Robert',
    middle_name: null,
    last_name: 'Wilson',
    email: 'robert.wilson@example.com',
    email_verified_at: null,
    phone: null,
    birth_date: '1965-09-30',
    gender: 'male',
    address: '654 Maple Dr, Suburbia, ST 12349',
    emergency_contact_name: null,
    emergency_contact_phone: null,
    role: 'patient',
    avatar: null,
    is_active: false,
    last_login_at: null,
    created_at: '2024-01-30T14:30:00Z',
    updated_at: '2024-02-01T10:00:00Z'
  }
])

const selectedUsers = ref([]);
const internalRowSelectionState = ref({});

const clearSelection = (rows) => {
  // To clear selection, you need to update the internal rowSelection state in DataTable.
  // This is where `v-model` (update:modelValue) comes in handy.
  internalRowSelectionState.value = {};
  selectedUsers.value = []; // Also clear the parent's array
};

const handleSelectedRowsChange = (rows) => {
  selectedUsers.value = rows;
  console.log('Selected Rows:', selectedUsers.value); // For debugging
};

// Reactive state
const backendErrors = ref(null)
const showModal = ref(false)
const isEditing = ref(false)
const loading = ref(false)
const searchQuery = ref('')
const statusFilter = ref('')
const roleFilter = ref('')
const genderFilter = ref('')

const { notifyToast } = useNotify()
const authStore = useAuthStore()

const passwordValue = computed(() => form.password)

function formatData(data){
    return {
      ...data,
      birth_date: formatDate(data.birth_date)
    }
}

const formattedData = computed(() => data.value.map(data => formatData(data)));

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
  role: { required },
}))


function toggleStatus(index) {
  console.log('WTF', index);
  data.value[index].is_active = !data.value[index].is_active;
}

const genderOptions = ref([
  { label: 'Male', value: 'male' },
  { label: 'Female', value: 'female' },
  { label: 'Other', value: 'other' }
])

function handleStatusUpdate({ id, is_active, status }) {
  const user = data.value.find(user => user.id === id)
  if (user) {
    user.is_active = is_active
    user.status = status
  }
}


// Form data
const form = reactive({
  id: null,
  first_name: '',
  middle_name: '',
  last_name: '',
  email: '',
  phone: '',
  birth_date: '',
  gender: '',
  address: '',
  emergency_contact_name: '',
  emergency_contact_phone: '',
  role: '',
  password: '',
  password_confirmation: '',
  is_active: 1
})

const { $v, getFrontendError, getBackendError } = useFormValidation(form, rules)

// Form errors
const errors = reactive({
  first_name: '',
  last_name: '',
  email: '',
  role: '',
  password: '',
  password_confirmation: ''
})

// Return display string for a role
function formatRole(role) {
  const map = {
    admin: 'Administrator',
    doctor: 'Doctor',
    patient: 'Patient',
    receptionist: 'Receptionist',
  }
  return map[role] || 'Unknown'
}

// Return Tailwind CSS class for role badges
function getRoleColor(role) {
  const map = {
    admin: 'bg-red-100 text-red-800',
    doctor: 'bg-green-100 text-green-800',
    patient: 'bg-blue-100 text-blue-800',
    receptionist: 'bg-yellow-100 text-yellow-800',
  }
  return map[role] || 'bg-gray-100 text-gray-800'
}

// Optional: format date of birth nicely
// function formatDate(date) {
//   if (!date) return 'N/A'
//   const options = { year: 'numeric', month: 'long', day: 'numeric' }
//   return new Date(date).toLocaleDateString(undefined, options)
// }

// Computed properties
const activeUsers = computed(() => 
  data.value.filter(user => user.is_active).length
)

const patientCount = computed(() => 
  data.value.filter(user => user.role === 'patient').length
)

const doctorCount = computed(() => 
  data.value.filter(user => user.role === 'doctor').length
)

const filteredUsers = computed(() => {
  let filtered = data.value

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(user =>
      user.first_name.toLowerCase().includes(query) ||
      user.last_name.toLowerCase().includes(query) ||
      user.email.toLowerCase().includes(query) ||
      (user.middle_name && user.middle_name.toLowerCase().includes(query))
    )
  }

  if (roleFilter.value) {
    filtered = filtered.filter(user => user.role === roleFilter.value)
  }

  if (statusFilter.value !== '') {
    const isActive = statusFilter.value === '1'
    filtered = filtered.filter(user => user.is_active === isActive)
  }

  if (genderFilter.value) {
    filtered = filtered.filter(user => user.gender === genderFilter.value)
  }

  return filtered
})


function resetForm() {
    form.value = {
        id: null,
        first_name: '',
        middle_name: '',
        last_name: '',
        email: '',
        phone: '',
        birth_date: '',
        gender: '',
        address: '',
        emergency_contact_name: '',
        emergency_contact_phone: '',
        role: '',
        password: '',
        password_confirmation: '',
        is_active: 1
    }
}

// Methods
const openAddModal = () => {
  isEditing.value = false
  showModal.value = true
}

const openEditModal = (user) => {
  isEditing.value = true
  showModal.value = true
  form.id = user.id
  form.first_name = user.first_name
  form.middle_name = user.middle_name || ''
  form.last_name = user.last_name
  form.email = user.email
  form.phone = user.phone || ''
  form.birth_date = user.birth_date || ''
  form.gender = user.gender || ''
  form.address = user.address
  form.emergency_contact_name = user.emergency_contact_name ||  '',
  form.emergency_contact_phone = user.emergency_contact_phone ||  '',
  form.role = user.role ||  '',
  form.password = user.password ||  '',
  form.password_confirmation = user.password_confirmation ||  '',
  form.is_active = user.is_active ? 1 : 0
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

const gridApi = ref(null)

function handleGridReady(params) {
  gridApi.value = params.api
  gridApi.value.sizeColumnsToFit()
}

function onResize() {
  if (gridApi.value) {
    gridApi.value.sizeColumnsToFit()
  }
}

onMounted(() => {
  window.addEventListener('resize', onResize)
})

onUnmounted(() => {
  window.removeEventListener('resize', onResize)
})

</script>
