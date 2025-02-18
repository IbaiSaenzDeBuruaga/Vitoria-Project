<template>
  <div class="admin-portal">
    <NavbarAdmin />

    <div class="main-container">
      <nav class="admin-nav">
        <button @click="showCentros" class="admin-nav-link" :class="{ active: activeSection === 'centros' }">Gestión de centros</button>
        <button @click="showUsuarios" class="admin-nav-link" :class="{ active: activeSection === 'usuarios' }">Gestión de usuarios</button>
        <button @click="showActividades" class="admin-nav-link" :class="{ active: activeSection === 'actividades' }">Gestión de Actividades</button>
      </nav>

      <CentrosCrud v-if="activeSection === 'centros'" />
      <UsuariosCrud v-if="activeSection === 'usuarios'" />
      <ActividadesCrud v-if="activeSection === 'actividades'" />
    </div>
  </div>
</template>

<script setup>
import NavbarAdmin from '../components/NavBarAdmin.vue';
import CentrosCrud from '../components/CentrosCrud.vue';  // Importa los componentes CRUD
import UsuariosCrud from '../components/UsuariosCrud.vue';
import ActividadesCrud from '../components/ActividadesCrud.vue';
import { ref } from 'vue';

const activeSection = ref('centros'); // Sección activa por defecto

const showCentros = () => {
  activeSection.value = 'centros';
};

const showUsuarios = () => {
  activeSection.value = 'usuarios';
};

const showActividades = () => {
  activeSection.value = 'actividades';
};
</script>

<style scoped>
/* ... (Estilos de AdminView - los que ya tenías, sin cambios importantes) ... */
.admin-portal {
  min-height: 100vh;
  background-color: #f9fafb;
}

.main-container {
  display: flex;
  max-width: 1400px;
  margin: 72px auto 0;
  padding: 24px;
  gap: 2rem;
}

.admin-nav {
  width: 280px;
  flex-shrink: 0;
  padding: 1.5rem;
  border-right: 1px solid #e5e7eb;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.admin-nav-link {
  display: block;
  padding: 0.75rem 1rem;
  border-radius: 4px;
  color: #374151;
  text-decoration: none;
  font-size: 0.875rem;
  transition: all 0.2s ease;
   background: none;
    border: none;
    color: #006758;
    cursor: pointer;
    padding: 8px;
}

.admin-nav-link:hover,
.admin-nav-link.active {
  background-color: #edf2f7;
  color: #006758;
  font-weight: 600;
}
</style>