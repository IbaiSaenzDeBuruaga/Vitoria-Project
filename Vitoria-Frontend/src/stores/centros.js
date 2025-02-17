import { defineStore } from 'pinia'
import axios, { all } from 'axios'

const API_URL = import.meta.env.VITE_API_AUTH_URL;

export const useCentrosStore = defineStore('centros', {
  state: () => ({
    allCentros: [],
  }),

  actions: {
    async fetchAllCentros(){
        try{
            console.log('llama ');
            const response = await axios.get(`${API_URL}/centros-civicos/all`);

            console.log('CENTROS  PINIA : ', response.data.data);
            this.allCentros = response.data.data;

        } catch(error) {
            console.error('Error al obtener centros:', error);
        }
    }
  }

});