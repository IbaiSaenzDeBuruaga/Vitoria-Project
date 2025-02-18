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
          <span class="bold">sede</span>
          <span>electrónica</span>
        </div>
      </div>

       <!-- Auth Button and Mobile Menu Items -->
      <div class="auth-buttons" :class="{ 'mobile-menu-open': mobileMenuOpen && isMobile }">
        <!-- Auth Buttons -->
        <button v-if="authStore.isLoggedIn" class="login-button" @click="logout">
          <user-circle-2-icon />
          Cerrar sesión
        </button>
        <button v-if="!authStore.isLoggedIn" class="login-button" @click="showLoginOptions">
          <user-circle-2-icon />
          Conectar
        </button>

          <!-- Navigation Links (Displayed within mobile menu) -->
        <div v-if="isMobile && mobileMenuOpen" class="mobile-nav-links">
          <a href="#" class="nav-link" @click.prevent="showMyActivities">Mis Actividades</a>
          <!-- Add more navigation links as needed -->
        </div>
      </div>
    </div>

    <!-- Expanded Mobile Menu (Conditional) -->
    <div v-if="isMobile && mobileMenuOpen" class="mobile-menu-expanded">
       <!-- Example: Placeholder for additional mobile-only content (if needed) -->
    </div>
  </nav>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'; // Import onMounted and onUnmounted
import { MenuIcon, UserCircle2Icon } from 'lucide-vue-next';
import { useAuthStore } from '../stores/authStore';
import { useRouter } from 'vue-router';

const router = useRouter();
const mobileMenuOpen = ref(false);
const authStore = useAuthStore();

// Computed property for mobile detection (more reliable)
const isMobile = ref(false);

const checkIsMobile = () => {
  isMobile.value = window.innerWidth < 768;
};

onMounted(() => {
  checkIsMobile(); // Check on initial load
  window.addEventListener('resize', checkIsMobile);
});

onUnmounted(() => {
  window.removeEventListener('resize', checkIsMobile);
});


const toggleMobileMenu = () => {
  mobileMenuOpen.value = !mobileMenuOpen.value;
};

const goHome = () => {
    router.push("/");
};

const showLoginOptions = () => {
    router.push("/") //or your login page
    //emit('show-login');  // Emit the event to the parent component
};
const showMyActivities = () =>{
    router.push("/")
}
const logout = async () => {
  try {
    // Limpia el token del localStorage (Corrected typo: localStorage)
    localStorage.removeItem('token');
    localStorage.removeItem('rol');
    // Redirige a la página de inicio
    router.push('/');
    window.location.reload(); // Consider removing if causing issues.  router.push("/") should suffice.
  } catch (error) {
    console.error('Error al cerrar sesión:', error);
  }
};

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
  justify-content: space-between; /* Corrected: Use space-between for proper alignment */
  align-items: center;
  gap: 1rem;
}

.mobile-menu {
  display: none; /* Hidden by default, shown in mobile view */
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
  cursor: pointer;
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
  /*  Removed flex: 1 and max-width;  Not needed in this layout */
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
  display: flex; /*  Keep for desktop, will be hidden in mobile */
  gap: 1.5rem;
  margin-right: 1rem;
}
.mobile-nav-links {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  padding: 1rem; /* Add some padding */
  background-color: white; /* Or your desired background color */
  border-top: 1px solid #e5e7eb; /* Add a separator */
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
  margin-left: auto; /* Push to the right */
  display: flex; /* Important for alignment */
  align-items: center; /* Center items vertically */
  gap: 0.5rem; /* Space between buttons */
}
/* Mobile Menu Styles */
.mobile-menu-open {
    display: flex; /* Show buttons when menu is open */
    flex-direction: column;
    position: absolute;
    top: 72px; /* Position below the navbar */
    right: 0;
    background-color: white;
    width: auto; /* Adjust width as needed */
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    gap:0;
     z-index: 1001;
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
   white-space: nowrap; /* Prevent text wrapping */
}

.login-button:hover {
  background: #f3f4f6;
}

/* Mobile Styles */
@media (max-width: 1024px) {
  .nav-links {
    display: none; /* Hide on smaller screens */
  }
}

@media (max-width: 768px) {
  .mobile-menu {
    display: block; /* Show mobile menu button */
  }

  .logo-text {
    display: none; /* Hide text on smaller screens */
  }

  .search-section {
    display: none; /* Hide search bar on smaller screens */
  }
  /* Hide desktop auth buttons by default in mobile */
    .auth-buttons > .login-button {
        display: none;
    }
      /* Show auth buttons ONLY when mobile menu is open */
    .mobile-menu-open > .login-button {
        display: flex;
    }
    .navbar-container {

    justify-content: space-between; /* Ensure logo and menu button are spaced */
  }

}

.mobile-menu-expanded {
  position: absolute;
  top: 72px; /* Below the navbar */
  left: 0;
  right: 0;
  background-color: white;
  box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
  z-index: 999; /* Ensure it's below the navbar but above other content */

}
</style>