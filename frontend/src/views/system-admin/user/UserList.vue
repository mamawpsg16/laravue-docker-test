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
          <!-- <button @click="openAddModal" class="btn-primary">
            <svg class="action-icon-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add User
          </button> -->
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
    <DataTable :data="formattedData" :columns="columns" :tableHeaderTitle="tableTitle"  @update:selectedRows="handleSelectedRowsChange" v-model="internalRowSelectionState">
      <template #table-actions>
        <button
          class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
          @click="openAddModal"
        >
          <i class="bi bi-plus-lg mr-2"></i> Add
        </button>
        <button
          class="flex items-center px-4 py-2 border border-gray-300 rounded-md bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
          @click="refreshTable"
        >
          <i class="bi bi-arrow-clockwise"></i>
        </button>
        <button
          class="flex items-center px-4 py-2 border border-gray-300 rounded-md bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
          @click="openFilters"
        >
          <i class="bi bi-funnel mr-2"></i> Filters
        </button>
        <button
          class="flex items-center px-4 py-2 border border-gray-300 rounded-md bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
          @click="exportData"
        >
          <i class="bi bi-upload mr-2"></i> Export
        </button>
      </template>
    </DataTable>

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
  <UserForm v-if="showModal" v-model="showModal" :is-editing="isEditing" :initial-user="userDetails"/>
  

</template>

<script setup>
import { ref, computed, watch, h } from 'vue'
import DataTable from '@/components/Table/TanStackTable.vue' 
import StatusButtonRenderer from './StatusButtonRenderer.vue'
import { formatDate } from '@/utils/dateHelpers';
import UserForm from './components/UserForm.vue';
import Swal from 'sweetalert2'

const tableTitle = ref('User List: (Jun 24, 2025 - Jun 24, 2025)');
const tableHeaderActions = ref([
  { label: 'Add', onClick: () => alert('Add clicked!'), icon: 'bi bi-plus-lg', primary: true },
  { label: '', onClick: () => alert('Refresh clicked!'), icon: 'bi bi-arrow-clockwise' }, // No label for refresh
  { label: 'Filters', onClick: () => alert('Filters clicked!'), icon: 'bi bi-funnel' },
  { label: 'Export', onClick: () => alert('Export clicked!'), icon: 'bi bi-upload' },
]);
const columns = ref([
  { label: 'Actions', field: 'status', 
    cell: (info) => h(StatusButtonRenderer, {
      rowData: info.row.original,
      onUpdateStatus: (updatedRow) => {
        const index = data.value.findIndex(r => r.id === updatedRow.id)
        if (index !== -1) {
          data.value[index] = {...updatedRow}
        }
      },
      onEditRow: (row) => {
        openEditModal(row);
      },
 
      onDeleteRow: (row) => {
        console.log(row,'onEditRow');
        alert('Delete clicked for ' + row.first_name)
      },
    }),
    sortable:false
  },
  { label: 'First Name', field: 'first_name'},
  { label: 'Middle Name', field: 'middle_name'},
  { label: 'Last Name', field: 'last_name'},
  { label: 'Email Address', field: 'email'},
  { label: 'Phone', field: 'phone'},
  { label: 'Birth Date', field: 'birth_date'},
  { label: 'Gender', field: 'gender'},
  { label: 'Address', field: 'address'},
  { label: 'Emergency Contact #', field: 'address'},
  { label: 'Emergency Contact #', field: 'address'},
  { label: 'Role', field: 'role'},
  
]);
// Role options

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

const userDetails = ref(null);

const refreshTable = () => {
  alert('Refresh table clicked!');
  // Implement your refresh logic here
};

const openFilters = () => {
  alert('Open filters clicked!');
  // Implement your filter logic here
};

const exportData = () => {
  alert('Export data clicked!');
  // Implement your export logic here
};

// const data = ref([])

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
const showModal = ref(false)
const isEditing = ref(false)
const isLoadingUserDetails = ref(false) 

function formatData(data){
    return {
      ...data,
      birth_date: formatDate(data.birth_date)
    }
}

const formattedData = computed(() => data.value.map(data => formatData(data)));

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

// Methods
const openAddModal = () => {
  console.log('showModal 1', showModal.value);
  isEditing.value = false
  showModal.value = true
  console.log('showModal 2', showModal.value);
}

const openEditModal = async (user) => {
  console.log('Opening edit modal for user:', user.id);
  
  // Set editing state and show modal immediately
  isEditing.value = true

  // Start loading state
  isLoadingUserDetails.value = true
  userDetails.value = null // Clear previous data
  
  try {
    // Simulate API call delay
    await new Promise(resolve => setTimeout(resolve, 5000)) // 1.5 second delay
    showModal.value = true
    // In real implementation, you would make an API call here:
    // const response = await userAPI.getUserDetails(user.id)
    // userDetails.value = response.data
    
    // For now, simulate fetching the user details
    const fetchedUserDetails = {
      ...user,
      // You might get additional details from the API that aren't in the table
      full_profile_data: true,
      last_updated: new Date().toISOString()
    }
    
    userDetails.value = fetchedUserDetails
    console.log('User details loaded:', fetchedUserDetails);
    
  } catch (error) {
    console.error('Failed to load user details:', error);
    
    // Show error message and close modal
    Swal.fire({
      title: 'Error!',
      text: 'Failed to load user details. Please try again.',
      icon: 'error',
      confirmButtonText: 'OK'
    })
    
    // Close modal on error
    showModal.value = false
    
  } finally {
    // Always stop loading state
    isLoadingUserDetails.value = false
  }
}

watch(isLoadingUserDetails, (newValue, oldValue) => {
  if (newValue) {
    Swal.fire({
      title: '<i class="fa fa-cog fa-spin"></i>&nbsp;Loading...',
      text: "Fetching details, kindly wait.",
      showConfirmButton: false,
      allowOutsideClick: false,
      allowEscapeKey: false,
    });
  } else {
    Swal.close();
  }
});

</script>
