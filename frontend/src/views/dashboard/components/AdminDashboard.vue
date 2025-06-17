<template>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="bg-white shadow rounded-lg p-4">
      <h2 class="text-lg font-bold">System Statistics</h2>
      <ul class="mt-2 space-y-1">
        <li>Total Patients: 154</li>
        <li>Total Doctors: 12</li>
        <li>Total Appointments: 342</li>
      </ul>
    </div>
    <div class="bg-purple-100 p-4 rounded">
      <p class="font-medium">Recent Activity</p>
      <ul class="mt-2 text-sm">
        <li>New doctor registered: Dr. Lim</li>
        <li>3 appointments booked today</li>
        <li>Department updated: Pediatrics</li>
      </ul>
    </div>
  </div>
</template>

<script setup>
// Admin dummy data is inline in template
</script>