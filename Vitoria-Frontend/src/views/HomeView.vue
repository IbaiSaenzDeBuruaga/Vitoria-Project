<template>
  <div class="activities-portal">
    <Navbar
      @show-login="showLoginOptions"
      @show-login-tmc="() => showTMCLogin(false)"
      @go-home="goToHome"
      @logout="logout"
      @goAdmin="goAdmin" 
      @show-my-activities="showMyActivities"
    />

    <MisActividades v-if="showMyActivitiesComponent" />

    <div v-else-if="!showLogin && !showTMC" class="main-container">
      <!-- Botón para mostrar/ocultar filtros en móvil -->
      <button class="filter-toggle" @click="toggleFilters">
        <FilterIcon />
        Filtros
      </button>

      <!-- Sidebar with Filters -->
      <aside class="filters-sidebar" :class="{ 'show-filters': filtersOpen }">
        <button class="close-filters" @click="toggleFilters">×</button>
        <div class="filters-header">
          <h2>Filtros</h2>
        </div>

        <div class="filters-section" :class="{ 'collapsed': !centrosExpanded }">
          <div class="filter-section-header" @click="centrosExpanded = !centrosExpanded">
            <h3>Centro cívico</h3>
            <span class="collapse-icon">{{ centrosExpanded ? '-' : '+' }}</span>
          </div>
          <div class="filter-options" v-if="centrosExpanded">
            <label class="filter-option" v-for="centro in centros" :key="centro.id">
              <input type="checkbox" :value="centro.id" v-model="selectedCentro" @change="aplicarFiltros"/>
              <span>{{ centro.nombre }}</span>
              <span class="count">({{ countActividades(centro.id) }})</span>
            </label>
          </div>
        </div>

        <div class="filters-section" :class="{ 'collapsed': !edadExpanded }">
          <div class="filter-section-header" @click="edadExpanded = !edadExpanded">
            <h3>Edad</h3>
            <span class="collapse-icon">{{ edadExpanded ? '-' : '+' }}</span>
          </div>
          <div class="filter-options" v-if="edadExpanded">
            <label class="filter-option" v-for="edadRange in edadRanges" :key="edadRange.id">
              <input type="checkbox" :value="edadRange.id" v-model="selectedEdad" @change="aplicarFiltros"/>
              <span>{{ edadRange.label }}</span>
              <span class="count">({{ countActivitiesByEdad(edadRange.min, edadRange.max) }})</span>
            </label>
          </div>
        </div>

        <div class="filters-section" :class="{ 'collapsed': !idiomaExpanded }">
          <div class="filter-section-header" @click="idiomaExpanded = !idiomaExpanded">
            <h3>Idioma</h3>
            <span class="collapse-icon">{{ idiomaExpanded ? '-' : '+' }}</span>
          </div>
          <div class="filter-options" v-if="idiomaExpanded">
            <label class="filter-option" v-for="idioma in idiomas" :key="idioma.value">
              <input type="checkbox" :value="idioma.value" v-model="selectedIdioma" @change="aplicarFiltros"/>
              <span>{{ idioma.label }}</span>
              <span class="count">({{ countActivitiesByIdioma(idioma.value) }})</span>
            </label>
          </div>
        </div>

        <div class="filters-section" :class="{ 'collapsed': !horarioExpanded }">
          <div class="filter-section-header" @click="horarioExpanded = !horarioExpanded">
            <h3>Horario</h3>
            <span class="collapse-icon">{{ horarioExpanded ? '-' : '+' }}</span>
          </div>
          <div class="filter-options" v-if="horarioExpanded">
            <label class="filter-option" v-for="horario in horarios" :key="horario.value">
              <input type="checkbox" :value="horario.value" v-model="selectedHorario" @change="aplicarFiltros"/>
              <span>{{ horario.label }}</span>
              <span class="count">({{ countActivitiesByHorario(horario.value) }})</span>
            </label>
          </div>
        </div>
      </aside>

      <!-- Main Content -->
      <main class="main-content">
        <div class="content-header">
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
            @show-login="() => showTMCLogin(true)"
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

    <LoginOptions v-if="showLogin" @tmc-selected="() => showTMCLogin(false)" />
    <LoginTMC
      v-if="showTMC"
      @back-to-login="showLoginOptions"
      @login-success="handleLoginSuccess"
      :from-register="showTMCFromRegister"
    />

    <!-- Registration Success Popup -->
    <div v-if="showRegistrationSuccess" class="popup-overlay">
      <div class="popup-content">
        <p>¡Te has inscrito correctamente!</p>
        <button @click="showRegistrationSuccess = false">Cerrar</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import { FilterIcon, Route } from 'lucide-vue-next';
