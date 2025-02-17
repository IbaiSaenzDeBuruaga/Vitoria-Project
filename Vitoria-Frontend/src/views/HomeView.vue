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
            <label class="filter-option" v-for="centro in centros" :key="centro.id">
              <input type="checkbox" :value="centro.id" v-model="selectedCentro" />
              <span>{{ centro.nombre }}</span>
              <span class="count">({{ countActividades(centro.id) }})</span>
            </label>
          </div>
        </div>

        <div class="filters-section">
          <h3>Edad</h3>
          <div class="filter-options">
            <label class="filter-option" v-for="edadRange in edadRanges" :key="edadRange.id">
              <input type="checkbox" :value="edadRange.id" v-model="selectedEdad" />
              <span>{{ edadRange.label }}</span>
              <span class="count">({{ countActivitiesByEdad(edadRange.min, edadRange.max) }})</span>
            </label>
          </div>
        </div>

        <div class="filters-section">
          <h3>Idioma</h3>
          <div class="filter-options">
            <label class="filter-option" v-for="idioma in idiomas" :key="idioma.value">
              <input type="checkbox" :value="idioma.value" v-model="selectedIdioma" />
              <span>{{ idioma.label }}</span>
              <span class="count">({{ countActivitiesByIdioma(idioma.value) }})</span>
            </label>
          </div>
        </div>

        <div class="filters-section">
          <h3>Horario</h3>
          <div class="filter-options">
            <label class="filter-option" v-for="horario in horarios" :key="horario.value">
              <input type="checkbox" :value="horario.value" v-model="selectedHorario" />
              <span>{{ horario.label }}</span>
              <span class="count">({{ countActivitiesByHorario(horario.value) }})</span>
            </label>
          </div>
        </div>

       

        <!-- REMOVED THIS SECTION -->
        <!-- <p>Centros: {{ selectedCentro }}</p>
        <p>Edad: {{ selectedEdad }}</p>
        <p>Idioma: {{ selectedIdioma }}</p>
        <p>Horario: {{ selectedHorario }}</p> -->
      </aside>

      <!-- Main Content -->
      <main class="main-content">
        <div class="content-header">
          <button class="filter-toggle" @click="toggleFilters" v-if="isMobile">
            <filter-icon />
            Filtros
          </button>
          <div class="results-count">
            {{ totalActivities }} actividades disponibles
          </div>
          <div class="sort-dropdown">
            <select v-model="sortBy">
              <option value="date">Fecha más próxima</option>
              <option value="az">A-Z</option>
            </select>
          </div>
        </div>

        <div class="activities-grid">
          <activity-card
            v-for="activity in paginatedActivities"
            :key="activity.id"
            :nombre="activity.activity.nombre"
            :imagen="activity.activity.imagen"
            :dates="`${activity.fecha_inicio} - ${activity.fecha_fin}`"
            :schedule="`${activity.hora_inicio} - ${activity.hora_fin}`"
            :days="activity.dias"
            :id="activity.id"
            @register="handleRegister"
            @show-login="showLoginOptions"
          />
        </div>

        <div class="pagination">
          <button
            v-for="page in totalPages"
            :key="page"
            :class="['page-button', { active: currentPage === page }]"
            @click="goToPage(page)"
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
import { ref, onMounted, computed, watch } from 'vue'
import { FilterIcon } from 'lucide-vue-next'
import ActivityCard from '../components/ActivityCard.vue'
import Navbar from '../components/Navbar.vue'
import LoginOptions from '../components/LoginOptions.vue'
import LoginTMC from '../components/LoginTMC.vue'
import { useAuthStore } from '../stores/authStore'
import axios from 'axios'
import { useRouter } from 'vue-router';
import MisActividades from '../components/MisActividades.vue';
import { useCentrosStore } from '@/stores/centros'  // Import CentrosStore

const centrosStore = useCentrosStore();  // Use CentrosStore

const filtersOpen = ref(false)
const isMobile = computed(() => window.innerWidth < 768)
const showLogin = ref(false)
const showTMC = ref(false)
const authStore = useAuthStore()
const router = useRouter();

const selectedCentro = ref([]);
const selectedEdad = ref([]);
const selectedIdioma = ref([]);
const selectedHorario = ref([]);
const sortBy = ref('relevance'); // 'relevance', 'date', 'az'

