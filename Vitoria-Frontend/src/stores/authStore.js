// stores/authStore.js
import { defineStore } from 'pinia';
import axios from 'axios';

const API_URL = import.meta.env.VITE_API_AUTH_URL;

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: localStorage.getItem('authToken') || null,
    rol: null
  }),
  getters: {
    isLoggedIn: (state) => !!state.token,
    getToken: (state) => state.token,
    getRol: (state) => state.rol
  },
  actions: {
    setToken(token) {
      this.token = token;
      localStorage.setItem('authToken', token);
    },
    async verifyRol(){

      try {

        console.log("El token de this-token es "+this.token);
        if(this.token != null){
          const response = await axios.get(API_URL+'/auth/myRol',{
            headers:{
              'Authorization': 'Bearer '+this.token
            }
          });
  
          console.log("La respuesta de response en verifyRol es "+response.data);
          
          this.rol =  response.data;
        }
        else{
          return null;
        }
        
      } 
      catch (error) {
        return false;
      }

    },
    clearToken() {
      this.token = null;
      localStorage.removeItem('authToken');
    },
  },
});