<template>
  <div class="content-card">
    <div class="card-header">
      <div class="header-content">
        <div>
          <h1 class="page-title flex items-center gap-2">
            <i class="bi bi-calendar-day text-2xl"></i>
            Appointment Management
          </h1>
          <p class="text-gray-600 mt-1">Manage your appointments and schedule</p>
        </div>
        <button @click="openCreateModal" class="btn-primary">
          <i class="bi bi-plus-circle text-2xl"></i>
          Add User
        </button>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid-4">
      <!-- Total Appointments -->
      <div class="stat-card">
        <div class="stat-card-content">
          <div class="stat-icon-blue">
            <i class="bi bi-calendar-day text-2xl"></i>
          </div>
          <div class="stat-details">
            <p class="section-label">Total Appointments</p>
            <p class="section-value">{{ total }}</p>
          </div>
        </div>
      </div>

      <!-- Confirmed -->
      <div class="stat-card">
        <div class="stat-card-content">
          <div class="stat-icon-green">
            <i class="bi bi-calendar-check text-2xl"></i>
          </div>
          <div class="stat-details">
            <p class="section-label">Confirmed</p>
            <p class="section-value">{{ confirmed }}</p>
          </div>
        </div>
      </div>

      <!-- Pending -->
      <div class="stat-card">
        <div class="stat-card-content">
          <div class="stat-icon-orange">
            <!-- Replace old SVG with this -->
            <i class="bi bi-clock-history text-2xl"></i>
          </div>
          <div class="stat-details">
            <p class="section-label">Pending</p>
            <p class="section-value">{{ pending }}</p>
          </div>
        </div>
      </div>

      <!-- Cancelled -->
      <div class="stat-card">
        <div class="stat-card-content">
          <div class="stat-icon-red flex items-center justify-center">
          <i class="bi bi-calendar-x text-2xl"></i>
          </div>
          <div class="stat-details">
            <p class="section-label">Cancelled</p>
            <p class="section-value">{{ cancelled }}</p>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>


<script setup>
defineProps(['total', 'confirmed', 'pending', 'cancelled'])

const emit = defineEmits(['openModal'])

function openCreateModal() {
  emit('openModal')
}
</script>
