<template>
  <div class="space-y-4">
    <div class="bg-white shadow rounded-lg p-4">
      <h2 class="text-lg font-bold">Today’s Appointments</h2>
      <ul>
        <li v-for="appt in todaysAppointments" :key="appt.id" class="border-b py-2">
          <p><strong>Time:</strong> {{ appt.time }}</p>
          <p><strong>Patient:</strong> {{ appt.patient }}</p>
          <p><strong>Service:</strong> {{ appt.service }}</p>
        </li>
      </ul>
    </div>
    <div class="bg-green-100 p-4 rounded">
      <p class="font-medium">You have {{ todaysAppointments.length }} appointments today.</p>
    </div>
  </div>
</template>

<script setup>
const todaysAppointments = [
  { id: 1, time: '10:00 AM', patient: 'Juan Dela Cruz', service: 'Consultation' },
  { id: 2, time: '11:30 AM', patient: 'Maria Clara', service: 'Follow-up' },
];
</script>
