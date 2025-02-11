<template>
  <div class="activities-portal">
    <!-- Header -->
    <header>
      <div class="header-content">
        <div class="logo-container">
          <img
            src="../assets/images/logo.png"
            alt="Vitoria Gasteiz"
            class="logo"
          />
          <div class="logo-text">
            <span class="bold">sede</span> <span>electrónica</span>
          </div>
        </div>
        <button class="connect-button">
          <user-circle-2-icon />
          Conectar
        </button>
      </div>
    </header>

    <!-- Filters -->
    <div class="filters">
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

    <!-- Activities Grid -->
    <main>
      <div class="activities-grid">
        <activity-card
          v-for="activity in activities"
          :key="activity.title"
          v-bind="activity"
        />
      </div>
    </main>

    <!-- Pagination -->
    <div class="pagination">
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
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { UserCircle2Icon, SearchIcon } from 'lucide-vue-next'
import ActivityCard from '../components/ActivityCard.vue'

const activities = ref([
  {
    title: "Yoga",
    image: "/placeholder.svg?height=200&width=300",
    dates: "29/06/2023 - 29/07/2025",
    schedule: "11:00-11:30",
    days: "Martes y Jueves"
  },
  {
    title: "Club de Lectura",
    image: "/placeholder.svg?height=200&width=300",
    dates: "29/06/2023 - 29/07/2025",
    schedule: "11:00-11:30",
    days: "Martes y Jueves"
  },
  {
    title: "Futbol",
    image: "/placeholder.svg?height=200&width=300",
    dates: "29/06/2023 - 29/07/2025",
    schedule: "11:00-11:30",
    days: "Martes y Jueves"
  },
  {
    title: "Balonmano",
    image: "/placeholder.svg?height=200&width=300",
    dates: "29/06/2023 - 29/07/2025",
    schedule: "11:00-11:30",
    days: "Martes y Jueves"
  },
  {
    title: "Natación",
    image: "/placeholder.svg?height=200&width=300",
    dates: "29/06/2023 - 29/07/2025",
    schedule: "11:00-11:30",
    days: "Martes y Jueves"
  },
  {
    title: "Pilates",
    image: "/placeholder.svg?height=200&width=300",
    dates: "29/06/2023 - 29/07/2025",
    schedule: "11:00-11:30",
    days: "Martes y Jueves"
  },
  {
    title: "Tenis",
    image: "/placeholder.svg?height=200&width=300",
    dates: "29/06/2023 - 29/07/2025",
    schedule: "11:00-11:30",
    days: "Martes y Jueves"
  },
  {
    title: "Baloncesto",
    image: "/placeholder.svg?height=200&width=300",
    dates: "29/06/2023 - 29/07/2025",
    schedule: "11:00-11:30",
    days: "Martes y Jueves"
  }
  
])
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
}

.filters-content {
  max-width: 72rem;
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
  max-width: 72rem;
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