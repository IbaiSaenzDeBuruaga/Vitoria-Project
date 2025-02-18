import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import axios, { all } from 'axios'

const API_URL = import.meta.env.VITE_API_AUTH_URL;

export const useActivityFiltroStore = defineStore('activityfiltros', {
  state: () => ({
    allActivities: [],
    filtroActivities: [],
    countActividades: 0
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

    async fetchActivitiesCentro(centro = [], edad = [], idioma = [], horario = []){
        try {
            const response = await axios.get(`${API_URL}/activity/all`);

            //activitiesFiltro = response.data.ActividadesCentro;

            console.log('DATA ACTIVITIES: ', response.data);
            const activitiesCentro = response.data.ActividadesCentro;
            console.log('Actvidades Centro: ', activitiesCentro);

            //FILTROS??
            console.log('Centro: ', centro);
            console.log('Edad: ', edad);
            console.log('Idioma: ', idioma);
            console.log('Horario: ', horario);

            // Filtrar actividades basadas en los parámetros proporcionados

            this.filtroActivities = activitiesCentro.filter(activityCentro => {
                return (
                    (centro.length === 0 || centro.includes(activityCentro.cetro_id) ) &&
                    (edad.length === 0 || edad.some(e => e >= activityCentro.activity.edad_min && e <= activityCentro.activity.edad_max) ) &&
                    (idioma.length === 0 || idioma.includes(activityCentro.activity.idioma) ) &&
                    (horario.length === 0 || horario.includes(activityCentro.activity.horario) )
                );
            });

             /*
            this.filtroActivities = activitiesCentro.filter(activity => {
                return (centro === null || activity.centro_id === centro) &&
                    (edad === null || (activity.activity.edad_min <= edad || activity.activity.edad_max >= edad) ) &&
                    (horario === null || activity.activity.horario === horario) &&
                    (idioma === null || activity.activity.idioma === idioma);
            });
            */

            console.log('Actividades Filtradas: ', this.filtroActivities);

        } catch (error) {
            console.log('ERROR al obtener filtros: ', error)            
        }
    },

    getCountActividades(centro_id){
        try {
            console.log('Centro ID PINIA : ', centro_id);
            const response = axios.get(`${API_URL}/activity/countActivities/${centro_id}`);

            console.log('Count PINIA: ', response.data);
            //this.countActividades = response.data.totalActividades;
            
        } catch (error) {
            console.log('ERROR al obtener filtros: ', error)   
            
        }
    },
  }

})
