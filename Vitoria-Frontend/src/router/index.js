import { createRouter, createWebHistory } from 'vue-router';
import axios from 'axios';
import HomeView from '../views/HomeView.vue';
import AdminView from '@/views/AdminView.vue';
import { useAuthStore } from '@/stores/authStore';

const urlBack = import.meta.env.VITE_API_AUTH_URL;

const routes = [
  {
    path: '/',
    component: HomeView,
  },
  {
    path: '/admin',
    component: AdminView,
    meta: { serAdmin: true },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

const comprobarToken = async () => {
  const token = localStorage.getItem('authToken');
  if (token) {
    try {
      const response = await axios.get(urlBack + '/auth/validate-token', {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
      console.log('Token válido:', response.data); // Inspecciona la respuesta completa
      const authStore = useAuthStore();
      await authStore.verifyRol();
      return {
        isValid: true,
        rol: response.data.data.rol,
      };
    } catch (error) {
      console.error('Error al validar el token:', error);
      return {
        isValid: false,
        rol: false,
      };
    }
  } else {
    return {
      isValid: false,
      rol: false,
    };
  }
};

// Añade un guard de navegación global
router.beforeEach(async (to, from, next) => {
  const token = localStorage.getItem('authToken');
  console.log('Ruta actual:', to.path); // Depuración: Imprime la ruta actual
  if (to.matched.some(record => record.meta.serAdmin)) {
    console.log('Ruta protegida detectada más el token'+ token); // Depuración: Indica que la ruta está protegida
    if (token) {

      console.log('El token ha sido detectado ');

      try {
        const { isValid, rol } = await comprobarToken();
        // Si la validación es exitosa, permite el acceso
        if (rol !== 'admin') {
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