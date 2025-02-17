// store.js
import { ref } from 'vue'
import { defineStore } from 'pinia'
import axios from 'axios';

const API_URL = import.meta.env.VITE_API_AUTH_URL; // Asegúrate de que esta URL esté definida en tu .env

export const useActivityStore = defineStore('activity', () => {
    const activities = ref([]);
    const loading = ref(false);
    const error = ref(null);

    const getActivities = async () => {
        loading.value = true;
        error.value = null;

        try {
            const response = await axios.get(`${API_URL}/activity/all`);

            // Asumiendo que la respuesta es un objeto con una clave 'ActividadesCentro' que contiene un array de actividades
            const actividadesCentro = response.data.ActividadesCentro;

            // Formatea la data para ActivityCard
            activities.value = actividadesCentro.map(centro => {
                const actividad = centro.activity;

                return {
                    id: centro.id,
                    nombre: actividad?.nombre || 'Nombre no disponible',
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