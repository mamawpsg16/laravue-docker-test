<template>
  <div class="space-y-4">
    <div class="bg-white shadow rounded-lg p-4">
      <h2 class="text-lg font-bold">Patient Queue</h2>
      <ul>
        <li v-for="patient in queue" :key="patient.id" class="border-b py-2">
          {{ patient.name }} - {{ patient.reason }}
        </li>
      </ul>
    </div>
    <div class="bg-yellow-100 p-4 rounded">
      <p class="font-medium">You have {{ queue.length }} patients waiting.</p>
    </div>
  </div>
</template>

<script setup>
const queue = [
  { id: 1, name: 'Pedro Penduko', reason: 'Blood Test' },
  { id: 2, name: 'Ligaya Santos', reason: 'Consultation' },
];
</script>
