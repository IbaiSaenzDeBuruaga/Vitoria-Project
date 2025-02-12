<template>
  <div class="activities-portal">
    <!-- Header -->
    <header>
      <div class="header-content">
        <div class="logo-container" @click="resetToHome">
          <img
            src="../assets/images/logo.png"
            alt="Vitoria Gasteiz"
            class="logo"
            style="cursor: pointer;"
          />
          <div class="logo-text">
            <span class="bold">sede</span> <span>electrónica</span>
          </div>
        </div>
        <button class="connect-button" @click="showLoginOptions" v-if="!isLoggedIn">
          <user-circle-2-icon />
          Conectar
        </button>
      </div>
    </header>

    <!-- Filters -->
    <div class="filters" v-if="!showLogin && !showTMC && !isLoggedIn">
      <div class="filters-content">
        <div class="filter-item">
          <label>Centro cívico</label>
          <select>
            <option>Iparralde</option>
          </select>
        </div>

        <div class="filter-item">
          <label>Edad</label>
          <select>
            <option>24</option>
          </select>
        </div>

        <div class="filter-item">
          <label>Idioma</label>
          <select>
            <option>Castellano</option>
          </select>
        </div>

        <div class="filter-item">
          <label>Horario</label>
          <select>
            <option>Mañana</option>
          </select>
        </div>

        <button class="search-button">
          <search-icon />
        </button>
      </div>
    </div>

    <!-- Activities Grid / Login Options -->
    <main>
      <LoginOptions v-if="showLogin && !isLoggedIn" @tmc-selected="showTMCLogin" />
      <LoginTMC v-else-if="showTMC && !isLoggedIn" @back-to-login="showLoginOptions" @login-success="handleLoginSuccess"/>

      <div v-if="activityStore.loading">Cargando actividades...</div>
      <div v-else-if="activityStore.error">Error: {{ activityStore.error }}</div>
      <div v-else class="activities-grid">
        <activity-card
      v-for="activity in activityStore.activities"
      :key="activity.id"
      :nombre="activity.nombre"
      :imagen="activity.imagen"
      :dates="activity.dates" 
      :schedule="activity.schedule" 
      :days="activity.days"    
    />
      </div>
    </main>

    <!-- Pagination -->
    <div class="pagination" v-if="!showLogin && !showTMC && !isLoggedIn">
      <div class="pagination-content">
        <button
          v-for="page in 5"
          :key="page"
          :class="['page-button', { active: page === 1 }]"
        >
          {{ page }}
        </button>
      </div>
    </div>
    <Footer />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { UserCircle2Icon, SearchIcon } from 'lucide-vue-next'
import ActivityCard from '../components/ActivityCard.vue'
import LoginOptions from '../components/LoginOptions.vue'
import LoginTMC from '../components/LoginTMC.vue'
import Footer from '../components/Footer.vue'
import { useActivityStore } from '../stores/store'; // Importa el store de Pinia

const activityStore = useActivityStore(); // Usa el store

const showLogin = ref(false)
const showTMC = ref(false)
const isLoggedIn = ref(false)

const showLoginOptions = () => {
  showLogin.value = true
  showTMC.value = false
}

const showTMCLogin = () => {
  showLogin.value = false
  showTMC.value = true
}

const resetToHome = () => {
  showLogin.value = false
  showTMC.value = false
  isLoggedIn.value = false
}

const handleLoginSuccess = () => {
    showLogin.value = false
    showTMC.value = false
    isLoggedIn.value = true
}

onMounted(() => {
  activityStore.getActivities(); // Llama a la función para obtener las actividades al montar el componente
});
</script>

<style scoped>
.activities-portal {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  background-color: white;
}

header {
  border-bottom: 1px solid #e2e8f0;
  padding: 0 1rem;
}

.header-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0.5rem 0;
}

.logo-container {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.logo {
  height: 2.5rem;
  width: 2.5rem;
}

.logo-text {
  font-size: 1.125rem;
}

.bold {
  font-weight: bold;
}

.connect-button {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: #006758;
  background: none;
  border: none;
  cursor: pointer;
}

.filters {
  background-color: #006758;
  padding: 0.5rem 1rem;
  border-bottom-right-radius: 10px;
  border-bottom-left-radius: 10px;
}

.filters-content {
  max-width: 100%;
  margin: 0 auto;
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: center;
  gap: 1.5rem;
}

.filter-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.filter-item label {
  font-size: 0.875rem;
  font-weight: 500;
  color: white;
}

.filter-item select {
  border-radius: 9999px;
  background-color: white;
  padding: 0.25rem 1rem;
  font-size: 0.875rem;
}

.search-button {
  color: white;
  background: none;
  border: none;
  cursor: pointer;
}

main {
  flex: 1;
  padding: 2rem 1rem;
}

.activities-grid {
  max-width: auto;
  margin: 0 auto;
  display: grid;
  gap: 1.5rem;
}

@media (min-width: 768px) {
  .activities-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

.pagination {
  border-top: 1px solid #e2e8f0;
  padding: 1.5rem 0;
}

.pagination-content {
  display: flex;
  justify-content: center;
  gap: 0.5rem;
}

.page-button {
  height: 2rem;
  width: 2rem;
  border-radius: 9999px;
  border: 1px solid #006758;
  color: #006758;
  background: none;
  cursor: pointer;
}

.page-button.active {
  background-color: #006758;
  color: white;
}
</style>