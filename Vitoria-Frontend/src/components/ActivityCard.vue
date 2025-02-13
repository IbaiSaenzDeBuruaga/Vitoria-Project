<template>
  <div class="activity-card">
    <div class="activity-content">
      <div class="activity-image">
        <img
          :src="imagen || '/placeholder.svg'"
          :alt="nombre" 
        />
      </div>
      <div class="activity-info">
        <div>
          <h3>{{ nombre }}</h3>
          <p>Fecha: {{ dates }}</p>
          <p>Horario: {{ schedule }}</p>
          <p>Días: {{ days }}</p>
        </div>
        <button class="register-button">
          Inscribirse
          <chevron-right-icon />
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ChevronRightIcon } from 'lucide-vue-next'

defineProps({
  nombre: String,
  imagen: String,
  dates: String,
  schedule: String,
  days: String
})
</script>

<style scoped>
.activity-card {
  overflow: hidden;
  border-radius: 0.5rem;
  background-color: #006758;
  padding: 1rem;
  border-radius: 25px;
}

.activity-content {
  display: flex;
  align-content: space-between;
  
}

.activity-image {
  position: relative;
  height: 190px;
  width: 300px;
  flex-shrink: 0;
  overflow: hidden;
  border-radius: 0.5rem;
  margin-right: 1rem;
}

.activity-image img {
  object-fit: cover;
  width: 100%;
  height: 100%;
}

.activity-info {
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  color: white;
}

.activity-info h3 {
  font-size: 1.5rem;
  font-weight: bold;
  margin-bottom: 0.5rem;
}

.activity-info p {
  font-size: 0.875rem;
  margin-bottom: 0.25rem;
}

.register-button {
  display: flex;
  align-items: center;
  gap: 0.25rem;
  border-radius: 9999px;
  background-color: white;
  padding: 0.5rem 1rem;
  font-size: 0.875rem;
  font-weight: 500;
  color: #006758;
  align-self: flex-end;
  border: none;
  cursor: pointer;
}
</style>