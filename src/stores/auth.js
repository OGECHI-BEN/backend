import { defineStore } from 'pinia'
import axios from 'axios'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null,
    userPoints: 0,
    userAvatar: null,
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
  },

  actions: {
    async login(credentials) {
      try {
        const response = await axios.post('/api/auth/login', credentials)
        const { token, user } = response.data

        this.token = token
        this.user = user
        this.userPoints = user.points || 0
        this.userAvatar = user.avatar_url

        // Store token in localStorage
        localStorage.setItem('token', token)

        // Set default authorization header
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`

        return true
      } catch (error) {
        console.error('Login error:', error)
        throw error
      }
    },

    async register(userData) {
      try {
        const response = await axios.post('/api/auth/register', userData)
        const { token, user } = response.data

        this.token = token
        this.user = user
        this.userPoints = user.points || 0
        this.userAvatar = user.avatar_url

        // Store token in localStorage
        localStorage.setItem('token', token)

        // Set default authorization header
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`

        return true
      } catch (error) {
        console.error('Registration error:', error)
        throw error
      }
    },

    async logout() {
      try {
        await axios.post('/api/auth/logout')
      } catch (error) {
        console.error('Logout error:', error)
      } finally {
        this.clearAuth()
      }
    },

    async fetchUser() {
      try {
        const response = await axios.get('/api/auth/user')
        const { user } = response.data

        this.user = user
        this.userPoints = user.points || 0
        this.userAvatar = user.avatar_url

        return user
      } catch (error) {
        console.error('Fetch user error:', error)
        this.clearAuth()
        throw error
      }
    },

    clearAuth() {
      this.user = null
      this.token = null
      this.userPoints = 0
      this.userAvatar = null
      localStorage.removeItem('token')
      delete axios.defaults.headers.common['Authorization']
    },

    // Initialize auth state from localStorage
    initializeAuth() {
      const token = localStorage.getItem('token')
      if (token) {
        this.token = token
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
        this.fetchUser()
      }
    },
  },
})
