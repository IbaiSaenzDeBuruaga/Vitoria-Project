<template>
  <BaseCrud ref="baseCrudRef" :items="adminStore.centros" :perPage="5" @add="addCentro" @page-changed="handlePageChange">
    <div class="table-responsive" v-if="baseCrudRef">
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="centro in baseCrudRef.paginatedItems" :key="centro.id">
            <td>{{ centro.id }}</td>
            <td>{{ centro.nombre }}</td>
            <td>
              <button class="action-button" @click="editCentro(centro)">Editar</button>
              <button class="action-button" @click="deleteCentro(centro)">Eliminar</button>
            </td>
          </tr>
        </tbody>
      </table>
        <div v-if="adminStore.loading" class="loading">Cargando...</div>
      <div v-if="adminStore.error" class="error">
        Error: {{ adminStore.error.message }}
      </div>
    </div>
    <div v-else>Cargando...</div>

    <div v-if="showAddModal" class="modal-overlay">
      <div class="modal">
        <h2>Añadir Centro</h2>
        <form @submit.prevent="submitAddCentro">
          <label for="nombre">Nombre:</label>
          <input type="text" id="nombre" v-model="newCentro.nombre" required>
          <label for="direccion">Dirección:</label>
          <input type="text" id="direccion" v-model="newCentro.direccion" required>

          <button type="submit" class="modal-button">Guardar</button>
          <button type="button" class="modal-button" @click="closeAddModal">Cancelar</button>
        </form>
      </div>
    </div>

    <div v-if="showEditModal" class="modal-overlay">
      <div class="modal">
        <h2>Editar Centro</h2>
        <form @submit.prevent="submitEditCentro">
          <label for="nombre">Nombre:</label>
          <input type="text" id="nombre" v-model="editCentroData.nombre" required>
          <label for="direccion">Dirección:</label>
          <input type="text" id="direccion" v-model="editCentroData.direccion" required>

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
import { useAdminStore } from '@/stores/adminStore';

const adminStore = useAdminStore();
const baseCrudRef = ref(null); // <-- Referencia a BaseCrud

const showAddModal = ref(false);
const showEditModal = ref(false);

const newCentro = ref({
  nombre: '',
  direccion:""
});

const editCentroData = ref({
    id: null,
    nombre: '',
    direccion: ''
});

onMounted(async () => {
  await adminStore.fetchCentros();
});

const addCentro = () => {
    showAddModal.value = true;
};

const submitAddCentro = async () => {
  try {
    await adminStore.addCentro(newCentro.value);
    closeAddModal();
      newCentro.value = { nombre: '' };
  } catch (error) {
    // Puedes mostrar el error aquí si quieres, pero el store ya lo guarda
  }
};

const closeAddModal = () => {
 showAddModal.value = false;
   newCentro.value = { nombre: '' };
};

const editCentro = (centro) => {
    editCentroData.value = { ...centro }; // Copia los datos del centro
    showEditModal.value = true; // Muestra el modal
};

const submitEditCentro = async () => {
    try {
        await adminStore.editCentro(editCentroData.value);
        closeEditModal(); // Cierra el modal
        editCentroData.value = { id: null, nombre: '' }; //Limpia
    } catch (error) {
        console.log(error)
    }
};

const closeEditModal = () => {
    showEditModal.value = false;
    editCentroData.value = { id: null, nombre: '' };
};

const deleteCentro = (centro) => {
  if (confirm(`¿Estás seguro de que quieres eliminar el centro "${centro.nombre}"?`)) {
    adminStore.deleteCentro(centro.id)
    .catch(error => {
          // Maneja el error aquí si es necesario
    });
  }
};

const handlePageChange = (newPage) => {
  console.log('Página cambiada a:', newPage); //  Opcional:  Haz algo si necesitas recargar datos
};

</script>

<style scoped>
/* ... (Estilos de CentrosCrud - los que ya tenías, con .action-button) ... */
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
    .modal input[type="password"]
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