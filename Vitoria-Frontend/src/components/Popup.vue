<template>
    <transition name="fade">
      <div v-if="isOpen" class="popup-overlay">
        <div class="popup-container">
          <div class="popup-header">
            <h2>{{ title }}</h2>
            <button @click="close" class="close-button">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
              </svg>
            </button>
          </div>
          <div class="popup-content">
            <slot></slot>
          </div>
          <div class="popup-footer">
            <slot name="footer">
              <button @click="close" class="cancel-button">Cancelar</button>
              <button @click="confirm" class="confirm-button">Confirmar</button>
            </slot>
          </div>
        </div>
      </div>
    </transition>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  
  const props = defineProps({
    title: {
      type: String,
      default: 'Popup'
    }
  });
  
  const isOpen = ref(false);
  
  const emit = defineEmits(['close', 'confirm']);
  
  const open = () => {
    isOpen.value = true;
  };
  
  const close = () => {
    isOpen.value = false;
    emit('close');
  };
  
  const confirm = () => {
    emit('confirm');
    close();
  };
  
  defineExpose({ open, close });
  </script>
  
  <style scoped>
  .popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
  }
  
  .popup-container {
    background-color: white;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }
  
  .popup-header {
    background-color: #006758;
    color: white;
    padding: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
  }
  
  .popup-header h2 {
    margin: 0;
    font-size: 1.25rem;
  }
  
  .close-button {
    background: none;
    border: none;
    color: white;
    cursor: pointer;
    padding: 0;
  }
  
  .popup-content {
    padding: 1rem;
  }
  
  .popup-footer {
    padding: 1rem;
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
    border-top: 1px solid #e2e8f0;
  }
  
  .cancel-button, .confirm-button {
    padding: 0.5rem 1rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
  }
  
  .cancel-button {
    background-color: #f3f4f6;
    color: #4b5563;
    border: 1px solid #d1d5db;
  }
  
  .confirm-button {
    background-color: #006758;
    color: white;
    border: none;
  }
  
  .fade-enter-active,
  .fade-leave-active {
    transition: opacity 0.3s ease;
  }
  
  .fade-enter-from,
  .fade-leave-to {
    opacity: 0;
  }
  </style>