<template>
  <div class="page-container">
    <!-- Header -->
    <div class="content-card">
       <!-- Header -->
       <div class="card-header">
         <div class="header-content">
           <div>
              <h1 class="page-title">Services Management</h1>
              <p class="page-subtitle">Manage services and their information</p>
           </div>
           <button @click="openAddModal" class="btn-primary">
             <svg class="action-icon-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
             </svg>
             Add Service
           </button>
         </div>
       </div>
      
       <!-- Stats Cards -->
       <div class="stats-grid">
         <div class="stat-card">
           <div class="stat-card-content">
             <div class="stat-icon-blue">
               <svg class="stat-icon-blue-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h4M9 7h6m-6 4h6m-6 4h6" />
               </svg>
             </div>
             <div class="stat-details">
               <p class="section-label">Total Services</p>
               <p class="section-value">{{ services.length }}</p>
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
               <p class="section-label">Active Services</p>
               <p class="section-value">{{ activeServices }}</p>
             </div>
           </div>
         </div>
         
         <div class="stat-card">
           <div class="stat-card-content">
             <div class="stat-icon-orange">
               <svg class="stat-icon-orange-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
               </svg>
             </div>
             <div class="stat-details">
               <p class="section-label">Inactive Services</p>
               <p class="section-value">{{ inactiveServices }}</p>
             </div>
           </div>
         </div>
       </div>
    </div>
    
    <div class="content-card">
      <!-- Search and Filter -->
      <div class="search-filter-card">
        <div class="search-filter-grid">
          <div class="search-input-wrapper">
            <BaseInput
              v-model="searchQuery"
              placeholder="Search services..."
              label=""
              :required="false"
            />
          </div>
          <div class="filter-select-wrapper">
            <select
              v-model="departmentFilter"
              class="filter-select"
            >
              <option value="">All Departments</option>
              <option v-for="dept in departmentOptions" :key="dept.value" :value="dept.value">
                {{ dept.label }}
              </option>
            </select>
          </div>
          <div class="filter-select-wrapper">
            <select
              v-model="statusFilter"
              class="filter-select"
            >
              <option value="">All Status</option>
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
          <div class="filter-select-wrapper">
            <select
              v-model="fastingFilter"
              class="filter-select"
            >
              <option value="">All Services</option>
              <option value="1">Requires Fasting</option>
              <option value="0">No Fasting</option>
            </select>
          </div>
        </div>
      </div>
      
      <!-- Services Table -->
      <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Service
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Department
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Duration
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Price
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Requirements
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Status
                </th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="service in filteredServices" :key="service.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                      <div class="h-10 w-10 rounded-lg bg-purple-100 flex items-center justify-center">
                        <span class="text-purple-600 font-medium text-sm">
                          {{ service.name.charAt(0).toUpperCase() }}
                        </span>
                      </div>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">{{ service.name }}</div>
                      <div class="text-sm text-gray-500">{{ service.code }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ getDepartmentName(service.department_id) }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ formatDuration(service.duration_minutes) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  ${{ service.base_price.toFixed(2) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex flex-col gap-1">
                    <span
                      v-if="service.requires_fasting"
                      class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800"
                    >
                      Fasting Required
                    </span>
                    <span
                      v-if="service.preparation_instructions"
                      class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800"
                    >
                      Has Instructions
                    </span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    :class="[
                      'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                      service.is_active
                        ? 'bg-green-100 text-green-800'
                        : 'bg-red-100 text-red-800'
                    ]"
                  >
                    {{ service.is_active ? 'Active' : 'Inactive' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex justify-end gap-2">
                    <button
                      @click="openEditModal(service)"
                      class="text-blue-600 hover:text-blue-900 p-1 rounded"
                      title="Edit"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </button>
                    <button
                      @click="deleteService(service.id)"
                      class="text-red-600 hover:text-red-900 p-1 rounded"
                      title="Delete"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Add/Edit Modal -->
  <BaseModal
    v-model="showModal"
    max-width="4xl"
    @closed="resetForm"
  >
    <div class="modal-header">
      <h3 class="modal-title">
        {{ isEditing ? 'Edit Service' : 'Add New Service' }}
      </h3>
    </div>

    <form @submit.prevent="saveService" class="p-6">
      <!-- Two per row -->
      <Label label="Name" for="name"/>
      <BaseInput
        id="name"
        v-model="form.name"
        label="Service Name"
        placeholder="Enter service name"
      />

      <Label label="Code" for="code"/>
      <BaseInput
        id="code"
        v-model="form.code"
        label="Service Code"
        placeholder="Enter service code"
      />

      <!-- Custom Select -->
      <FieldWrapper label="Department">
        <multiselect
          v-model="form.department_id"
          :options="departmentOptions"
          label="label"
          placeholder="Select Department"
        />
      </FieldWrapper>

      <Label label="Duration" for="duration_minutes"/>
      <BaseInput
        id="duration_minutes"
        type="number"
        v-model="form.duration_minutes"
        label="Duration (Minutes)"
        placeholder="Enter duration in minutes"
      />

      <Label label="Base Price" for="base_price"/>
      <BaseInput
        id="base_price"
        type="number"
        v-model="form.base_price"
        placeholder="Enter base price"
      />

      <!-- Description textarea - full width -->
      <FieldWrapper label="Description">
        <BaseTextArea
          v-model="form.description"
          rows="3"
          placeholder="Enter department description"
        ></BaseTextArea>
      </FieldWrapper>

      <!-- Preparation Instructions textarea - full width -->
    
      <FieldWrapper label="Preparation Instructions">
        <BaseTextArea
          v-model="form.preparation_instructions"
          rows="3"
          placeholder="Enter preparation instructions for the patient"
        ></BaseTextArea>
      </FieldWrapper>

      <!-- Checkbox - full width -->
      <div class="col-span-2">
        <div class="flex items-center">
          <BaseInput
            type="checkbox"
            v-model="form.requires_fasting"
            id="requires_fasting"
          />
         
          <Label for="requires_fasting" label="Requires Fasting" class="ml-2"/>
        </div>
        <p class="text-xs text-gray-500 mt-1">Check if patient needs to fast before this service</p>
      </div>

      <div  class="flex justify-end">
        <FieldWrapper label="Status" v-if="isEditing">
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
          <span v-else>{{ isEditing ? 'Update Service' : 'Create Service' }}</span>
        </button>
      </div>
    </form>

  </BaseModal>
</template>

<script setup>
import { ref, computed, reactive } from 'vue'
import BaseInput from '@/components/Form/BaseInput.vue'
import BaseModal from '@/components/BaseModal.vue'
import Multiselect from 'vue-multiselect'
import FieldWrapper from '@/components/Form/FieldWrapper.vue'
import Label from '@/components/Form/Label.vue'
import BaseTextArea from '@/components/Form/BaseTextArea.vue'

// Test departments data (for reference)
const departmentOptions = ref([
  { value: 1, label: 'Emergency Department (ED)' },
  { value: 2, label: 'Cardiology (CARD)' },
  { value: 3, label: 'Radiology (RAD)' },
  { value: 4, label: 'Pediatrics (PED)' },
  { value: 5, label: 'Laboratory (LAB)' },
  { value: 6, label: 'Orthopedics (ORTHO)' }
])

// Test services data matching database structure
const services = ref([
  {
    id: 1,
    department_id: 1,
    name: 'Emergency Consultation',
    code: 'ED-CONS',
    description: 'Initial emergency consultation and assessment',
    duration_minutes: 30,
    base_price: 150.00,
    preparation_instructions: null,
    requires_fasting: false,
    is_active: true,
    created_at: '2024-01-15T10:30:00Z',
    updated_at: '2024-01-15T10:30:00Z'
  },
  {
    id: 2,
    department_id: 2,
    name: 'ECG Test',
    code: 'CARD-ECG',
    description: 'Electrocardiogram test to check heart rhythm',
    duration_minutes: 15,
    base_price: 75.00,
    preparation_instructions: 'Wear comfortable clothing. Avoid caffeine 2 hours before test.',
    requires_fasting: false,
    is_active: true,
    created_at: '2024-01-20T14:15:00Z',
    updated_at: '2024-01-20T14:15:00Z'
  },
  {
    id: 3,
    department_id: 2,
    name: 'Stress Test',
    code: 'CARD-STRESS',
    description: 'Cardiac stress test to evaluate heart function under stress',
    duration_minutes: 60,
    base_price: 300.00,
    preparation_instructions: 'Wear comfortable exercise clothing and shoes. Fast for 3 hours before test.',
    requires_fasting: true,
    is_active: true,
    created_at: '2024-01-22T09:00:00Z',
    updated_at: '2024-01-22T09:00:00Z'
  },
  {
    id: 4,
    department_id: 3,
    name: 'Chest X-Ray',
    code: 'RAD-CXR',
    description: 'Standard chest X-ray examination',
    duration_minutes: 10,
    base_price: 85.00,
    preparation_instructions: 'Remove all jewelry and metal objects from chest area.',
    requires_fasting: false,
    is_active: true,
    created_at: '2024-01-25T11:30:00Z',
    updated_at: '2024-01-25T11:30:00Z'
  },
  {
    id: 5,
    department_id: 3,
    name: 'CT Scan',
    code: 'RAD-CT',
    description: 'Computed tomography scan',
    duration_minutes: 45,
    base_price: 450.00,
    preparation_instructions: 'Fast for 4 hours before scan. Drink contrast solution 1 hour before if required.',
    requires_fasting: true,
    is_active: true,
    created_at: '2024-01-28T08:45:00Z',
    updated_at: '2024-01-28T08:45:00Z'
  },
  {
    id: 6,
    department_id: 5,
    name: 'Blood Test Panel',
    code: 'LAB-BLOOD',
    description: 'Comprehensive blood test panel',
    duration_minutes: 5,
    base_price: 120.00,
    preparation_instructions: 'Fast for 12 hours before blood draw. Water is allowed.',
    requires_fasting: true,
    is_active: true,
    created_at: '2024-02-01T07:00:00Z',
    updated_at: '2024-02-01T07:00:00Z'
  },
  {
    id: 7,
    department_id: 4,
    name: 'Pediatric Check-up',
    code: 'PED-CHECKUP',
    description: 'Routine pediatric examination',
    duration_minutes: 25,
    base_price: 100.00,
    preparation_instructions: null,
    requires_fasting: false,
    is_active: false,
    created_at: '2024-02-05T13:20:00Z',
    updated_at: '2024-02-05T13:20:00Z'
  }
])

// Reactive state
const showModal = ref(false)
const isEditing = ref(false)
const loading = ref(false)
const searchQuery = ref('')
const statusFilter = ref('')
const departmentFilter = ref('')
const fastingFilter = ref('')

// Form data
const form = reactive({
  id: null,
  department_id: '',
  name: '',
  code: '',
  description: '',
  duration_minutes: '',
  base_price: '',
  preparation_instructions: '',
  requires_fasting: false,
  is_active: ''
})

// Form errors
const errors = reactive({
  department_id: '',
  name: '',
  code: '',
  duration_minutes: '',
  base_price: '',
  is_active: ''
})

// Computed properties
const activeServices = computed(() => 
  services.value.filter(service => service.is_active).length
)

const inactiveServices = computed(() => 
  services.value.filter(service => !service.is_active).length
)

const fastingServices = computed(() => 
  services.value.filter(service => service.requires_fasting).length
)

const averagePrice = computed(() => {
  if (services.value.length === 0) return 0
  const total = services.value.reduce((sum, service) => sum + service.base_price, 0)
  return (total / services.value.length).toFixed(2)
})

const filteredServices = computed(() => {
  let filtered = services.value

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(service =>
      service.name.toLowerCase().includes(query) ||
      service.code.toLowerCase().includes(query) ||
      (service.description && service.description.toLowerCase().includes(query))
    )
  }

  if (departmentFilter.value) {
    filtered = filtered.filter(service => service.department_id === parseInt(departmentFilter.value))
  }

  if (statusFilter.value !== '') {
    const isActive = statusFilter.value === '1'
    filtered = filtered.filter(service => service.is_active === isActive)
  }

  if (fastingFilter.value !== '') {
    const requiresFasting = fastingFilter.value === '1'
    filtered = filtered.filter(service => service.requires_fasting === requiresFasting)
  }

  return filtered
})

// Methods
const openAddModal = () => {
  isEditing.value = false
  showModal.value = true
}

const openEditModal = (service) => {
  isEditing.value = true
  form.id = service.id
  form.department_id = service.department_id
  form.name = service.name
  form.code = service.code
  form.description = service.description || ''
  form.duration_minutes = service.duration_minutes
  form.base_price = service.base_price
  form.preparation_instructions = service.preparation_instructions || ''
  form.requires_fasting = service.requires_fasting
  form.is_active = service.is_active

  showModal.value = true
}

const resetForm = () => {
  Object.keys(form).forEach(key => {
    if (key === 'id') {
      form[key] = null
    } else if (key === 'requires_fasting') {
      form[key] = false
    } else {
      form[key] = ''
    }
  })
  Object.keys(errors).forEach(key => {
    errors[key] = ''
  })
}

const validateForm = () => {
  let isValid = true
  
  // Reset errors
  Object.keys(errors).forEach(key => {
    errors[key] = ''
  })

  if (!form.department_id) {
    errors.department_id = 'Department is required'
    isValid = false
  }

  if (!form.name.trim()) {
    errors.name = 'Service name is required'
    isValid = false
  } else if (form.name.length > 100) {
    errors.name = 'Service name must not exceed 100 characters'
    isValid = false
  }

  if (!form.code.trim()) {
    errors.code = 'Service code is required'
    isValid = false
  } else if (form.code.length > 20) {
    errors.code = 'Service code must not exceed 20 characters'
    isValid = false
  }

  if (!form.duration_minutes || form.duration_minutes <= 0) {
    errors.duration_minutes = 'Duration must be greater than 0'
    isValid = false
  }

  if (form.base_price === '' || form.base_price < 0) {
    errors.base_price = 'Base price must be 0 or greater'
    isValid = false
  }

  if (form.is_active === '') {
    errors.is_active = 'Status is required'
    isValid = false
  }

  return isValid
}

const saveService = async () => {
  if (!validateForm()) return

  loading.value = true

  try {
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 1000))

    const now = new Date().toISOString()

    if (isEditing.value) {
      const index = services.value.findIndex(service => service.id === form.id)
      if (index !== -1) {
        services.value[index] = {
          ...services.value[index],
          department_id: parseInt(form.department_id),
          name: form.name,
          code: form.code,
          description: form.description || null,
          duration_minutes: parseInt(form.duration_minutes),
          base_price: parseFloat(form.base_price),
          preparation_instructions: form.preparation_instructions || null,
          requires_fasting: form.requires_fasting,
          is_active: form.is_active,
          updated_at: now
        }
      }
    } else {
      const newId = Math.max(...services.value.map(s => s.id)) + 1
      services.value.push({
        id: newId,
        department_id: parseInt(form.department_id),
        name: form.name,
        code: form.code,
        description: form.description || null,
        duration_minutes: parseInt(form.duration_minutes),
        base_price: parseFloat(form.base_price),
        preparation_instructions: form.preparation_instructions || null,
        requires_fasting: form.requires_fasting,
        is_active: form.is_active,
        created_at: now,
        updated_at: now
      })
    }

    closeModal()
  } catch (error) {
    console.error('Failed to save service:', error)
  } finally {
    loading.value = false
  }
}


// Delete service
const deleteService = async (id) => {
  if (!confirm('Are you sure you want to delete this service?')) return

  loading.value = true

  try {
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 500))
    services.value = services.value.filter(service => service.id !== id)
  } catch (error) {
    console.error('Failed to delete service:', error)
  } finally {
    loading.value = false
  }
}

const formatDuration = (minutes) => {
  if (!minutes || isNaN(minutes)) return 'N/A'

  const hrs = Math.floor(minutes / 60)
  const mins = minutes % 60

  if (hrs > 0 && mins > 0) return `${hrs}h ${mins}m`
  if (hrs > 0) return `${hrs}h`
  return `${mins}m`
}


const getDepartmentName = (id) => {
  const dept = departmentOptions.value.find(d => d.id === id)
  return dept ? dept.label : 'Unknown'
}



// Close modal
const closeModal = () => {
  showModal.value = false
  resetForm()
}
</script>