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

      // Obtiene el token del localStorage
      const token = localStorage.getItem('authToken'); // OJO: No recomendado localStorage

      // Configura las cabeceras de la petición
      const headers = {
        'Content-Type': 'application/json', //  Asegúrate de que el tipo de contenido es correcto
      };

      // Si hay un token, lo añade a la cabecera de autorización
      if (token) {
        headers['Authorization'] = `Bearer ${token}`;
      }
      else{
        console.log
      }

      try {
        const response = await axios({
          method,
          url: `${API_URL}${endpoint}`,
          data,
          headers, // Incluye las cabeceras en la petición
        });
        return response.data;
      } catch (error) {
        this.error = error;
        console.error(`Error en ${method} ${endpoint}:`, error);

        // Manejo especial para errores de autenticación (401 Unauthorized)
        if (error.response && error.response.status === 401) {
          //  Podrías redirigir al login aquí, o limpiar el token del localStorage
          console.warn("Token inválido o expirado.  Redirigiendo al login...");
            localStorage.removeItem('authToken'); //OJO: No recomendado
            // router.push('/login'); //  Si usas Vue Router, descomenta esta línea.

        }

        throw error; // Re-lanza el error
      } finally {
        this.loading = false;
      }
    },

    // --- Centros Cívicos --- (Los métodos usan apiRequest)
    async fetchCentros() {
        try {
            const responseData = await this.apiRequest('get', '/centros-civicos/all');
            this.centros = responseData.data; // Ajusta según tu API
        } catch (error) { /* Los errores se manejan en apiRequest */ }
    },

    async addCentro(centro) {
        try {
            const responseData = await this.apiRequest('post', '/centros-civicos', centro);
            this.centros.push(responseData.data);  // Ajusta
        } catch (error) { /* Los errores se manejan en apiRequest */ }
    },
    async editCentro(centro) {
        try {
          const responseData = await this.apiRequest('put', `/centros-civicos/${centro.id}/update`, centro);
          const index = this.centros.findIndex(c => c.id === centro.id);
          if (index !== -1) {
            this.centros[index] = responseData.data; // Ajusta
          }
        } catch (error) { /* Los errores se manejan en apiRequest */ }
      },
      async deleteCentro(id) {
        try {
          await this.apiRequest('delete', `/centros-civicos/${id}`);
          this.centros = this.centros.filter(c => c.id !== id);
        } catch (error) { /* Los errores se manejan en apiRequest */ }
      },

    // --- Usuarios --- (Los métodos usan apiRequest)
    async fetchUsuarios() {
        try {
            const responseData = await this.apiRequest('get', '/usuario/all');
            this.usuarios = responseData.data; // Ajusta
        } catch (error) { /* Los errores se manejan en apiRequest */ }
    },

    async addUsuario(usuario) {
      try {
        const responseData = await this.apiRequest('post', '/auth/register', usuario);
        this.usuarios.push(responseData); // Ajusta si la API devuelve el usuario creado
      } catch (error) { /* Los errores se manejan en apiRequest */ }
    },

    async editUsuario(usuario) {
        try {
          const responseData = await this.apiRequest('put', `/usuario/${usuario.id}/update`, usuario);
          const index = this.usuarios.findIndex(u => u.id === usuario.id);
          if (index !== -1) {
            this.usuarios[index] = responseData.data; // Ajusta si la API devuelve el usuario actualizado
          }
        } catch (error) { /* Los errores se manejan en apiRequest */ }
      },
    async deleteUsuario(id) {
        try {
            await this.apiRequest('delete', `/usuario/${id}/delete`);
            this.usuarios = this.usuarios.filter(u => u.id !== id);
        } catch (error) { /* Los errores se manejan en apiRequest */ }
    },
      // --- Actividades --- (Los métodos usan apiRequest)// ... (resto del código del store) ...

    async fetchActividades() {
      this.loading = true;
      this.error = null;
      try {
        const responseData = await this.apiRequest('get', '/activity/todos'); // Cambio de URL
        this.actividades = responseData; // Ya no necesitamos .data
      } catch (error) {
        this.error = error; // El error se maneja en apiRequest
        console.error("Error al obtener actividades:", error);
      } finally {
        this.loading = false;
      }
    },
    async addActividad(actividad) {
      this.loading = true;
      this.error = null;
      try {
        const responseData = await this.apiRequest('post', '/activity', actividad); // Corregido
        this.actividades.push(responseData); //  Ajusta según la respuesta
      } catch (error) {
        this.error = error;
        console.error("Error al crear actividad:", error);
      } finally {
        this.loading = false;
      }
    },
    async editActividad(actividad) {
        try{
            const responseData = await this.apiRequest('put', `/activity/${actividad.id}/update`, actividad)
            const index = this.actividades.findIndex(a => a.id === actividad.id);
            if (index !== -1) {
                this.actividades[index] = responseData.data; //  Ajusta si la API no devuelve el objeto actualizado
            }
        }
        catch(error){}
        finally{
            this.loading = false
        }
    },
    async deleteActividad(id) {
      try{
            const responseData = await this.apiRequest('delete', `/activity/${id}/delete`);
            this.actividades = this.actividades.filter(a => a.id !== id);
      }catch(error){}
      finally{
            this.loading = false
      }
    },

  },
  getters: {
    // Puedes agregar getters aquí si necesitas calcular datos derivados del estado
  },
});