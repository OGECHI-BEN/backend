<template>
  <div class="flex items-center space-x-2">
    <img
      :src="authStore.userAvatar || '/default-avatar.png'"
      alt="User avatar"
      class="w-8 h-8 rounded-full object-cover"
      @error="handleAvatarError"
    />
    <span class="text-indigo-600 font-semibold">{{ authStore.userPoints }} XP</span>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

defineOptions({
  name: 'AppHeader',
})

const router = useRouter()
const authStore = useAuthStore()
const isMobileMenuOpen = ref(false)

const handleAvatarError = (e) => {
  // If the avatar fails to load, set a default avatar
  e.target.src = '/default-avatar.png'
}

const handleLogout = async () => {
  await authStore.logout()
  router.push('/login')
}

// Initialize auth state when component is mounted
authStore.initializeAuth()
</script>
