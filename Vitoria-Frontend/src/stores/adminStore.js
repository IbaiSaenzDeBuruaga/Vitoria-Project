import { defineStore } from 'pinia';
import axios from 'axios';

const API_URL = import.meta.env.VITE_API_AUTH_URL;

export const useAdminStore = defineStore('admin', {
  state: () => ({
    centros: [],
    usuarios: [],
    actividades: [],
    loading: false,
    error: null,
  }),
  actions: {
    // --- Función auxiliar para las peticiones ---
    async apiRequest(method, endpoint, data = null) {
      this.loading = true;
      this.error = null;
      try {
        const token = localStorage.getItem('authToken'); // Obtiene el token
        const headers = {
          'Content-Type': 'application/json',
        };
        if (token) {
          headers['Authorization'] = `Bearer ${token}`;
        }

        console.log("Los datos son los siguientes "+ data);

        const response = await axios({
          method,
          url: `${API_URL}${endpoint}`,
          data,
          headers, // Incluye las cabeceras
        });
        console.log('La respuesta de '+ JSON.stringify(endpoint)+ " es "+JSON.stringify(response));
        return response;
      } catch (error) {
        this.error = error;
        console.error(`Error en ${method} ${endpoint}:`, error);
         if (error.response && error.response.status === 401) {
          console.warn("Token inválido o expirado.  Redirigiendo al login...");
            localStorage.removeItem('authToken');
            localStorage.removeItem('rol');
        }
        throw error; // Re-lanza el error
      } finally {
        this.loading = false;
      }
    },

    // --- Centros Cívicos ---
    async fetchCentros() {
      try {
        const responseData = await this.apiRequest('get', '/centros-civicos/all');
        console.log("Los centros all son "+ responseData.data.data);
        this.centros = responseData.data.data;
      } catch (error) { /* Los errores se manejan en apiRequest */ }
    },
    async addCentro(centro) {
      try {

        

        const responseData = await this.apiRequest('post', '/centros-civicos', centro);
        this.centros.push(responseData.data.data);
      } catch (error) { /* Los errores se manejan en apiRequest */ }
    },
    async editCentro(centro) {
      try {
        const responseData = await this.apiRequest('put', `/centros-civicos/${centro.id}/update`, centro);
        const index = this.centros.findIndex(c => c.id === centro.id);
        if (index !== -1) {
          this.centros[index] = responseData.data.data;
        }
      } catch (error) { /* Los errores se manejan en apiRequest */ }
    },
    async deleteCentro(id) {
      try {
        await this.apiRequest('delete', `/centros-civicos/${id}`);
        this.centros = this.centros.filter(c => c.id !== id);
      } catch (error) { /* Los errores se manejan en apiRequest */ }
    },

    // --- Usuarios ---
    async fetchUsuarios() {
      try {
        const responseData = await this.apiRequest('get', '/usuario/all');
        this.usuarios = responseData.data.data;
        console.log("Usuarios son "+ this.usuarios);
      } catch (error) { /* Los errores se manejan en apiRequest */ }
    },
    async addUsuario(usuario) {
      try {
        const responseData = await this.apiRequest('post', '/auth/register', usuario);
        this.usuarios.push(responseData);
      } catch (error) { /* Los errores se manejan en apiRequest */ }
    },
    async editUsuario(usuario) {
      try {
        const responseData = await this.apiRequest('put', `/usuario/${usuario.id}/update`, usuario);
        const index = this.usuarios.findIndex(u => u.id === usuario.id);
        if (index !== -1) {
          this.usuarios[index] = responseData.data.data;
        }
      } catch (error) { /* Los errores se manejan en apiRequest */ }
    },
    async deleteUsuario(id) {
      try {
        await this.apiRequest('delete', `/usuario/${id}/delete`);
        this.usuarios = this.usuarios.filter(u => u.id !== id);
      } catch (error) { /* Los errores se manejan en apiRequest */ }
    },

    // --- Actividades ---
    async fetchActividades() {
      try {
        const responseData = await this.apiRequest('get', '/activity/todos');
        console.log("El response es "+ responseData.data);
        this.actividades = responseData.data; // Ya no se necesita .data
       
        console.log("Actividades son "+ this.actividades);
      } catch (error) { /* Los errores se manejan en apiRequest */ }
    },
    async addActividad(actividad) {
      try {
        const responseData = await this.apiRequest('post', '/activity', actividad);
        this.actividades.push(responseData.data); // Ajusta si la API devuelve la actividad
      } catch (error) { /* Los errores se manejan en apiRequest */ }
    },
    async editActividad(actividad) {
      try {
        const responseData = await this.apiRequest('put', `/activity/${actividad.id}/update`, actividad);
        const index = this.actividades.findIndex(a => a.id === actividad.id);
        if (index !== -1) {
          this.actividades[index] = responseData.data; // Ajusta si la API devuelve la actividad
        }
      } catch (error) { /* Los errores se manejan en apiRequest */ }
    },
    async deleteActividad(id) {
      try {
        await this.apiRequest('delete', `/activity/${id}/delete`);
        this.actividades = this.actividades.filter(a => a.id !== id);
      } catch (error) { /* Los errores se manejan en apiRequest */ }
    },
  },
  getters: {}, // Puedes añadir getters si los necesitas
});