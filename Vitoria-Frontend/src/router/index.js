import { createRouter, createWebHistory } from 'vue-router';

import axios from 'axios';
import HomeView from '../views/HomeView.vue';
import AdminView from '@/views/AdminView.vue';
//import comprobarToken from './authManager';

const urlBack = import.meta.env.VITE_API_AUTH_URL;

const routes = [
  {
    path: '/',
    component: HomeView,
  },
  {
    path: '/admin',
    component: AdminView,
    meta: { serAdmin: true},
  }
  
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

const rutasAdmin = [
  '/admin',
];

const rutasGenericas = [
  '/home'
];


const comprobarToken = async () => {
  const token = localStorage.getItem('token');
  if (token) {
    try {
      const response = await axios.get(urlBack + '/auth/validate-token', {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
      console.log('Token válido:', response.data.data.rol);
      return{
        isValid: true,
        rol: response.data.data.rol,
      } 
    }
    catch (error) {
      console.error('Error al validar el token:', error);
      return {
        isValid: false,
        rol: false,
      };
    }
  }
  else {
    return{
      isValid: false,
      rol: false,
    };
  }
ç
};


// Añade un guard de navegación global
router.beforeEach(async (to, from, next) => {
  const token = localStorage.getItem('token');
  if (to.matched.some(record => record.meta.serAdmin)) {
    if (token) {
      try {
        const { isValid, rol } = await comprobarToken();
        // Si la validación es exitosa, permite el acceso
        const permisoAdmin = rutasAdmin.includes(to.path);

        if (permisoAdmin && rol !== 'admin') {
          alert('No tienes permisos para acceder a esta página');
          next('/');
        } else {
          next();
        }

      } catch (error) {
        console.error('Error al validar el token:', error);
        // Si hay un error en la validación, redirige al login
        next('/');
      }
    } else {
      // Si no hay token, redirige al login
      next('/');
    }
  } else {
    // Si la ruta no requiere autenticación, permite el acceso
    next();
  }
});

export default router;