<template>
  <div class="activities-portal">
    <Navbar
      @show-login="showLoginOptions"
      @go-home="goToHome"
      @logout="logout"
      @show-my-activities="showMyActivities"
    />

    <MisActividades v-if="showMyActivitiesComponent" />

    <div v-else-if="!showLogin && !showTMC" class="main-container">
      <!-- Sidebar with Filters -->
      <aside class="filters-sidebar" :class="{ 'show-filters': filtersOpen }">
        <div class="filters-header">
          <h2>Filtros</h2>
        </div>

        <div class="filters-section">
          <h3>Centro cívico</h3>
          <div class="filter-options">
            <label class="filter-option">
              <input type="checkbox" />
              <span>Iparralde</span>
              <span class="count">(24)</span>
            </label>
            <!-- Más opciones aquí -->
          </div>
        </div>

        <div class="filters-section">
          <h3>Edad</h3>
          <div class="filter-options">
            <label class="filter-option">
              <input type="checkbox" />
              <span>Adultos</span>
              <span class="count">(156)</span>
            </label>
            <!-- Más opciones aquí -->
          </div>
        </div>

        <div class="filters-section">
          <h3>Idioma</h3>
          <div class="filter-options">
            <label class="filter-option">
              <input type="checkbox" />
              <span>Castellano</span>
              <span class="count">(89)</span>
            </label>
            <!-- Más opciones aquí -->
          </div>
        </div>

        <div class="filters-section">
          <h3>Horario</h3>
          <div class="filter-options">
            <label class="filter-option">
              <input type="checkbox" />
              <span>Mañana</span>
              <span class="count">(45)</span>
            </label>
            <!-- Más opciones aquí -->
          </div>
        </div>
      </aside>

      <!-- Main Content -->
      <main class="main-content">
        <div class="content-header">
          <button class="filter-toggle" @click="toggleFilters" v-if="isMobile">
            <filter-icon />
            Filtros
          </button>
          <div class="results-count">
            {{ activityStore.activities.length }} actividades disponibles
          </div>
          <div class="sort-dropdown">
            <select>
              <option>Más relevante</option>
              <option>Fecha más próxima</option>
              <option>A-Z</option>
            </select>
          </div>
        </div>

        <div class="activities-grid">
          <activity-card
            v-for="activity in activityStore.activities"
            :key="activity.id"
            :nombre="activity.nombre"
            :imagen="activity.imagen"
            :dates="activity.dates"
            :schedule="activity.schedule"
            :days="activity.days"
            :id="activity.id"
            @register="handleRegister"
            @show-login="showLoginOptions"
          />
        </div>

        <div class="pagination">
          <button
            v-for="page in 5"
            :key="page"
            :class="['page-button', { active: page === 1 }]"
          >
            {{ page }}
          </button>
        </div>
      </main>
    </div>

    <LoginOptions v-if="showLogin" @tmc-selected="showTMCLogin" />
    <LoginTMC v-if="showTMC" @back-to-login="showLoginOptions" @login-success="handleLoginSuccess" />
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { FilterIcon } from 'lucide-vue-next'
import ActivityCard from '../components/ActivityCard.vue'
import { useActivityStore } from '../stores/store'
import Navbar from '../components/Navbar.vue'
import LoginOptions from '../components/LoginOptions.vue'
import LoginTMC from '../components/LoginTMC.vue'
import { useAuthStore } from '../stores/authStore'
import axios from 'axios'
import { useRouter } from 'vue-router';
import MisActividades from '../components/MisActividades.vue';

const activityStore = useActivityStore()
const filtersOpen = ref(false)
const isMobile = computed(() => window.innerWidth < 768)
const showLogin = ref(false)
const showTMC = ref(false)
const authStore = useAuthStore()
const router = useRouter();

const showMyActivitiesComponent = ref(false);

const toggleFilters = () => {
  filtersOpen.value = !filtersOpen.value
}

const showLoginOptions = () => {
  showLogin.value = true
  showTMC.value = false
}

const showTMCLogin = () => {
  showLogin.value = false
  showTMC.value = true
}

const handleLoginSuccess = () => {
  showLogin.value = false
  showTMC.value = false
}

const goToHome = () => {
  showLogin.value = false;
  showTMC.value = false;
  showMyActivitiesComponent.value = false
}
const showMyActivities = () => {
  showMyActivitiesComponent.value = !showMyActivitiesComponent.value;
}
const API_URL = import.meta.env.VITE_API_AUTH_URL;

