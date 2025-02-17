<template>
  <nav class="navbar">
    <div class="navbar-container">
      <!-- Logo -->
      <div class="logo-section" @click="emit('go-home')">
        <img
          src="../assets/images/logo.png"
          alt="Vitoria Gasteiz"
          class="logo"
        />
        <div class="logo-text">
          <span>Sede</span>
          <span> Electrónica</span>
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

      <!-- Mobile Menu Button -->
      <button class="mobile-menu" @click="toggleMobileMenu">
        <menu-icon />
      </button>
    </div>
  </nav>
</template>

<script setup>
import { ref } from 'vue'
import { MenuIcon, UserCircle2Icon } from 'lucide-vue-next'
import { defineEmits } from 'vue';
import { useAuthStore } from '../stores/authStore';

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
  justify-content: space-between;
  align-items: center;
}

.logo-section {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
}

.logo {
  height: 10%;
  width: 10%;
}

.logo-text {
  font-size: 1.125rem;
  white-space: nowrap;
}

.logo-text .bold {
  font-weight: 700;
}

.nav-links {
  display: flex;
  gap: 2rem;
}

.nav-link {
  font-size: 0.9375rem;
  color: #374151;
  text-decoration: none;
  white-space: nowrap;
  transition: color 0.2s ease;
}

.nav-link:hover {
  color: #006758;
}

.auth-buttons {
  display: flex;
  align-items: center;
}

.login-button {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 8px 16px;
  border: 1px solid #006758;
  border-radius: 4px;
  background: white;
  color: #006758;
  font-size: 0.9375rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease;
}

.login-button:hover {
  background: #006758;
  color: white;
}

.mobile-menu {
  display: none;
  background: none;
  border: none;
  color: #006758;
  cursor: pointer;
  padding: 8px;
}

@media (max-width: 1024px) {
  .nav-links {
    display: none;
  }

  .mobile-menu {
    display: block;
  }
}

@media (max-width: 768px) {
  .logo-text {
    display: none;
  }
}
</style>

