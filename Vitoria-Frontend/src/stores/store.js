import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import axios from 'axios';

const API_URL = import.meta.env.VITE_API_AUTH_URL; // Make sure this is defined in .env

export const useActivityStore = defineStore('activity', () => {
    const activities = ref([]);
    const loading = ref(false);
    const error = ref(null);

    const getActivities = async () => {
        loading.value = true;
        error.value = null;

        try {
            const response = await axios.get(`${API_URL}/activity/all`);

            // Assuming your API response structure is like this:
            // { message: "...", ActividadesCentro: [...], Actividades: [...] }
            const actividadesCentro = response.data.ActividadesCentro;
            const actividades = response.data.Actividades;

            // Combine the data and format it for ActivityCard
            activities.value = actividadesCentro.map(centro => {
                const actividad = actividades.find(act => act.id === centro.activity_id);
                return {
                    id: centro.id, // Or actividad.id, depending on which ID you need
                    nombre: actividad?.nombre || 'Nombre no disponible', // Use optional chaining
                    imagen: actividad?.imagen || null,
                    dates: `${centro.fecha_inicio} - ${centro.fecha_fin}`,
                    schedule: `${centro.hora_inicio} - ${centro.hora_fin}`,
                    days: centro.dias
                };
            });

        } catch (e) {
            error.value = e;
            console.error("Error fetching activities:", e);
        } finally {
            loading.value = false;
        }
    };

    return { activities, loading, error, getActivities };
});