const centros = ref([]);
const allActivities = ref([]); // Store all activities here

const idiomas = [
  { value: 'es', label: 'Castellano' },
  { value: 'en', label: 'Inglés' },
  { value: 'eu', label: 'Euskera' },
];

const horarios = [
  { value: 'matutino', label: 'Mañana' },
  { value: 'vespertino', label: 'Tarde' },
];

const edadRanges = [
  { id: 1, label: 'Niños', min: 0, max: 12 },
  { id: 2, label: 'Jóvenes', min: 13, max: 17 },
  { id: 3, label: 'Adultos', min: 18, max: 150 }, // Assuming a reasonable max age
];

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

// Pagination variables
const currentPage = ref(1);
const perPage = ref(6);  // Set a default value
const totalPages = computed(() => Math.ceil(filteredActivities.value.length / perPage.value)); // Initialize
const totalActivities = computed(() => filteredActivities.value.length);


const goToPage = (page) => {
  currentPage.value = page;
};

const filteredActivities = computed(() => {
  let activities = allActivities.value;

  if (selectedCentro.value.length > 0) {
    activities = activities.filter(activity => selectedCentro.value.includes(activity.centro_id));
  }
  if (selectedEdad.value.length > 0) {
    activities = activities.filter(activity => {
      return selectedEdad.value.some(edadId => {
        const range = edadRanges.find(r => r.id === edadId);
        return activity.activity.edad_min <= range.max && activity.activity.edad_max >= range.min;
      });
    });
  }

  if (selectedIdioma.value.length > 0) {
    activities = activities.filter(activity => selectedIdioma.value.includes(activity.activity.idioma));
  }
  if (selectedHorario.value.length > 0) {
    activities = activities.filter(activity => selectedHorario.value.includes(activity.activity.horario));
  }

  // Sorting
  if (sortBy.value === 'date') {
    activities.sort((a, b) => new Date(a.fecha_inicio) - new Date(b.fecha_inicio));
  } else if (sortBy.value === 'az') {
    activities.sort((a, b) => a.activity.nombre.localeCompare(b.activity.nombre));
  }
  // No need for else if 'relevance', as it's the default order

  return activities;
});

const paginatedActivities = computed(() => {
  const start = (currentPage.value - 1) * perPage.value;
  const end = start + perPage.value;
  return filteredActivities.value.slice(start, end);
});


onMounted(async () => {
  // Fetch centros
  await centrosStore.fetchAllCentros();
  centros.value = centrosStore.allCentros;

  // Fetch all activities
  try {
    const response = await axios.get(`${API_URL}/activity/all`);
    allActivities.value = response.data.ActividadesCentro; // Store *all* activities
    console.log(allActivities.value);
  } catch (error) {
    console.error("Error fetching activities:", error);
  }

    validateTokenOnLoad();

  // Start tracking activity for idle logout
  window.addEventListener('mousemove', resetIdleTimeout);
  window.addEventListener('keypress', resetIdleTimeout);
  resetIdleTimeout(); // Initial call to set the timeout
});


// Count functions (now use allActivities)
const countActividades = (centroId) => {
  return allActivities.value.filter(act => act.centro_id === centroId).length;
};

const countActivitiesByEdad = (min, max) => {
  return allActivities.value.filter(activity => {
    return activity.activity.edad_min <= max && activity.activity.edad_max >= min;
  }).length;
};
const countActivitiesByIdioma = (idioma) => {
  return allActivities.value.filter(activity => activity.activity.idioma === idioma).length;
};

const countActivitiesByHorario = (horario) => {
  return allActivities.value.filter(activity => activity.activity.horario === horario).length;
};


function aplicarFiltros() {
  // The filtering is now handled by the `filteredActivities` computed property
  currentPage.value = 1; // Reset to the first page
}

watch(filteredActivities, () => {
  currentPage.value = 1; // Reset page when filters change
});

</script>

<style scoped>
/* No scrollbar on filters-sidebar */
.filters-sidebar {
  width: 280px;
  flex-shrink: 0;
  padding: 1.5rem;
  height: calc(100vh - 72px);
  position: sticky;
  top: 72px;
  /* overflow-y: auto;  <-- REMOVE THIS */
  border-right: 1px solid #e5e7eb;
}


/* ... (the rest of your existing styles) ... */
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