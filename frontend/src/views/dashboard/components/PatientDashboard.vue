<template>
  <div class="space-y-4">
    <div class="bg-white shadow rounded-lg p-4">
      <h2 class="text-lg font-bold">Upcoming Appointments</h2>
      <ul>
        <li v-for="appt in appointments" :key="appt.id" class="border-b py-2">
          <p><strong>Date:</strong> {{ appt.date }}</p>
          <p><strong>Doctor:</strong> {{ appt.doctor }}</p>
          <p><strong>Service:</strong> {{ appt.service }}</p>
        </li>
      </ul>
    </div>
    <div class="bg-blue-100 p-4 rounded">
      <p class="font-medium">Ready to book your next appointment?</p>
      <button class="mt-2 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Book Now</button>
    </div>
  </div>
</template>

<script setup>
const appointments = [
  { id: 1, date: '2025-06-18', doctor: 'Dr. Reyes', service: 'General Checkup' },
  { id: 2, date: '2025-06-25', doctor: 'Dr. Santos', service: 'Dental Cleaning' },
];
</script>