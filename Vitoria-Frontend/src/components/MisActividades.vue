<template>
    <div class="mis-actividades">
      <h1>Mis Actividades</h1>
    
        <div v-for="activityUser of activities" class="activity-item">
          <h2>{{ activityUser.activity.activity.nombre }}</h2>
          <img v-if="activityUser.activity.imagen" :src="activityUser.activity.imagen" :alt="activityUser.activity.nombre" class="activity-image" />
          <p><strong>Fechas:</strong> {{ activityUser.activity.fecha_inicio }} - {{ activityUser.activity.fecha_fin}}</p>
          <p><strong>Horario:</strong> {{ activityUser.activity.hora_inicio }} - {{ activityUser.activity.hora_fin }}</p>
          <p><strong>Días:</strong> {{ activityUser.activity.dias }}</p>
        </div>

    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  import { useAuthStore } from '../stores/authStore';
  
  const activities = ref([]);
  const loading = ref(false);
  const error = ref(null);
  const authStore = useAuthStore();
  const API_URL = import.meta.env.VITE_API_AUTH_URL;
  
  onMounted(async () => {
    loading.value = true;
    error.value = null;
    try {
      const response = await axios.get(
        `${API_URL}/activityUser/getMy`,
        {
          headers: {
            Authorization: `Bearer ${authStore.getToken}`
          }
        }
      );
  
      console.log(response.data);
  
      if (response.data && response.data.data) {
        activities.value = response.data.data;
        console.log("Las actividades son "+activities.value.length);
      } else {
        throw new Error('Unexpected response format');
      }
    } catch (err) {
      console.error('Error details:', err);
      error.value = err.message || 'Error al cargar las actividades';
    } finally {
      loading.value = false;
    }
  });
  </script>
  
  <style scoped>
  .mis-actividades {
    padding: 20px;
    font-family: sans-serif;
  }
  
  .activities-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }
  
  .activity-item {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    background-color: #f9f9f9;
  }
  
  .activity-image {
    max-width: 100%;
    height: auto;
    border-radius: 4px;
  }
  </style>
  