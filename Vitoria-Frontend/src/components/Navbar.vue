<template>
  <nav class="navbar">
    <div class="navbar-container">
      <!-- Logo alineado a la izquierda -->
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

      <!-- Mobile Menu Button (Always visible on mobile) -->
      <button class="mobile-menu" @click="toggleMobileMenu">
        <menu-icon />
      </button>

      <!-- Navigation Links and Auth Buttons (Conditional Rendering) -->
      <div class="nav-actions" :class="{ 'mobile-menu-open': mobileMenuOpen }">
        <a href="#" class="nav-link" @click.prevent="emit('go-home')">Actividades</a>

        <a
          v-if="authStore.isLoggedIn"
          @click="emit('show-my-activities')"
          class="nav-link"
          >Mis Actividades</a
        >

        <!-- Botones de autenticación -->
        <button
          v-if="!authStore.isLoggedIn"
          class="login-button"
          @click="$emit('show-login-tmc')"
        >
          <user-circle-2-icon />
          Conectar
        </button>
        <button v-else class="login-button" @click="emit('logout')">
          <user-circle-2-icon/>
          Desconectar
        </button>
        <button v-if=" authStore.getRol == 'admin'" class="login-admin" @click="emit('goAdmin')">
          Administración
        </button>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'; // Import onMounted and onUnmounted
import { MenuIcon, UserCircle2Icon } from 'lucide-vue-next';
import { defineEmits } from 'vue';
import { useAuthStore } from '../stores/authStore';


const mobileMenuOpen = ref(false)
const emit = defineEmits(['show-login', 'go-home', 'logout', 'show-my-activities', 'show-login-tmc', 'goAdmin']);


const authStore = useAuthStore();
const rol = ref('');


const toggleMobileMenu = () => {
  mobileMenuOpen.value = !mobileMenuOpen.value;
};

// Responsive check
const isMobile = ref(false);

const checkIsMobile = () => {
  isMobile.value = window.innerWidth < 1024; // Consistent with your media query
};

onMounted(async () => {
  checkIsMobile();
  window.addEventListener('resize', checkIsMobile); 

  await authStore.verifyRol();

  console.log("El valor del rol es "+authStore.getRol);


  
});

onUnmounted(() => {
  window.removeEventListener('resize', checkIsMobile);
});

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
  height: 32px; /*  Fixed height.  Using % for height can be problematic. */
  width: 32px;  /*  Fixed width */
}

.logo-text {
  font-size: 1.125rem;
  white-space: nowrap;
}

.nav-actions {
  display: flex;
  align-items: center;
  gap: 2rem;
  /* Default styles for desktop (hidden on mobile) */
  position: static; /* Default positioning */
  background-color: transparent;
  width: auto;
  box-shadow: none;
}

.nav-link {
  font-size: 0.9375rem;
  color: #374151;
  text-decoration: none;
  white-space: nowrap;
  transition: color 0.2s ease;
}

.nav-link:hover {
  cursor: pointer;
  color: #006758;
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
  white-space: nowrap; /* Prevent text wrapping */
}

.login-button:hover {
  background: #006758;
  color: white;
}

.login-admin {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 8px 16px;
  border: 1px solid #006758;
  border-radius: 4px;
  background: white;
  color: red;
  font-size: 0.9375rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease;
}

.login-admin:hover {
  background: red;
  color: white;
}


.mobile-menu {
  display: none; /* Hidden by default, shown on mobile */
  background: none;
  border: none;
  color: #006758;
  cursor: pointer;
  padding: 8px;
}

/* Mobile Styles */
@media (max-width: 1024px) {
   .navbar-container {
    flex-direction: row; /* Keep as row */
    align-items: center; /* Vertically center items */

  }

  .nav-actions {
    display: none; /* Hide by default on mobile */
    flex-direction: column; /* Stack items vertically */
    position: absolute; /* Position absolutely */
    top: 72px;        /* Below the navbar */
    right: 0;        /* Align to the right */
    background-color: white; /* Background color */
    width: auto;       /* Adjust width as needed */
    box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* Optional shadow */
    z-index: 1001;   /* Ensure it's above other content */
    padding: 1rem;   /* Add some padding */

  }
    /* Show nav-actions when mobile menu is open */
  .mobile-menu-open {
    display: flex;
  }

  .mobile-menu {
    display: block; /* Show the mobile menu button */
  }
   .nav-link, .login-button{
        width: 100%;
        justify-content: flex-start;
        padding: 1rem 0;
    }
}

@media (max-width: 768px) {
  .logo-text {
    display: none; /*  Hide on very small screens */
  }
}
</style>