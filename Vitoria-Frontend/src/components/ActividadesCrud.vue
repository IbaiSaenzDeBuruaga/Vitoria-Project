<template>
  <BaseCrud ref="baseCrudRef" :items="adminStore.actividades" :perPage="5" @add="addActivity" @page-changed="handlePageChange">
    <div class="table-responsive" v-if="baseCrudRef">
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Edad Mínima</th>
            <th>Edad Máxima</th>
            <th>Horario</th>
            <th>Idioma</th>
            <th>Plazas</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="activity in baseCrudRef.paginatedItems" :key="activity.id">
            <td>{{ activity.id }}</td>
            <td>{{ activity.nombre }}</td>
            <td>{{ activity.edad_min }}</td>
            <td>{{ activity.edad_max }}</td>
            <td>{{ activity.horario }}</td>
            <td>{{ activity.idioma }}</td>
            <td>{{ activity.plazas }}</td>
            <td>
              <button class="action-button" @click="editActivity(activity)">Editar</button>
              <button class="action-button" @click="deleteActivity(activity)">Eliminar</button>
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
    <div v-else>Cargando datos...</div>

    <!-- Modal para Añadir Actividad -->
    <div v-if="showAddModal" class="modal-overlay">
        <div class="modal">
          <h2>Añadir Actividad</h2>
          <form @submit.prevent="submitAddActivity">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" v-model="newActivity.nombre" required>
            <label for="edad_min">Edad mínima:</label>
            <input type="number" id="edad_min" v-model="newActivity.edad_min" required>
            <label for="edad_max">Edad máxima:</label>
            <input type="number" id="edad_max" v-model="newActivity.edad_max" required>
            <label for="horario">Horario:</label>
            <select id="horario" v-model="newActivity.horario">
              <option value="matutino">Matutino</option>
              <option value="vespertino">Vespertino</option>
            </select>
            <label for="idioma">Idioma:</label>
            <select id="idioma" v-model="newActivity.idioma">
              <option value="es">Español</option>
              <option value="eu">Euskera</option>
              <option value="en">Inglés</option>
            </select>
            <label for="plazas">Plazas:</label>
            <input type="number" id="plazas" v-model="newActivity.plazas">
            <button type="submit" class="modal-button">Guardar</button>
            <button type="button" class="modal-button" @click="closeAddModal">Cancelar</button>
          </form>
        </div>
    </div>

    <!-- Modal para Editar Actividad -->
       <div v-if="showEditModal" class="modal-overlay">
        <div class="modal">
          <h2>Editar Actividad</h2>
          <form @submit.prevent="submitEditActivity">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" v-model="editActivityData.nombre" required>

            <label for="edad_min">Edad mínima:</label>
            <input type="number" id="edad_min" v-model="editActivityData.edad_min" required>

            <label for="edad_max">Edad máxima:</label>
            <input type="number" id="edad_max" v-model="editActivityData.edad_max" required>

            <label for="horario">Horario:</label>
            <select id="horario" v-model="editActivityData.horario">
              <option value="matutino">Matutino</option>
              <option value="vespertino">Vespertino</option>
            </select>

            <label for="idioma">Idioma:</label>
            <select id="idioma" v-model="editActivityData.idioma">
              <option value="es">Español</option>
              <option value="eu">Euskera</option>
              <option value="en">Inglés</option>
            </select>

            <label for="plazas">Plazas:</label>
            <input type="number" id="plazas" v-model="editActivityData.plazas">

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

const adminStore = useAdminStore(); // Usa el store

//Estados para mostrar modales
const showAddModal = ref(false);
const showEditModal = ref(false);

// Nuevo actividad
const newActivity = ref({
    nombre: '',
    edad_min: null,
    edad_max: null,
    horario: null,
    idioma: null,
    plazas: null
});
const editActivityData = ref({
    id: null,
    nombre: '',
    edad_min: null,
    edad_max: null,
    horario: null,
    idioma: null,
    plazas: null
});

// Referencia a la instancia de BaseCrud  --MUY IMPORTANTE--
const baseCrudRef = ref(null); // Inicializa como null

onMounted(async () => {
  await adminStore.fetchActividades();
});

const addActivity = () => {
    showAddModal.value = true;
};
const submitAddActivity = async () => {
  try {
    await adminStore.addActividad(newActivity.value);
    closeAddModal(); // Cierra el modal
    newActivity.value = {
        nombre: '',
        edad_min: null,
        edad_max: null,
        horario: null,
        idioma: null,
        plazas: null
    };
  } catch (error) {
    // Manejar errores
  }
};

const closeAddModal = () => {
  showAddModal.value = false;
  newActivity.value = {
        nombre: '',
        edad_min: null,
        edad_max: null,
        horario: null,
        idioma: null,
        plazas: null
    };
};

const editActivity = (activity) => {
    editActivityData.value = { ...activity }; // Copia los datos del centro
    showEditModal.value = true; // Muestra el modal
};

const submitEditActivity = async () => {
    try {
        await adminStore.editActividad(editActivityData.value);
        closeEditModal(); // Cierra el modal
        editActivityData.value = {   
            id: null,
            nombre: '',
            edad_min: null,
            edad_max: null,
            horario: null,
            idioma: null,
            plazas: null 
        }; //Limpia
        location.reload();
    } catch (error) {
        console.log(error)
    }
};

const closeEditModal = () => {
    showEditModal.value = false;
    editActivityData.value = {   
        id: null,
        nombre: '',
        edad_min: null,
        edad_max: null,
        horario: null,
        idioma: null,
        plazas: null 
    }; //Limpia
};
const deleteActivity = (activity) => {
  if (confirm(`¿Estás seguro de que quieres eliminar la actividad "${activity.nombre}"?`)) {
    adminStore.deleteActividad(activity.id)
    .catch(error => {
          // Manejar errores aquí si es necesario.  Por ejemplo, mostrar una notificación.
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
    .modal input[type="number"],
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