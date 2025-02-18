<template>
  <nav class="navbar">
    <div class="navbar-container">
      <!-- Mobile Menu Button -->
      <button class="mobile-menu" v-if="isMobile" @click="toggleMobileMenu">
        <menu-icon />
      </button>

      <!-- Logo -->
      <div class="logo-section" @click="goHome">
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

      <!-- Auth Button -->
      <div class="auth-buttons">
        <button v-if="authStore.isLoggedIn" class="login-button" @click="goHome">
          <user-circle-2-icon />
          Volver
        </button>
        <button v-if="!authStore.isLoggedIn" class="login-button" >
          <user-circle-2-icon />
          Conectar
        </button>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, computed } from 'vue';
import { MenuIcon, UserCircle2Icon } from 'lucide-vue-next'; // Importa solo los íconos que necesitas
import { useAuthStore } from '../stores/authStore';  // Asegúrate de que la ruta es correcta
import { useRouter } from 'vue-router';

const router = useRouter();
const isMobile = computed(() => window.innerWidth < 768);
const mobileMenuOpen = ref(false);

const authStore = useAuthStore(); // Usa el store de autenticación

const toggleMobileMenu = () => {
  mobileMenuOpen.value = !mobileMenuOpen.value;
};

const goHome = () => {
    router.push("/");
};



</script>

<style scoped>
/* ... (Estilos del NavbarAdmin - los que ya tenías, sin cambios importantes) ... */
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