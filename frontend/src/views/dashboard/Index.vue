<template>
  <!-- <div class="p-4">
    <VueGoodTableNext v-slot="{ props }" :data="users" :columns="columns">
      <template v-if="props.column.field === 'status'">
        <FieldWrapper>
          <span class="text-sm mr-3 text-gray-600">
            {{ props.row.status ? 'Active' : 'Inactive' }}
          </span>
          <div
            @click="toggleStatus(props.index)"
            class="relative inline-flex h-6 w-11 items-center rounded-full cursor-pointer transition-colors"
            :class="props.row.status ? 'bg-blue-600' : 'bg-gray-300'"
          >
            <span
              class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-300 ease-in-out"
              :class="props.row.status ? 'translate-x-6' : 'translate-x-1'"
            />
          </div>
        </FieldWrapper>
        
      </template>
      <template v-else>
        {{ props.row[props.column.field] }}
      </template>
    </VueGoodTableNext> -->
    <GalleryCarousel :images="images" :maxWidth="'max-w-3xl'" />

  <!-- </div> -->
</template>

<script setup>
import { ref } from 'vue'
import FieldWrapper from '@/components/Form/FieldWrapper.vue'
import VueGoodTableNext from '@/components/Table/VueGoodTableNext.vue'
import GalleryCarousel from '@/components/GalleryCarousel.vue';

const images = Array.from({ length: 10 }, (_, index) => ({
  id: index + 1,
  url: `https://picsum.photos/seed/${Math.random()}/800/600`,
}))
const users = ref([
  { id: 1, name: 'Alice', email: 'alice@example.com', status: true },
  { id: 2, name: 'Bob', email: 'bob@example.com', status: false },
  { id: 3, name: 'Charlie', email: 'charlie@example.com', status: true },
  { id: 3, name: 'Charlie', email: 'charlie@example.com', status: true },
  { id: 3, name: 'Charlie', email: 'charlie@example.com', status: true },
  { id: 3, name: 'Charlie', email: 'charlie@example.com', status: true },

])

const columns = [
  { label: 'ID', field: 'id', },
  { label: 'Name', field: 'name' },
  { label: 'Email', field: 'email' },
  { label: 'Status', field: 'status' }
]


function toggleStatus(index) {
  users.value[index].status = !users.value[index].status;
}

</script>



