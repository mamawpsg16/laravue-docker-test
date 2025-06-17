<template>
   <div class="page-container">
      <div class="content-card">
       <!-- Header -->
       <div class="card-header">
         <div class="header-content">
           <div>
             <h1 class="page-title">Department Management</h1>  
             <p class="page-subtitle">Manage departments and their information</p>
           </div>
           <button @click="openAddModal" class="btn-primary">
             <svg class="action-icon-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
             </svg>
             Add Department
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
               <p class="section-label">Total Departments</p>
               <p class="section-value">{{ departments.length }}</p>
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
               <p class="section-label">Active Departments</p>
               <p class="section-value">{{ activeDepartments }}</p>
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
               <p class="section-label">Inactive Departments</p>
               <p class="section-value">{{ inactiveDepartments }}</p>
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
                placeholder="Search departments..."
                label=""
                :required="false"
              />
            </div>
            <div class="filter-select-wrapper">
              <select v-model="statusFilter" class="filter-select">
                <option value="">All Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
              </select>
            </div>
          </div>
        </div>
    
        <!-- Departments Table -->
        <div class="data-table-card">
          <div class="data-table-wrapper">
            <table class="data-table">
              <thead class="table-header">
                <tr>
                  <th class="table-header-cell">Department</th>
                  <th class="table-header-cell">Head Doctor</th>
                  <th class="table-header-cell">Operating Hours</th>
                  <th class="table-header-cell">Status</th>
                  <th class="table-header-cell">Created</th>
                  <th class="table-header-cell-right">Actions</th>
                </tr>
              </thead>
              <tbody class="table-body">
                <tr v-for="department in filteredDepartments" :key="department.id" class="table-row">
                  <td class="table-cell">
                    <div class="item-avatar-wrapper">
                      <div class="item-avatar">
                        <div class="item-avatar-blue">
                          <span class="item-avatar-text-blue">
                            {{ department.name.charAt(0).toUpperCase() }}
                          </span>
                        </div>
                      </div>
                      <div class="item-details">
                        <div class="item-name">{{ department.name }}</div>
                        <div class="item-code">{{ department.code }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="table-cell">
                    <div class="item-info">{{ department.head_doctor_id || 'Not Assigned' }}</div>
                  </td>
                  <td class="table-cell">
                    <div v-if="department.operating_hours" class="item-info">
                      {{ formatOperatingHours(department.operating_hours) }}
                    </div>
                    <div v-else class="item-code">Not Set</div>
                  </td>
                  <td class="table-cell">
                    <span :class="department.is_active ? 'status-badge-active' : 'status-badge-inactive'">
                      {{ department.is_active ? 'Active' : 'Inactive' }}
                    </span>
                  </td>
                  <td class="table-cell item-code">
                    {{ formatDate(department.created_at) }}
                  </td>
                  <td class="table-cell-right">
                    <div class="action-buttons">
                      <button @click="openEditModal(department)" class="action-btn-edit" title="Edit">
                        <svg class="action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                      </button>
                      <button @click="deleteDepartment(department.id)" class="action-btn-delete" title="Delete">
                        <svg class="action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
  <BaseModal v-model="showModal" @closed="resetForm">
    <div class="modal-header">
      <h3 class="modal-title">
        {{ isEditing ? 'Edit Department' : 'Add New Department' }}
      </h3>
    </div>

     <form @submit.prevent="saveDepartment" class="p-6">
      <Label label="Name" for="name"/>
      <BaseInput
        id="name"
        v-model="form.name"
        label="Department Name"
        placeholder="Enter department name"
        :frontend-error="errors.name"
        required
        maxlength="100"
      />

      <Label label="Code" for="code"/>
      <BaseInput
        id="code"
        v-model="form.code"
        label="Department Code"
        placeholder="Enter department code (max 10 chars)"
        :frontend-error="errors.code"
        required
        maxlength="10"
      />

      <FieldWrapper label="Head Doctor">
        <multiselect
          v-model="form.doctor"
          :options="doctorOptions"
          label="label"
          placeholder="Select Head Doctor"
        />
      </FieldWrapper>   

      <FieldWrapper label="Description">
        <BaseTextArea
          v-model="form.description"
          rows="3"
          placeholder="Enter department description"
        ></BaseTextArea>
      </FieldWrapper>
      
      <!-- Operating Hours Section -->
      <div class="col-span-2">
        <label class="block text-xs font-medium text-gray-700 mb-2">
          Operating Hours
        </label>
        <div class="border border-gray-200 rounded-lg p-3 sm:p-4">
          <div class="space-y-3 sm:space-y-4">
            <div v-for="day in daysOfWeek" :key="day.key" 
                class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3 p-2 sm:p-0">
              
      <!-- Day checkbox and label -->
      <div class="flex items-center space-x-2 sm:space-x-3 min-w-0 flex-shrink-0">
        <input
          type="checkbox"
          :id="`${day.key}_enabled`"
          v-model="operatingHours[day.key].enabled"
          class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
        />
        <label :for="`${day.key}_enabled`" 
              class="text-sm font-medium text-gray-700 w-12 sm:w-16 flex-shrink-0">
          {{ day.label }}
        </label>
      </div>
      
      <!-- Time inputs -->
      <div v-if="operatingHours[day.key].enabled" 
          class="flex items-center gap-2 sm:gap-3 ml-6 sm:ml-0 flex-wrap sm:flex-nowrap">
        <input
          type="time"
          v-model="operatingHours[day.key].start"
          class="text-xs border border-gray-300 rounded px-2 py-1 focus:ring-2 focus:ring-blue-500 
                w-full sm:w-auto min-w-0 flex-shrink-0"
        />
        <span class="text-xs text-gray-500 whitespace-nowrap flex-shrink-0">to</span>
        <input
          type="time"
          v-model="operatingHours[day.key].end"
          class="text-xs border border-gray-300 rounded px-2 py-1 focus:ring-2 focus:ring-blue-500 
                w-full sm:w-auto min-w-0 flex-shrink-0"
        />
      </div>
    </div>
  </div>
</div>
</div>

<div  class="flex justify-end mt-2">
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

<div class="flex justify-end gap-3 mt-2 pt-6 border-t border-gray-200">
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
  <span v-else>{{ isEditing ? 'Update Department' : 'Create Department' }}</span>
</button>
</div>
</form>
  </BaseModal>
</template>

<script setup>
import { ref, computed, reactive } from 'vue'
import BaseInput from '@/components/Form/BaseInput.vue'
import BaseModal from '@/components/BaseModal.vue'
import FieldWrapper from '@/components/Form/FieldWrapper.vue'
import Label from '@/components/Form/Label.vue'
import BaseTextArea from '@/components/Form/BaseTextArea.vue'
import Multiselect from 'vue-multiselect'

// Test data matching database structure
const statusOptions = ref([
  { label: 'Active', value: 1 },
  { label: 'Inactive', value: 0 },
])

const doctorOptions = ref([
  { value: 1, label: 'Kevin Mensah (KMA)' },
  { value: 2, label: 'Rojenson Lugo (RJL)' },
  { value: 3, label: 'Ricardo Gubat (RGB)' },
  { value: 4, label: 'Rustian Taculog (RTL)' },
  { value: 5, label: 'John Paul Alindog (JPL)' },
])

const departments = ref([
  {
    id: 1,
    name: 'Emergency Department',
    code: 'ED',
    description: 'Provides emergency medical care 24/7',
    head_doctor_id: 'DOC001',
    is_active: true,
    operating_hours: {
      monday: { enabled: true, start: '00:00', end: '23:59' },
      tuesday: { enabled: true, start: '00:00', end: '23:59' },
      wednesday: { enabled: true, start: '00:00', end: '23:59' },
      thursday: { enabled: true, start: '00:00', end: '23:59' },
      friday: { enabled: true, start: '00:00', end: '23:59' },
      saturday: { enabled: true, start: '00:00', end: '23:59' },
      sunday: { enabled: true, start: '00:00', end: '23:59' }
    },
    created_at: '2024-01-15T10:30:00Z',
    updated_at: '2024-01-15T10:30:00Z'
  },
  {
    id: 2,
    name: 'Cardiology',
    code: 'CARD',
    description: 'Specialized care for heart conditions',
    head_doctor_id: 'DOC002',
    is_active: true,
    operating_hours: {
      monday: { enabled: true, start: '08:00', end: '17:00' },
      tuesday: { enabled: true, start: '08:00', end: '17:00' },
      wednesday: { enabled: true, start: '08:00', end: '17:00' },
      thursday: { enabled: true, start: '08:00', end: '17:00' },
      friday: { enabled: true, start: '08:00', end: '17:00' },
      saturday: { enabled: false, start: '', end: '' },
      sunday: { enabled: false, start: '', end: '' }
    },
    created_at: '2024-01-20T14:15:00Z',
    updated_at: '2024-01-20T14:15:00Z'
  },
  {
    id: 3,
    name: 'Radiology',
    code: 'RAD',
    description: 'Medical imaging and diagnostic services',
    head_doctor_id: 'DOC003',
    is_active: true,
    operating_hours: {
      monday: { enabled: true, start: '07:00', end: '19:00' },
      tuesday: { enabled: true, start: '07:00', end: '19:00' },
      wednesday: { enabled: true, start: '07:00', end: '19:00' },
      thursday: { enabled: true, start: '07:00', end: '19:00' },
      friday: { enabled: true, start: '07:00', end: '19:00' },
      saturday: { enabled: true, start: '08:00', end: '12:00' },
      sunday: { enabled: false, start: '', end: '' }
    },
    created_at: '2024-01-25T09:45:00Z',
    updated_at: '2024-01-25T09:45:00Z'
  },
  {
    id: 4,
    name: 'Pediatrics',
    code: 'PED',
    description: 'Medical care for infants, children, and adolescents',
    head_doctor_id: null,
    is_active: false,
    operating_hours: null,
    created_at: '2024-02-01T11:20:00Z',
    updated_at: '2024-02-01T11:20:00Z'
  }
])

// Days of week for operating hours
const daysOfWeek = [
  { key: 'monday', label: 'Mon' },
  { key: 'tuesday', label: 'Tue' },
  { key: 'wednesday', label: 'Wed' },
  { key: 'thursday', label: 'Thu' },
  { key: 'friday', label: 'Fri' },
  { key: 'saturday', label: 'Sat' },
  { key: 'sunday', label: 'Sun' }
]

// Reactive state
const showModal = ref(false)
const isEditing = ref(false)
const loading = ref(false)
const searchQuery = ref('')
const statusFilter = ref('')

// Form data
const form = reactive({
  id: null,
  name: '',
  code: '',
  description: '',
  doctor: '',
  is_active: ''
})

// Operating hours form data
const operatingHours = reactive({
  monday: { enabled: false, start: '08:00', end: '17:00' },
  tuesday: { enabled: false, start: '08:00', end: '17:00' },
  wednesday: { enabled: false, start: '08:00', end: '17:00' },
  thursday: { enabled: false, start: '08:00', end: '17:00' },
  friday: { enabled: false, start: '08:00', end: '17:00' },
  saturday: { enabled: false, start: '08:00', end: '17:00' },
  sunday: { enabled: false, start: '08:00', end: '17:00' }
})

// Form errors
const errors = reactive({
  name: '',
  code: '',
  head_doctor_id: '',
  is_active: ''
})

// Computed properties
const activeDepartments = computed(() => 
  departments.value.filter(dept => dept.is_active).length
)

const inactiveDepartments = computed(() => 
  departments.value.filter(dept => !dept.is_active).length
)

const filteredDepartments = computed(() => {
  let filtered = departments.value

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(dept =>
      dept.name.toLowerCase().includes(query) ||
      dept.code.toLowerCase().includes(query) ||
      (dept.head_doctor_id && dept.head_doctor_id.toLowerCase().includes(query))
    )
  }

  if (statusFilter.value !== '') {
    const isActive = statusFilter.value === '1'
    filtered = filtered.filter(dept => dept.is_active === isActive)
  }

  return filtered
})

