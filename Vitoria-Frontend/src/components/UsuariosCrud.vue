<template>
  <BaseCrud ref="baseCrudRef" :items="adminStore.usuarios" :perPage="5" @add="addUsuario" @page-changed="handlePageChange">
    <div class="table-responsive" v-if="baseCrudRef">
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
           <tr v-for="usuario in baseCrudRef.paginatedItems" :key="usuario.id">
            <td>{{ usuario.id }}</td>
            <td>{{ usuario.name }}</td>
            <td>{{ usuario.email }}</td>
            <td>{{ usuario.rol }}</td>
            <td>
              <button class="action-button" @click="editUsuario(usuario)">Editar</button>
              <button class="action-button" @click="deleteUsuario(usuario)">Eliminar</button>
            </td>
          </tr>
        </tbody>
      </table>
       <!-- Indicador de carga -->
        <div v-if="adminStore.loading" class="loading">Cargando...</div>

      <!-- Mensaje de error -->
      <div v-if="adminStore.error" class="error">
        Error: {{ adminStore.error.message }}
      </div>
    </div>
    <div v-else>Cargando...</div>

    <div v-if="showAddModal" class="modal-overlay">
        <div class="modal">
          <h2>Añadir Usuario</h2>
          <form @submit.prevent="submitAddUsuario">
            <label for="name">Nombre:</label>
            <input type="text" id="name" v-model="newUsuario.name" required>
            <label for="primer_apellido">Primer Apellido</label>
            <input type="text" id="primer_apellido" v-model="newUsuario.primer_apellido" required>
            <label for="segundo_apellido">Segundo Apellido</label>
            <input type="text" id="segundo_apellido" v-model="newUsuario.segundo_apellido" required>
            <label for="email">Email:</label>
            <input type="email" id="email" v-model="newUsuario.email" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" v-model="newUsuario.password" required>

            <label for="rol">Rol:</label>
            <select id="rol" v-model="newUsuario.rol" required>
              <option value="usuario">Usuario</option>
              <option value="administrador">Administrador</option>
            </select>

            <label for="n_tarjeta">Número de Tarjeta (opcional):</label>
            <input type="text" id="n_tarjeta" v-model="newUsuario.n_tarjeta">

            <label for="n_barcos">Número de Barcos (opcional):</label>
            <input type="text" id="n_barcos" v-model="newUsuario.n_barcos">

            <button type="submit" class="modal-button">Guardar</button>
            <button type="button" class="modal-button" @click="closeAddModal">Cancelar</button>
          </form>
        </div>
    </div>

      <div v-if="showEditModal" class="modal-overlay">
        <div class="modal">
          <h2>Editar Usuario</h2>
          <form @submit.prevent="submitEditUsuario">
            <label for="edit-name">Nombre:</label>
            <input type="text" id="edit-name" v-model="editUsuarioData.name" required>

            <label for="edit-primer_apellido">Primer Apellido:</label>
            <input type="text" id="edit-primer_apellido" v-model="editUsuarioData.primer_apellido">

            <label for="edit-segundo_apellido">Segundo Apellido:</label>
            <input type="text" id="edit-segundo_apellido" v-model="editUsuarioData.segundo_apellido">

            <label for="edit-email">Email:</label>
            <input type="email" id="edit-email" v-model="editUsuarioData.email" required>

            <label for="edit-rol">Rol:</label>
            <select id="edit-rol" v-model="editUsuarioData.rol" required>
              <option value="usuario">Usuario</option>
              <option value="administrador">Administrador</option>
            </select>

              <label for="edit-n_tarjeta">Número de Tarjeta (opcional):</label>
              <input type="text" id="edit-n_tarjeta" v-model="editUsuarioData.n_tarjeta">

              <label for="edit-n_barcos">Número de Barcos (opcional):</label>
              <input type="text" id="edit-n_barcos" v-model="editUsuarioData.n_barcos">

            <button type="submit" class="modal-button">Guardar</button>
            <button type="button" class="modal-button" @click="closeEditModal">Cancelar</button>
          </form>
        </div>
      </div>
  </BaseCrud>
</template>

<script setup>
import BaseCrud from './BaseCrud.vue';
import { ref, onMounted, reactive } from 'vue';
import { useAdminStore } from '@/stores/adminStore'; // Importa el store
import axios from 'axios';

const adminStore = useAdminStore(); // Usa el store
//Estados para mostrar modales
const showAddModal = ref(false);
const showEditModal = ref(false);

// Nuevo usuario
const newUsuario = ref({
    name: '',
    primer_apellido: '',
    segundo_apellido: '',
    email: '',
    password: '',
    rol: 'usuario', // Valor por defecto
    n_tarjeta: '',
    n_barcos: ''
});

