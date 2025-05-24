const validateForm = () => {
  let isValid = true
  errors.username = ''
  errors.email = ''
  errors.password = ''
  errors.password_confirmation = ''
  errors.avatar = ''

  // Username validation
  if (form.username.length < 3) {
    errors.username = 'Username must be at least 3 characters long'
    isValid = false
  }

  // Email validation
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  if (!emailRegex.test(form.email)) {
    errors.email = 'Please enter a valid email address'
    isValid = false
  }

  // Password validation
  if (form.password.length < 8) {
    errors.password = 'Password must be at least 8 characters long'
    isValid = false
  }

  // Password confirmation
  if (form.password !== form.password_confirmation) {
    errors.password_confirmation = 'Passwords do not match'
    isValid = false
  }

  // Avatar validation
  if (!form.avatar) {
    errors.avatar = 'Please select an avatar'
    isValid = false
  } else if (form.avatar instanceof File) {
    // Check file size (2MB = 2 * 1024 * 1024 bytes)
    if (form.avatar.size > 2 * 1024 * 1024) {
      errors.avatar = 'Avatar image must be less than 2MB'
      isValid = false
    }
    // Check file type
    const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/avif']
    if (!allowedTypes.includes(form.avatar.type)) {
      errors.avatar = 'Avatar must be a valid image file (JPEG, PNG, JPG, GIF, or AVIF)'
      isValid = false
    }
  }

  return isValid
}
