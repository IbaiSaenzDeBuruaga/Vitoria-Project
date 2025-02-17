import { ref, computed } from 'vue';
import { defineStore } from 'pinia';
import axios from 'axios';

export const useActivitiesStore = defineStore('activities', () => {
/*
  state: () => ({
      incidenciasAlta: -1,
      incidenciasMedia: -1,
      incidenciasBaja: -1,
      incidenciasResueltas: -1,
      mantenimientos: -1,
    }),
    */

    async function fetchActivitiesAll(){
      const response = await axios.get('http://localhost:8000/api/activity/all');

      
    }

});
