// composables/useRegisterForm.js
import { reactive, ref } from 'vue'
import { required, email } from '@vuelidate/validators'
import { useVuelidate } from '@vuelidate/core'

export function useRegisterForm() {
  const form = reactive({
    first_name: '',
    middle_name: '',
    last_name: '',
    email: '',
    password: '',
    password_confirmation: '',
    birth_date: null,
    gender: null,
    address: ''
  })

  const genderOptions = ref([
    { label: 'Male', value: 'male' },
    { label: 'Female', value: 'female' },
    { label: 'Other', value: 'other' }
  ])

  const rules = {
    first_name: { required },
    last_name: { required },
    email: { required, email },
    password: { required },
    password_confirmation: { required },
    birth_date: { required },
    gender: { required },
    address: { required }
  }

  const $v = useVuelidate(rules, form, { $autoDirty: true })

  const loading = ref(false)
  const errorMessage = ref(null)

  return {
    form,
    genderOptions,
    loading,
    errorMessage,
    $v
  }
}
