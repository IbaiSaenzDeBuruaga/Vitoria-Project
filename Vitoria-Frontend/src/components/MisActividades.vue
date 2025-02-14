<template>
    <div class="mis-actividades">
      <h1>Mis Actividades</h1>
  
      <div v-for="activityUser in activities" :key="activityUser.id" class="activity-item">
        <div class="activity-header">
          <h2>{{ activityUser.activity.nombre }}</h2>
          <button @click="unsubscribe(activityUser.id)" class="unsubscribe-button">Desinscribirse</button>
        </div>
        <img v-if="activityUser.activity.imagen" :src="activityUser.activity.imagen" :alt="activityUser.activity.nombre" class="activity-image" />
        <p><strong>Fechas:</strong> {{ activityUser.activity.fecha_inicio }} - {{ activityUser.activity.fecha_fin }}</p>
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

        if (response.data && response.data.data) {
            activities.value = response.data.data;
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

const unsubscribe = async (activityUser) => {
    try {
        console.log(activityUser);
        const response = await axios.delete(
            `${API_URL}/activityUser/delete`,
            {
                headers: {
                    Authorization: `Bearer ${authStore.getToken}`
                },
                data: {
                    activity_id: activityUser.activity.id
                }
            }
        );
        if (response.status == 200) {
            alert('Actividad borrada correctamente');
            activities.value = activities.value.filter(a => a.activity.id !== activityUser.activity.id); // Use activityUser.id to filter
        }

    } catch (err) {
        console.error('Error al desinscribirse:', err);
        alert('Error al desinscribirse de la actividad.');
    }
};
</script>
  <style scoped>
  .mis-actividades {
    padding: 20px;
    font-family: sans-serif;
    max-width: 800px;
    margin: auto;
  }
  
  .activity-item {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    background-color: #f9f9f9;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
    margin-bottom: 20px;
  }
  
  .activity-item:hover {
    transform: translateY(-5px);
  }
  
  .activity-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  
  .activity-image {
    max-width: 100%;
    height: auto;
    border-radius: 4px;
    margin-bottom: 10px;
  }
  
  .unsubscribe-button {
    background-color: #ff4d4d;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 5px 10px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }
  
  .unsubscribe-button:hover {
    background-color: #ff1a1a;
  }
  </style>
  