let idleTimeout;
const idleDuration = 60 * 60 * 1000; // 1 hour in milliseconds

const resetIdleTimeout = () => {
  clearTimeout(idleTimeout);
  idleTimeout = setTimeout(logout, idleDuration);
};

const logout = async () => {
  try {
    await axios.get(`${API_URL}/auth/logout`);
    authStore.clearToken();
    router.push('/');
    window.location.reload()
  } catch (error) {
    console.error('Logout failed:', error);
  }
};

const validateTokenOnLoad = async () => {
  console.log('validateTokenOnLoad called');
  if (authStore.token) {
    console.log('Token found in authStore:', authStore.token);
    axios.defaults.headers.common['Authorization'] = `Bearer ${authStore.token}`;
    console.log('Authorization header set:', axios.defaults.headers.common['Authorization']);
    try {
      const response = await axios.get(`${API_URL}/auth/validate-token`);
      console.log('validate-token response:', response);
      if (response.status !== 200) {
        authStore.clearToken();
        delete axios.defaults.headers.common['Authorization'];
        console.log("token invalid - clearing token");
      } else {
        console.log("token is valid - keeping user logged in");
      }
    } catch (error) {
      console.error('Token validation failed:', error);
      authStore.clearToken();
      delete axios.defaults.headers.common['Authorization'];
      console.log("token invalid - clearing token");
    }
  } else {
    console.log('No token found in authStore');
  }
};

const handleRegister = async (activityId) => {
  try {
    const response = await axios.post(`${API_URL}/activityUser/add`, { activity_id: activityId });
    console.log('Registration successful:', response.data);
    // Handle success, e.g., show a success message
  } catch (error) {
    console.error('Registration failed:', error);
    // Handle error, e.g., show an error message
  }
};

onMounted(() => {
  console.log('ActivitiesPortal mounted');
  activityStore.getActivities();
  validateTokenOnLoad();

  // Start tracking activity for idle logout
  window.addEventListener('mousemove', resetIdleTimeout);
  window.addEventListener('keypress', resetIdleTimeout);
  resetIdleTimeout(); // Initial call to set the timeout
});
</script>

<style scoped>
.activities-portal {
  min-height: 100vh;
  background-color: #fff;
}

.main-container {
  display: flex;
  max-width: 1400px;
  margin: 72px auto 0;
  padding: 0 24px;
  gap: 2rem;
}

.filters-sidebar {
  width: 280px;
  flex-shrink: 0;
  padding: 1.5rem;
  height: calc(100vh - 72px);
  position: sticky;
  top: 72px;
  overflow-y: auto;
  border-right: 1px solid #e5e7eb;
}

.filters-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.filters-header h2 {
  font-size: 1.25rem;
  font-weight: 700;
  color: #006758;
}

.close-filters {
  display: none;
}

.filters-section {
  margin-bottom: 1.5rem;
  padding-bottom: 1.5rem;
  border-bottom: 1px solid #e5e7eb;
}

.filters-section h3 {
  font-size: 1rem;
  font-weight: 600;
  margin-bottom: 0.75rem;
  color: #111827;
}

.filter-options {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.filter-option {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
  font-size: 0.875rem;
  color: #374151;
}

.count {
  color: #6b7280;
  font-size: 0.75rem;
}

.main-content {
  flex: 1;
  padding: 1.5rem 0;
}

.content-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 2rem;
}

.filter-toggle {
  display: none;
}

.results-count {
  font-size: 0.875rem;
  color: #6b7280;
}

.sort-dropdown select {
  padding: 0.5rem 2rem 0.5rem 1rem;
  border: 1px solid #e5e7eb;
  border-radius: 4px;
  font-size: 0.875rem;
  color: #374151;
  background-color: white;
}

.activities-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 2rem;
}

@media (max-width: 1200px) {
  .activities-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 767px) {
  .filters-sidebar {
    position: fixed;
    left: -100%;
    top: 0;
    bottom: 0;
    width: 100%;
    max-width: 320px;
    background: white;
    z-index: 50;
    transition: left 0.3s ease;
  }

  .show-filters {
    left: 0;
  }
}

.pagination {
  display: flex;
  justify-content: center;
  gap: 0.5rem;
  margin-top: 2rem;
}

.page-button {
  height: 2.5rem;
  width: 2.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 1px solid #e5e7eb;
  background: white;
  color: #374151;
  font-size: 0.875rem;
  cursor: pointer;
}

.page-button.active {
  background-color: #006758;
  color: white;
  border-color: #006758;
}
</style>