import ActivityCard from '../components/ActivityCard.vue';
import Navbar from '../components/Navbar.vue';
import LoginOptions from '../components/LoginOptions.vue';
import LoginTMC from '../components/LoginTMC.vue';
import { useAuthStore } from '../stores/authStore';
import axios from 'axios';
import { useRouter } from 'vue-router';
import MisActividades from '../components/MisActividades.vue';
import { useCentrosStore } from '@/stores/centros';
import { useActivityFiltroStore } from '../stores/activityFiltroStore';

const centrosStore = useCentrosStore();
const activityStore = useActivityFiltroStore();

const filtersOpen = ref(false);
const showLogin = ref(false);
const showTMC = ref(false);
const authStore = useAuthStore();
const router = useRouter();

const selectedCentro = ref([]);
const selectedEdad = ref([]);
const selectedIdioma = ref([]);
const selectedHorario = ref([]);
const sortBy = ref('date');

const centros = ref([]);

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
  { id: 3, label: 'Adultos', min: 18, max: 150 },
];

const showMyActivitiesComponent = ref(false);
const centrosExpanded = ref(true);
const edadExpanded = ref(true);
const idiomaExpanded = ref(true);
const horarioExpanded = ref(true);
const showRegistrationSuccess = ref(false);
const showTMCFromRegister = ref(false);

const toggleFilters = () => {
  filtersOpen.value = !filtersOpen.value;
   // Si los filtros se están abriendo, colapsa las secciones para una mejor experiencia en móvil
  if (filtersOpen.value) {
    centrosExpanded.value = false;
    edadExpanded.value = false;
    idiomaExpanded.value = false;
    horarioExpanded.value = false;
  }
};

const showLoginOptions = () => {
  showLogin.value = true;
  showTMC.value = false;
};

const showTMCLogin = (fromRegister = false) => {
  showLogin.value = false;
  showTMC.value = true;
  showTMCFromRegister.value = fromRegister;
};

const goToHome = () => {
  showLogin.value = false;
  showTMC.value = false;
  showMyActivitiesComponent.value = false;
};

const showMyActivities = () => {
  showMyActivitiesComponent.value = !showMyActivitiesComponent.value;
};

const API_URL = import.meta.env.VITE_API_AUTH_URL;

let idleTimeout;
const idleDuration = 60 * 60 * 1000;

const resetIdleTimeout = () => {
  clearTimeout(idleTimeout);
  idleTimeout = setTimeout(logout, idleDuration);
};

const logout = async () => {
  try {
    await axios.get(`${API_URL}/auth/logout`);
    authStore.clearToken();
    router.push('/');
    window.location.reload();
  } catch (error) {
    console.error('Logout failed:', error);
  }
};

const goAdmin = async () => {
  router.push('/admin');
};

const validateTokenOnLoad = async () => {
    if (authStore.token) {
        axios.defaults.headers.common['Authorization'] = `Bearer ${authStore.token}`;
        try {
            const response = await axios.get(`${API_URL}/auth/validate-token`);
            if (response.status !== 200) {
                authStore.clearToken();
                delete axios.defaults.headers.common['Authorization'];
            }
        } catch (error) {
            console.error('Token validation failed:', error);
            authStore.clearToken();
            delete axios.defaults.headers.common['Authorization'];
        }
    }
};

const handleRegister = async (activityId) => {
  if (!authStore.isLoggedIn) {
    showTMCLogin(true);
    return;
  }

  try {
    const response = await axios.post(`${API_URL}/activityUser/add`, { activity_id: activityId });
    console.log('Registration successful:', response.data);
    showRegistrationSuccess.value = true;
  } catch (error) {
    console.error('Registration failed:', error);
  }
};

const handleLoginSuccess = async () => {
  showLogin.value = false;
  showTMC.value = false;
  router.push('/');
};

const currentPage = ref(1);
const perPage = ref(6);
const totalPages = computed(() => Math.ceil(filteredActivities.value.length / perPage.value));
const totalActivities = computed(() => filteredActivities.value.length);

