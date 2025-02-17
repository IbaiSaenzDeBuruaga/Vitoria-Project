<!-- Navbar.vue -->
<template>
  <nav class="navbar">
    <div class="navbar-container">
      <!-- Mobile Menu Button -->
      <button class="mobile-menu" v-if="isMobile" @click="toggleMobileMenu">
        <menu-icon />
      </button>

      <!-- Logo -->
      <div class="logo-section" @click="emit('go-home')">
        <img
          src="../assets/images/logo.png"
          alt="Vitoria Gasteiz"
          class="logo"
        />
        <div class="logo-text">
          <span class="bold">sede</span>
          <span>electrónica</span>
        </div>
      </div>

      <!-- Search Bar -->
      <div class="search-section">
        <div class="search-bar">
          <search-icon size="20" class="search-icon" />
          <input
            type="text"
            placeholder="Buscar actividades..."
            class="search-input"
          />
        </div>
      </div>

      <!-- Navigation Links -->
      <div class="nav-links">
        <a href="#" class="nav-link" @click.prevent="emit('go-home')">Actividades</a>
        <a href="#" class="nav-link">Centros</a>
        <a href="#" class="nav-link">Ayuda</a>
        <a
          v-if="authStore.isLoggedIn"
          @click="emit('show-my-activities')"
          class="nav-link"
          >Mis Actividades</a
        >
      </div>

      <!-- Auth Button -->
      <div class="auth-buttons">
        <button
          v-if="!authStore.isLoggedIn"
          class="login-button"
          @click="$emit('show-login')"
        >
          <user-circle-2-icon />
          Conectar
        </button>
        <button v-else class="login-button" @click="emit('logout')">
          Desconectar
        </button>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, computed } from 'vue'
import { MenuIcon, SearchIcon, UserCircle2Icon } from 'lucide-vue-next'
import { defineEmits } from 'vue';
import { useAuthStore } from '../stores/authStore';

const isMobile = computed(() => window.innerWidth < 768)
const mobileMenuOpen = ref(false)
const emit = defineEmits(['show-login', 'go-home', 'logout', 'show-my-activities']);

const authStore = useAuthStore()

const toggleMobileMenu = () => {
  mobileMenuOpen.value = !mobileMenuOpen.value
}
</script>

<style scoped>
.navbar {
  height: 72px;
  background: white;
  border-bottom: 1px solid #e5e7eb;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  z-index: 1000;
}

.navbar-container {
  max-width: 1400px;
  height: 100%;
  margin: 0 auto;
  padding: 0 24px;
  display: flex;
  justify-content:end;
  align-items: center;
  gap: 1rem;
}

.mobile-menu {
  display: none;
  background: none;
  border: none;
  color: #006758;
  cursor: pointer;
  padding: 8px;
}

.logo-section {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  min-width: 160px;
  cursor: pointer; /* Add pointer cursor for clickable logo */
}

.logo {
  height: 32px;
  width: 32px;
}

.logo-text {
  font-size: 1.125rem;
  white-space: nowrap;
}

.logo-text .bold {
  font-weight: 700;
}

.search-section {
  flex: 1;
  max-width: 600px;
  margin: 0 1rem;
}

.search-bar {
  display: flex;
  align-items: center;
  background: #f3f4f6;
  border: 1px solid #e5e7eb;
  border-radius: 9999px;
  padding: 0.5rem 1rem;
  transition: all 0.2s ease;
}

.search-bar:focus-within {
  background: white;
  border-color: #006758;
  box-shadow: 0 0 0 2px rgba(0, 103, 88, 0.1);
}

.search-icon {
  color: #6b7280;
  margin-right: 0.5rem;
}

.search-input {
  border: none;
  background: transparent;
  width: 100%;
  font-size: 0.875rem;
  color: #111827;
}

.search-input:focus {
  outline: none;
}

.search-input::placeholder {
  color: #6b7280;
}

.nav-links {
  display: flex;
  gap: 1.5rem;
  margin-right: 1rem;
}

.nav-link {
  font-size: 0.875rem;
  color: #374151;
  text-decoration: none;
  white-space: nowrap;
  transition: color 0.2s ease;
}

.nav-link:hover {
  color: #006758;
}

.auth-buttons {
  margin-left: auto;
}

.login-button {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 8px 12px;
  border: 1px solid #006758;
  border-radius: 4px;
  background: white;
  color: #006758;
  font-size: 0.875rem;
  cursor: pointer;
  transition: all 0.2s ease;
}

.login-button:hover {
  background: #f3f4f6;
}

@media (max-width: 1024px) {
  .nav-links {
    display: none;
  }
}

@media (max-width: 768px) {
  .mobile-menu {
    display: block;
  }

  .logo-text {
    display: none;
  }

  .search-section {
    display: none;
  }
}
</style>