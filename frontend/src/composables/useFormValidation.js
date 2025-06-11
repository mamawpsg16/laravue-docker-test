import { useVuelidate } from '@vuelidate/core'
import { unref } from 'vue'

export function useFormValidation(form, rules, options = { $autoDirty: true, $lazy: false }) {
  const $v = useVuelidate(rules, form, options)

  const humanizeField = (field) =>
    field
      .replace(/_/g, ' ')
      .replace(/\b\w/g, (char) => char.toUpperCase())

  const getFrontendError = (field) => {
    const fieldState = $v.value?.[field]
    if (!fieldState?.$dirty || !fieldState?.$invalid) return null

    const rulesValue = unref(rules)
    const fieldRules = rulesValue[field] || {}

    for (const rule in fieldRules) {
      const validator = fieldState[rule]
      if (validator?.$invalid) {
        // 👇 Ensure we unwrap the message properly
        const rawMessage = unref(validator.$message)
        if (typeof rawMessage === 'string') {
          return rawMessage.replace(/value/i, humanizeField(field))
        }
      }
    }

    return `${humanizeField(field)} is invalid`
  }

  const getBackendError = (field, errors) => {
    const error = unref(errors)
    const fieldErrors = error?.[field];
    
    return fieldErrors ?? [];
  }

  return { $v, getFrontendError, getBackendError }
}
