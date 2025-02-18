// stores/activityFiltroStore.js
import { defineStore } from 'pinia';
import axios from 'axios';
import { useAuthStore } from './authStore'; // Importa useAuthStore

const API_URL = import.meta.env.VITE_API_AUTH_URL;

export const useActivityFiltroStore = defineStore('activityfiltros', {
  state: () => ({
    allActivities: [],
    filtroActivities: [],
    countActividades: 0,
  }),

  actions: {
    async fetchActivitiesAll() {
      try {
        const response = await axios.get(`${API_URL}/activity/all`);
        this.allActivities = response.data.ActividadesCentro;
      } catch (error) {
        console.error('Error al obtener actividades:', error);
      }
    },

    async fetchActivitiesCentro(centro = [], edad = [], idioma = [], horario = []) {
      try {
        const response = await axios.get(`${API_URL}/activity/all`);
        const activitiesCentro = response.data.ActividadesCentro;

        this.filtroActivities = activitiesCentro.filter(activityCentro => {
          return (
            (centro.length === 0 || centro.includes(activityCentro.centro_id)) &&
            (edad.length === 0 || edad.some(e => e >= activityCentro.activity.edad_min && e <= activityCentro.activity.edad_max)) &&
            (idioma.length === 0 || idioma.includes(activityCentro.activity.idioma)) &&
            (horario.length === 0 || horario.includes(activityCentro.activity.horario))
          );
        });
      } catch (error) {
        console.error('ERROR al obtener filtros: ', error);
      }
    },

    getCountActividades(centro_id) {
      try {
        axios.get(`${API_URL}/activity/countActivities/${centro_id}`);
      } catch (error) {
        console.error('ERROR al obtener filtros: ', error);
      }
    },

    async isActivityRegistered(activityId) { // <-- Método para verificar inscripción
      try {
        const response = await axios.get(
          `${API_URL}/activityUser/isRegistered/${activityId}`,
          {
            headers: {
              Authorization: `Bearer ${this.getToken()}` // <-- Llama a la función!
            }
          }
        );
        return response.data.is_registered;
      } catch (error) {
        console.error("Error checking registration:", error);
        return false; // Devuelve false en caso de error
      }
    },

    getToken() { // <-- Getter para el token
      return useAuthStore().getToken;
    }
  }
});