// Methods
const openAddModal = () => {
  isEditing.value = false
  showModal.value = true
}

const openEditModal = (department) => {
  isEditing.value = true
  form.id = department.id
  form.name = department.name
  form.code = department.code
  form.description = department.description || ''
  form.head_doctor_id = department.head_doctor_id || ''
  form.is_active = department.is_active

  // Load operating hours
  if (department.operating_hours) {
    Object.keys(operatingHours).forEach(day => {
      if (department.operating_hours[day]) {
        operatingHours[day] = { ...department.operating_hours[day] }
      } else {
        operatingHours[day] = { enabled: false, start: '08:00', end: '17:00' }
      }
    })
  } else {
    resetOperatingHours()
  }

  showModal.value = true
}

const resetForm = () => {
  Object.keys(form).forEach(key => {
    form[key] = key === 'id' ? null : ''
  })
  Object.keys(errors).forEach(key => {
    errors[key] = ''
  })
  resetOperatingHours()
}

const resetOperatingHours = () => {
  Object.keys(operatingHours).forEach(day => {
    operatingHours[day] = { enabled: false, start: '08:00', end: '17:00' }
  })
}

const validateForm = () => {
  let isValid = true
  
  // Reset errors
  Object.keys(errors).forEach(key => {
    errors[key] = ''
  })

  if (!form.name.trim()) {
    errors.name = 'Department name is required'
    isValid = false
  } else if (form.name.length > 100) {
    errors.name = 'Department name must not exceed 100 characters'
    isValid = false
  }

  if (!form.code.trim()) {
    errors.code = 'Department code is required'
    isValid = false
  } else if (form.code.length > 10) {
    errors.code = 'Department code must not exceed 10 characters'
    isValid = false
  }

  if (form.is_active === '') {
    errors.is_active = 'Status is required'
    isValid = false
  }

  return isValid
}

