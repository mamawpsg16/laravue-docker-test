<template>
  <BaseModal v-model="modalVisible" max-width="xl" @closed="$emit('close')">
    <div class="p-6 bg-white rounded-lg shadow space-y-6">
      <h3 class="text-xl font-semibold text-gray-800">
        {{ isEditing ? 'Edit' : 'Create' }} Appointment
      </h3>

      <form @submit.prevent="$emit('save')" class="space-y-4">
        <BaseInput
          v-model="form.title"
          label="Title"
          minlength="8"
          maxlength="20"
          type="text"
        />

        <BaseInput
          v-model="form.client"
          label="Client"
          minlength="8"
          maxlength="20"
          type="text"
        />

        <FieldWrapper label="Date">
          <VueDatePicker 
            v-model="form.date"
            placeholder="Select your date"
            format="MMMM d, yyyy"
            :auto-apply="true"
            :time-picker-inline="true"
            :is-24="false"
          />
        </FieldWrapper>
      

        <div class="flex justify-end gap-3 pt-4">
          <button
            type="button"
            @click="$emit('close')"
            class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100 transition"
          >
            Cancel
          </button>
          <button
            type="submit"
            class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition"
          >
            {{ isEditing ? 'Update' : 'Create' }}
          </button>
        </div>
      </form>
    </div>
  </BaseModal>
</template>

<script setup>
import BaseModal from '@/components/BaseModal.vue'
import BaseInput from '@/components/Form/BaseInput.vue'
import VueDatePicker from '@vuepic/vue-datepicker'
import FieldWrapper from '@/components/Form/FieldWrapper.vue'


const form = defineModel('form')
const modalVisible = defineModel()

defineProps(['isEditing'])
defineEmits(['close', 'save'])
</script>

<style scoped>
::v-deep(.dp__menu) {
  z-index: 9999 !important;
}
</style>