import Swal from 'sweetalert2'
import { useToast } from 'vue-toastification'

export function useNotify() {
    const toast = useToast()

    // Toast: quick, non-blocking message
    const notifyToast = (message, type = 'success', options = {}) => {
        toast[type](message, {
            // Default options
            position: 'top-right',
            timeout: 3000,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: true,
            showCloseButtonOnHover: false,
            ...options, // override or add custom options
        })
    }


  // Alert: blocking modal
  const notifyAlert = async ({
    title = 'Success',
    text = '',
    icon = 'success',
    confirmButtonText = 'OK',
    ...rest
  } = {}) => {
    return await Swal.fire({
      title,
      text,
      icon,
      confirmButtonText,
      ...rest
    })
  }

  // Confirm dialog (returns a promise)
  const notifyConfirm = async ({
    title = 'Are you sure?',
    text = 'You won’t be able to revert this!',
    confirmButtonText = 'Yes',
    cancelButtonText = 'Cancel',
    icon = 'warning',
    showCancelButton = true,
    ...rest
  } = {}) => {
    return await Swal.fire({
      title,
      text,
      icon,
      showCancelButton,
      confirmButtonText,
      cancelButtonText,
      ...rest
    })
  }

  return {
    notifyToast,
    notifyAlert,
    notifyConfirm,
  }
}
