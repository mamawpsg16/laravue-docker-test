<template>
  <div class="page-container">
    <AppointmentStats
      :total="appointments.length"
      :confirmed="confirmedCount"
      :pending="pendingCount"
      :cancelled="cancelledCount"
      @openModal="openCreateModal"
    />

    
    <div v-if="appointments.length" class="bg-white rounded-lg shadow-sm">
      <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        
        <AppointmentFilters v-model:search="searchQuery" v-model:status="statusFilter" />

        <div class="divide-y divide-gray-200">
          <AppointmentItem
            v-for="appointment in appointments"
            :key="appointment.id"
            :appointment="appointment"
            @edit="editAppointment"
            @delete="deleteAppointment"
          />
        </div>
      </div>
    </div>
    <div v-else class="text-center py-12 text-gray-500">No appointments found</div>

    <AppointmentModal
      v-model="showModal"
      v-model:form="appointmentForm"
      @close="closeModal"
      @save="saveAppointment"
    />
  </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import AppointmentStats from './components/Stats.vue'
import AppointmentFilters from './components/Filters.vue'
import AppointmentItem from './components/Item.vue'
import AppointmentModal from './components/Form.vue'

// Sample appointment list
const appointments = ref([
  {
    id: 1,
    title: 'Dental Checkup',
    client: 'John Doe',
    status: 'confirmed',
    date: '2025-06-15',
    time: '10:00',
    notes: 'Patient requested early morning slot'
  },
  {
    id: 2,
    title: 'Business Meeting',
    client: 'Jane Smith',
    status: 'pending',
    date: '2025-06-18',
    time: '14:30',
    notes: 'Bring contract documents'
  },
  {
    id: 3,
    title: 'Therapy Session',
    client: 'Albert Poon',
    status: 'cancelled',
    date: '2025-06-20',
    time: '16:00',
    notes: 'Cancelled due to client unavailability'
  }
])


const searchQuery = ref('')
const statusFilter = ref('')
const showModal = ref(false)
const appointmentForm = reactive({
  id: null,
  name: '',
  status: 'pending',
  date: null
})

// const filteredAppointments = computed(() => {
//   return appointments.value.filter(a => {
//     const matchesSearch = a.name.toLowerCase().includes(searchQuery.value.toLowerCase())
//     const matchesStatus = statusFilter.value ? a.status === statusFilter.value : true
//     return matchesSearch && matchesStatus
//   })
// })

const confirmedCount = computed(() =>
  appointments.value.filter(a => a.status === 'confirmed').length
)
const pendingCount = computed(() =>
  appointments.value.filter(a => a.status === 'pending').length
)
const cancelledCount = computed(() =>
  appointments.value.filter(a => a.status === 'cancelled').length
)

function openCreateModal() {
  Object.assign(appointmentForm, {
    id: null,
    name: '',
    status: 'pending',
    datetime: ''
  })
  showModal.value = true
}

function editAppointment(appointment) {
  Object.assign(appointmentForm, { ...appointment })
  showModal.value = true
}

function deleteAppointment(appointmentId) {
  appointments.value = appointments.value.filter(a => a.id !== appointmentId)
}

function saveAppointment() {
  if (appointmentForm.id) {
    // Update
    const index = appointments.value.findIndex(a => a.id === appointmentForm.id)
    if (index !== -1) {
      appointments.value[index] = { ...appointmentForm }
    }
  } else {
    // Create
    const newId = Math.max(...appointments.value.map(a => a.id), 0) + 1
    appointments.value.push({ ...appointmentForm, id: newId })
  }
  closeModal()
}

function closeModal() {
  showModal.value = false
}
</script>
  