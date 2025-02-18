<template>
  <div class="crud-container">
    <div class="row">
      <div class="col-sm-4">
        <button @click="onAdd">Añadir</button>
        <!-- Los botones Editar/Eliminar se manejan en la tabla -->
      </div>
    </div>
    <div class="row">
      <div>
        <slot></slot> <!-- Aquí va la tabla -->
      </div>
    </div>
    <!-- Paginación -->
    <div class="row pagination" v-if="totalPages > 1">
      <button :disabled="currentPage === 1" @click="goToPage(currentPage - 1)">
        Anterior
      </button>
      <button
        v-for="page in totalPages"
        :key="page"
        :class="{ active: currentPage === page }"
        @click="goToPage(page)"
      >
        {{ page }}
      </button>
      <button :disabled="currentPage === totalPages" @click="goToPage(currentPage + 1)">
        Siguiente
      </button>
    </div>
  </div>
</template>

<script setup>
import { defineProps, defineEmits, computed, ref } from 'vue';

const props = defineProps({
  items: { type: Array, required: true },
  perPage: { type: Number, default: 10 },
});

const emit = defineEmits(['add', 'page-changed']); // Solo add y page-changed

const currentPage = ref(1);

const totalPages = computed(() => {
  return Math.ceil(props.items.length / props.perPage);
});

const paginatedItems = computed(() => {
  const start = (currentPage.value - 1) * props.perPage;
  const end = start + props.perPage;
  return props.items.slice(start, end);
});

const onAdd = () => {
  emit('add');
};

const goToPage = (page) => {
    if (page > 0 && page <= totalPages.value){
        currentPage.value = page;
        emit('page-changed', currentPage.value); // Emite el evento
    }
};

defineExpose({ paginatedItems, currentPage, totalPages }); // Expone propiedades

</script>

<style scoped>
/* ... (Estilos del BaseCrud - los que ya tenías, sin cambios) ... */
.crud-container {
  padding: 1rem;
  border: 1px solid #e5e7eb;
  border-radius: 4px;
  background-color: #fff;
  width: 100%;
}

.row {
  margin-bottom: 1rem;
}

.col-sm-4 {
  display: flex;
  gap: 0.5rem;
}

button {
  padding: 0.5rem 1rem;
  border: 1px solid #006758;
  border-radius: 4px;
  background-color: white;
  color: #006758;
  cursor: pointer;
  transition: all 0.2s ease;
}

button:hover {
  background-color: #f3f4f6;
}

button:disabled {
    cursor: not-allowed;
    opacity: 0.5;
}

/* Estilos para la paginación */
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 0.5rem;
  margin-top: 1rem;
}

.pagination button {
  /*  Usa los mismos estilos que los otros botones */
  padding: 0.5rem 1rem;
  border: 1px solid #006758;
  border-radius: 4px;
  background-color: white;
  color: #006758;
  cursor: pointer;
  transition: all 0.2s ease;
}

.pagination button:hover {
    background-color: #f3f4f6;
}

.pagination button.active {
  background-color: #006758;
  color: white;
}
</style>