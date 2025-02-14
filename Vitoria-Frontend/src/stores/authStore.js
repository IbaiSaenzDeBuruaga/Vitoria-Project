// stores/authStore.js
import { defineStore } from 'pinia';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: localStorage.getItem('authToken') || null,
  }),
  getters: {
    isLoggedIn: (state) => !!state.token,
    getToken: (state) => state.token
  },
  actions: {
    setToken(token) {
      this.token = token;
      localStorage.setItem('authToken', token);
    },
    clearToken() {
      this.token = null;
      localStorage.removeItem('authToken');
    },
  },
});