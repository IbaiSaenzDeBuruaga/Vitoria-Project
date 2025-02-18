<template>
  <div class="mis-actividades">
    <h1 class="title">Mis Actividades</h1>

    <div v-if="loading" class="loading">Cargando actividades...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <div v-else-if="activities.length === 0" class="no-activities">No tienes actividades inscritas.</div>

    <div v-else class="activities-grid">
      <div v-for="activityUser in activities" :key="activityUser.id" class="activity-card">
        <div class="activity-image">
          <img v-if="activityUser.activity_centro.activity.imagen" :src="imageUrl(activityUser.activity_centro.activity.imagen)" :alt="activityUser.activity_centro.activity.nombre" />
        </div>
        <div class="activity-info">
          <h3 class="activity-title">{{ activityUser.activity_centro.activity.nombre }}</h3>
          <p><calendar-icon /> {{ activityUser.activity_centro.fecha_inicio }} - {{ activityUser.activity_centro.fecha_fin }}</p>
          <p><clock-icon /> {{ activityUser.activity_centro.hora_inicio }} - {{ activityUser.activity_centro.hora_fin }}</p>
          <p><calendar-days-icon /> {{ formatDays(activityUser.activity_centro.dias) }}</p>
          <button @click="unsubscribe(activityUser.id)" class="unsubscribe-button">
            Desinscribirse
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { useAuthStore } from '../stores/authStore';
import { CalendarIcon, ClockIcon, CalendarDaysIcon } from 'lucide-vue-next';

const activities = ref([]);
const loading = ref(false);
const error = ref(null);
const authStore = useAuthStore();
const API_URL = import.meta.env.VITE_API_AUTH_URL;
const IMAGE_BASE_URL = import.meta.env.VITE_IMAGE_URL;

const imageUrl = (imagePath) => {
  const cleanImagePath = imagePath.replace(/^storage\//, '');
  return `${IMAGE_BASE_URL}${cleanImagePath}`;
};

// FINALLY the correct formatDays function!
const formatDays = (daysArray) => {
  if (!daysArray) {
    return ""; // Handle null or undefined
  }

  // Check if it's an array (or a reactive Proxy of an array)
  if (Array.isArray(daysArray)) {
    return daysArray.join(', ');  // Just join it!
  }
   // Check if it's a string (in case the data changes in the future)
  if (typeof daysArray === "string") {
        // 1. Check if it *looks like* it's ALREADY comma-separated
        if (daysArray.includes(',') && !daysArray.trim().startsWith('[')) {
            return daysArray; // If so, assume it's already formatted and return it
        }
        // 2. If not, try to parse it as JSON (it might be a stringified array)
        try {
          const parsed = JSON.parse(daysArray);
          if (Array.isArray(parsed)) {
            return parsed.join(', '); // If it's an array, join with commas and spaces
          }
        } catch (error) {
          // If JSON.parse fails, it's not a valid JSON array string.
          //  We've already handled the comma-separated case, so at this
          //  point, it's likely some other unexpected format.
          return daysString; // Return original string in this unexpected case.
        }
      }

  // If it's neither an array nor a string, return an empty string (or handle as needed)
  return "";
};

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
/* (Estilos -  Ajustados para alinear con el ejemplo proporcionado) */
.mis-actividades {
  padding: 100px 20px 20px;
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
  border-radius: 0.5rem; /* Corregido: border-radius consistente */
  overflow: hidden;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.activity-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

.activity-image {
  height: 200px;
  overflow: hidden; /* Asegura que la imagen no sobresalga */
}

.activity-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.activity-info {
  padding: 1rem; /* Corregido padding */
}

.activity-info h3 {
    /*Añadido para que coincida con el h3 de la card de ejemplo*/
  font-size: 1.25rem;
  font-weight: bold;
  margin-bottom: 0.5rem;
  color: #006758;
}
.activity-info p {
     /*Añadido para que coincida con el p de la card de ejemplo*/
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.875rem;
  color: #4a5568;
  margin-bottom: 0.25rem;
}

.unsubscribe-button {
  display: flex; /* Usar flexbox para alinear */
  align-items: center;/* Centrar verticalmente */
  justify-content: center; /* Centrar horizontalmente */
  gap: 0.25rem;  /* Espacio entre texto e icono */
  width: 100%;
  border-radius: 0.25rem; /* Corregido: border-radius consistente */
  background-color: #ef4444;
  padding: 0.5rem 1rem; /* Corregido padding */
  font-size: 0.875rem; /* Corregido tamaño de fuente */
  font-weight: 500;
  color: white;
  border: none;
  cursor: pointer;
  transition: background-color 0.3s ease;
  margin-top: 1rem;
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