const getOperatingHoursData = () => {
  const hasAnyEnabledDay = Object.values(operatingHours).some(day => day.enabled)
  if (!hasAnyEnabledDay) return null

  const hoursData = {}
  Object.keys(operatingHours).forEach(day => {
    if (operatingHours[day].enabled) {
      hoursData[day] = {
        enabled: true,
        start: operatingHours[day].start,
        end: operatingHours[day].end
      }
    } else {
      hoursData[day] = { enabled: false, start: '', end: '' }
    }
  })
  return hoursData
}

const saveDepartment = async () => {
  if (!validateForm()) return

  loading.value = true

  try {
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 1000))

    const operatingHoursData = getOperatingHoursData()

    if (isEditing.value) {
      // Update existing department
      const index = departments.value.findIndex(dept => dept.id === form.id)
      if (index !== -1) {
        departments.value[index] = {
          ...departments.value[index],
          name: form.name,
          code: form.code,
          description: form.description || null,
          head_doctor_id: form.head_doctor_id || null,
          is_active: form.is_active,
          operating_hours: operatingHoursData,
          updated_at: new Date().toISOString()
        }
      }
    } else {
      // Add new department
      const newDepartment = {
        id: Math.max(...departments.value.map(d => d.id)) + 1,
        name: form.name,
        code: form.code,
        description: form.description || null,
        head_doctor_id: form.head_doctor_id || null,
        is_active: form.is_active,
        operating_hours: operatingHoursData,
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString()
      }
      departments.value.push(newDepartment)
    }

    showModal.value = false
    resetForm()
  } catch (error) {
    console.error('Error saving department:', error)
  } finally {
    loading.value = false
  }
}

const deleteDepartment = (id) => {
  if (confirm('Are you sure you want to delete this department?')) {
    departments.value = departments.value.filter(dept => dept.id !== id)
  }
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const formatOperatingHours = (hours) => {
  if (!hours) return 'Not Set'
  
  const enabledDays = Object.entries(hours)
    .filter(([_, dayData]) => dayData.enabled)
    .map(([day, dayData]) => `${day.charAt(0).toUpperCase()}${day.slice(1)}: ${dayData.start}-${dayData.end}`)
  
  return enabledDays.length > 0 ? enabledDays.join(', ') : 'No active hours'
}
</script>