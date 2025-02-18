//loginOptions.vue
<template>
  <div class="login-page" v-show="!showTMC">
    <div class="login-container">
        <h1>Inscripción en Actividades</h1>
        <section class="message-section">
          <p>Para inscribirte en una actividad, necesitas estar conectado.</p>
          <p>Por favor, inicia sesión para continuar.</p>
        </section>
        <section class="login-option">
          <button class="login-button" @click="showLoginComponent">
            Iniciar Sesión
          </button>
        </section>
    </div>
  </div>
    <LoginTMC v-if="showTMC" @close-tmc="showTMC = false" />
</template>

<script setup>
import { ref } from 'vue';
import LoginTMC from './LoginTMC.vue';

const showTMC = ref(false);

const showLoginComponent = () => {
  showTMC.value = true;
};
</script>

<style scoped>
/* (Same styles as before - no changes needed) */
.login-page {
  min-height: 100vh;
  background-color: #f0f4f8;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem;
}

.login-container {
  max-width: 600px;
  width: 100%;
  background-color: white;
  border-radius: 12px;
  padding: 3rem;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  text-align: center; /* Center all content */
}

h1 {
  color: #006758;
  font-size: 2.5rem;
  margin-bottom: 2rem;
}

.message-section {
  margin-bottom: 2rem;
}

.message-section p {
  color: #4a5568;
  line-height: 1.6;
  margin-bottom: 0.75rem;
}


.login-button {
  padding: 1rem 2rem;
  border-radius: 4px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  width: 100%;
  max-width: 300px;
  background-color: #006758;
  color: white;
  border: none;
}

.login-button:hover {
  background-color: #005447;
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 103, 88, 0.2);
}

@media (max-width: 640px) {
  .login-page {
    padding: 1rem;
  }

  .login-container {
    padding: 2rem;
  }

  h1 {
    font-size: 2rem;
  }
}
</style>