const goToPage = (page) => {
  currentPage.value = page;
};

const filteredActivities = computed(() => {
  let activities = activityStore.allActivities;

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

  if (sortBy.value === 'date') {
    activities.sort((a, b) => new Date(a.fecha_inicio) - new Date(b.fecha_inicio));
  } else if (sortBy.value === 'az') {
    activities.sort((a, b) => a.activity.nombre.localeCompare(b.activity.nombre));
  }

  return activities;
});

const paginatedActivities = computed(() => {
  const start = (currentPage.value - 1) * perPage.value;
  const end = start + perPage.value;
  return filteredActivities.value.slice(start, end);
});

onMounted(async () => {
  await centrosStore.fetchAllCentros();
  centros.value = centrosStore.allCentros;
  await activityStore.fetchActivitiesAll();

  validateTokenOnLoad();

  window.addEventListener('mousemove', resetIdleTimeout);
  window.addEventListener('keypress', resetIdleTimeout);
  resetIdleTimeout();
});

watch(filteredActivities, () => {
  currentPage.value = 1;
});

const countActividades = (centroId) => {
  return activityStore.allActivities.filter(act => act.centro_id === centroId).length;
};

const countActivitiesByEdad = (min, max) => {
  return activityStore.allActivities
    .filter(activity => {
      return activity.activity.edad_min <= max && activity.activity.edad_max >= min;
    })
    .length;
};

const countActivitiesByIdioma = (idioma) => {
  return activityStore.allActivities.filter(activity => activity.activity.idioma === idioma).length;
};

const countActivitiesByHorario = (horario) => {
  return activityStore.allActivities.filter(activity => activity.activity.horario === horario).length;
};

function aplicarFiltros() {
  currentPage.value = 1;
   // Cierra los filtros después de aplicar en móvil
  filtersOpen.value = false;
}
</script>

<style scoped>
/* Style for the minimal scrollbar */
.filters-sidebar::-webkit-scrollbar {
  width: 5px;
}

.filters-sidebar::-webkit-scrollbar-track {
  background: #f1f1f1;
}

.filters-sidebar::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 2.5px;
}

.filters-sidebar::-webkit-scrollbar-thumb:hover {
  background: #555;
}

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
  background-color: white;
  transition: transform 0.3s ease-in-out;
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
  overflow: hidden;
  transition: max-height 0.3s ease;
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
  padding: 1.5rem;
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

.close-filters {
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

.filter-section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  cursor: pointer;
  padding: 0.5rem 0;
}

.collapse-icon {
  font-size: 1rem;
  color: #6b7280;
}

.collapsed .filter-options {
  max-height: 0;
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

/* Popup Styles */
.popup-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.popup-content {
  background-color: white;
  padding: 20px;
  border-radius: 8px;
  text-align: center;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.popup-content p {
  margin-bottom: 1rem;
  color: #333;
  font-size: 1.1rem;
}

.popup-content button {
  background-color: #006758;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 1rem;
  transition: background-color 0.3s ease;
}

.popup-content button:hover {
  background-color: #004c3f;
}

@media (max-width: 1200px) {
  .activities-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 767px) {
  .main-container {
    flex-direction: column;
  }

  .filters-sidebar {
    position: fixed;
    left: 0;
    top: 0;
    bottom: 0;
    width: 280px;
    max-width: 100%;
    z-index: 1000;
    transform: translateX(-100%);
     /* Asegura que los filtros estén colapsados inicialmente en móvil */
    overflow-y: auto; /* Asegura que el contenido de los filtros sea scrollable */
  }

  .filters-sidebar.show-filters {
    transform: translateX(0);
  }

  .filter-toggle {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem 1rem;
    margin-bottom: 1rem;
    background-color: #006758;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }

  .close-filters {
    display: block;
    position: absolute;
    top: 10px;
    right: 10px;
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
  }

  .activities-grid {
    grid-template-columns: 1fr;
  }
    /* Estilos para colapsar las secciones por defecto en móvil */
  .filters-section.collapsed .filter-options {
    max-height: 0;
    overflow: hidden;
    padding: 0;  /* Añadido para eliminar el padding cuando está colapsado */
  }

  .filter-section-header {
    padding: 0.5rem 0; /* Ajusta el padding si es necesario */
  }

}
</style>