const editUsuarioData = ref({
    id: null,
    name: '',
    primer_apellido: '',
    segundo_apellido: '',
    email: '',
    rol: '',
    n_tarjeta: '',
    n_barcos: ''
});
// Referencia a la instancia de BaseCrud
const baseCrudRef = ref(null);
onMounted(async () => {
  await adminStore.fetchUsuarios();
});

const addUsuario = () => {
  showAddModal.value = true;
};

const submitAddUsuario = async () => {
    try {
        await adminStore.addUsuario(newUsuario.value);
        closeAddModal();
        newUsuario.value = {
            name: '',
            primer_apellido: '',
            segundo_apellido: '',
            email: '',
            password: '',
            rol: 'usuario',
            n_tarjeta: '',
            n_barcos: ''
        };

    } catch (error) {
        console.error("Error al añadir usuario:", error);
    }
};

const closeAddModal = () => {
    showAddModal.value = false;
    newUsuario.value = {
            name: '',
            primer_apellido: '',
            segundo_apellido: '',
            email: '',
            password: '',
            rol: 'usuario',
            n_tarjeta: '',
            n_barcos: ''
        };
};

const editUsuario = (usuario) => {
    // Copia los datos del usuario al objeto editUsuarioData. Usamos structuredClone para una copia profunda.
    editUsuarioData.value = structuredClone(usuario);
    showEditModal.value = true; // Muestra el modal de edición
};

const submitEditUsuario = async () => {
    try {
        await adminStore.editUsuario(editUsuarioData.value);
        closeEditModal();
        editUsuarioData.value = {
            id: null,
            name: '',
            primer_apellido: '',
            segundo_apellido: '',
            email: '',
            rol: '',
            n_tarjeta: '',
            n_barcos: ''
        };
    } catch (error) {
        console.error("Error al editar usuario:", error);
    }
};

const closeEditModal = () => {
    showEditModal.value = false;
    editUsuarioData.value = {
        id: null,
        name: '',
        primer_apellido: '',
        segundo_apellido: '',
        email: '',
        rol: '',
        n_tarjeta: '',
        n_barcos: ''
    };
};
const deleteUsuario = (usuario) => {
 if (confirm(`¿Estás seguro de que quieres eliminar al usuario "${usuario.name}"?`)) {
    adminStore.deleteUsuario(usuario.id)
    .catch(error => {
          // Maneja el error aquí si es necesario
    });
  }
};
const handlePageChange = (newPage) => {
    console.log('Página cambiada a:', newPage);
};
</script>

<style scoped>
/* Estilos específicos para la tabla (sin cambios) */
.table-responsive {
  overflow-x: auto;
}
.table {
  width: 100%;
  border-collapse: collapse;
}
.table th, .table td {
  padding: 0.75rem;
  border-top: 1px solid #dee2e6;
  text-align: left;
}

.table th {
    background-color: #f8f9fa;
    color: #495057;
}

 /* Estilos para el modal */
 .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000; /* Asegúrate de que el modal esté por encima de otros elementos */
  }

  .modal {
    background-color: white;
    padding: 2rem;
    border-radius: 4px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    min-width: 300px;
  }

  .modal h2 {
    margin-top: 0;
    margin-bottom: 1rem;
    color: #006758;
  }

  .modal label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: bold;
  }

  .modal input[type="text"],
  .modal input[type="email"],
    .modal input[type="password"],
    .modal select
    {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ced4da;
    border-radius: 4px;
    margin-bottom: 1rem;
    box-sizing: border-box; /* Incluye el padding y el border en el ancho total */
  }

  .modal button {
        padding: 0.5rem 1rem;
        border: 1px solid #006758;
        border-radius: 4px;
        background-color: white;
        color: #006758;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-right: 0.5em;
    }
    .modal button:hover {
        background-color: #f3f4f6;
    }


  .modal button[type="button"]{
      background-color: #888888;
  }
  .loading, .error {
    margin-top: 1rem;
    padding: 0.5rem;
    text-align: center;
  }

.loading {
    color: #006758;
    font-weight: bold;
}

.error {
    color: #d32f2f; /* Un rojo oscuro para errores */
    font-weight: bold;
}
/* Estilos para los botones de acción en la tabla */
.action-button {
  padding: 0.25rem 0.5rem; /* Más pequeños que los botones principales */
  font-size: 0.75rem;    /* Texto más pequeño */
  margin-right: 0.25rem; /* Menos espacio entre botones */
  /* El resto de los estilos son iguales a los botones normales */
    border: 1px solid #006758;
    border-radius: 4px;
    background-color: white;
    color: #006758;
    cursor: pointer;
    transition: all 0.2s ease;
}

.action-button:hover {
  background-color: #f3f4f6; /* Mismo hover que los botones normales */
}
</style>