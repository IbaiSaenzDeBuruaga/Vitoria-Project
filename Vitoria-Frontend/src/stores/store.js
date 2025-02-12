import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import axios from 'axios';

const API_URL = import.meta.env.VITE_API_AUTH_URL; // Asegúrate de que esta variable esté definida en tu .env

export const useActivityStore = defineStore('activity', () => {
  const activities = ref([]);
  const loading = ref(false);
  const error = ref(null);

  const getActivities = async () => {
    loading.value = true;
    error.value = null;

    try {
      const response = await axios.get(`${API_URL}/activity/all`); // Ajusta el endpoint según tu API
      activities.value = response.data.data; // Ajusta esto según la estructura de respuesta de tu API
    } catch (e) {
      error.value = e;
      console.error("Error fetching activities:", e);
    } finally {
      loading.value = false;
    }
  };

  return { activities, loading, error, getActivities };
});