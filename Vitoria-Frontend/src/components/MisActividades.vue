<template>
  <div class="mis-actividades">
    <h1 class="title">Mis Actividades</h1>

    <div v-if="loading" class="loading">Cargando actividades...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <div v-else-if="activities.length === 0" class="no-activities">No tienes actividades inscritas.</div>
    
    <div v-else class="activities-grid">
      <div v-for="activityUser in activities" :key="activityUser.id" class="activity-card">
        <img v-if="activityUser.activity_centro.activity.imagen" :src="activityUser.activity_centro.activity.imagen" :alt="activityUser.activity_centro.activity.nombre" class="activity-image" />
        <div class="activity-content">
          <h2 class="activity-title">{{ activityUser.activity_centro.activity.nombre }}</h2>
          <p class="activity-info"><strong>Fechas:</strong> {{ activityUser.activity_centro.fecha_inicio }} - {{ activityUser.activity_centro.fecha_fin }}</p>
          <p class="activity-info"><strong>Horario:</strong> {{ activityUser.activity_centro.hora_inicio }} - {{ activityUser.activity_centro.hora_fin }}</p>
          <p class="activity-info"><strong>Días:</strong> {{ activityUser.activity_centro.dias }}</p>
          <button @click="unsubscribe(activityUser.id)" class="unsubscribe-button">
            Desinscribirse
          </button>
        </div>
      </div>
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

const unsubscribe = async (activityUserId) => {
    try {
        const activityUser = activities.value.find(a => a.id === activityUserId);

        if (!activityUser) {
            throw new Error('Activity not found');
        }

        const response = await axios.delete(
            `${API_URL}/activityUser/delete`,
            {
                headers: {
                    Authorization: `Bearer ${authStore.getToken}`
                },
                data: {
                    activity_id: activityUser.activity_id
                }
            }
        );
        if (response.status === 200) {
            activities.value = activities.value.filter(a => a.id !== activityUserId);
        }
    } catch (err) {
        console.error('Error al desinscribirse:', err);
    }
};
</script>

<style scoped>
.mis-actividades {
  padding: 100px 20px 20px; /* Added top padding to account for the navbar */
  font-family: 'Arial', sans-serif;
  max-width: 1200px;
  margin: 0 auto;
}

.title {
  font-size: 2.5rem;
  color: #006758;
  margin-bottom: 2rem;
  text-align: center;
}

.loading, .error, .no-activities {
  text-align: center;
  font-size: 1.2rem;
  margin-top: 2rem;
  color: #6b7280;
}

.activities-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 2rem;
}

.activity-card {
  background-color: white;
  border-radius: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.activity-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

.activity-image {
  width: 100%;
  height: 200px;
  object-fit: cover;
}

.activity-content {
  padding: 1.5rem;
}

.activity-title {
  font-size: 1.5rem;
  color: #006758;
  margin-bottom: 1rem;
}

.activity-info {
  font-size: 0.9rem;
  color: #4b5563;
  margin-bottom: 0.5rem;
}

.unsubscribe-button {
  display: block;
  width: 100%;
  padding: 0.75rem;
  margin-top: 1rem;
  background-color: #ef4444;
  color: white;
  border: none;
  border-radius: 5px;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.unsubscribe-button:hover {
  background-color: #dc2626;
}

@media (max-width: 768px) {
  .mis-actividades {
    padding-top: 80px;
  }

  .title {
    font-size: 2rem;
  }

  .activities-grid {
    grid-template-columns: 1fr;
  }
}
</style>