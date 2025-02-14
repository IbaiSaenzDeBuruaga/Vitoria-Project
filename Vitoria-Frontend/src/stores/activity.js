import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import axios, { all } from 'axios'

const API_URL = import.meta.env.VITE_API_AUTH_URL;

export const useActivityFiltroStore = defineStore('activityfiltros', {
  state: () => ({
    allActivities: [],
    filtroActivities: []
  }),

  actions: {
    async fetchActivitiesAll(){
        try{
            const response = await axios.get('http://localhost:8000/api/activity/all');

            console.log('DATA: ', response.data);
            this.allActivities = response.data;

        } catch(error) {
            console.error('Error al obtener actividades:', error);
        }
        
    },

    async fetchActivitiesCentro(centro = null, edad = null, horario = null, idioma = null){
        try {
            const response = await axios.get(`${API_URL}/activity/activitiescentro`);

            //activitiesFiltro = response.data.ActividadesCentro;

            //console.log('DATA: ', response.data.ActividadesCentro);
            const activitiesCentro = response.data.ActividadesCentro;
            console.log('Actvidades Centro: ', activitiesCentro);

            //FILTROS??
            centro = 10;
            //edad = 22;
            //horario = 'matutino';
            idioma = 'eu';

            // Filtrar actividades basadas en los parámetros proporcionados
            this.filtroActivities = activitiesCentro.filter(activity => {
                return (centro === null || activity.centro_id === centro) &&
                    (edad === null || (activity.activity.edad_min <= edad || activity.activity.edad_max >= edad) ) &&
                    (horario === null || activity.activity.horario === horario) &&
                    (idioma === null || activity.activity.idioma === idioma);
            });

            console.log('Actividades Filtradas: ', this.filtroActivities);

        } catch (error) {
            console.log('ERROR al obtener filtros: ', error)            
        }
    }
  }
})
