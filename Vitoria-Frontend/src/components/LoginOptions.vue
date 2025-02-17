<template>
  <div class="login-page">
    <div class="login-container">
      <h1>Tramitación online</h1>
      
      <!-- TMC Section -->
      <section class="login-option">
        <h2>Tarjeta Municipal Ciudadana (TMC)</h2>
        <p>Para trámites online que <strong>NO requieren firma digital</strong>, identifíquese con su Tarjeta Municipal Ciudadana.</p>
        <div class="option-content">
          <div class="tmc-card">
            <img src="../assets/images/tarjeta.png" alt="Tarjeta Municipal" />
          </div>
          <button class="identify-button tmc" @click="goToTMCLogin">
            Identificarse con TMC
          </button>
        </div>
      </section>

      <!-- Additional Information -->
      <section class="additional-info">
        <h2>Información adicional</h2>
        <ul>
          <li><a href="#" class="info-link">Medios de identificación</a></li>
          <li><a href="#" class="info-link">Ayuda y preguntas frecuentes</a></li>
        </ul>
        <p class="company-note">Las empresas deben utilizar un certificado de entidad.</p>
      </section>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { defineEmits } from 'vue'

const router = useRouter()
const emit = defineEmits(['tmc-selected'])

const goToTMCLogin = () => {
  emit('tmc-selected')
}
</script>

<style scoped>
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
}

h1 {
  color: #006758;
  font-size: 2.5rem;
  margin-bottom: 2rem;
  text-align: center;
}

.login-option {
  margin-bottom: 2rem;
}

h2 {
  color: #2d3748;
  font-size: 1.5rem;
  margin-bottom: 1rem;
}

p {
  color: #4a5568;
  margin-bottom: 1.5rem;
  line-height: 1.6;
}

.option-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 2rem;
}

.tmc-card {
  width: 100%;
  max-width: 320px;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.tmc-card img {
  width: 100%;
  height: auto;
  display: block;
}

.identify-button {
  padding: 1rem 2rem;
  border-radius: 4px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  width: 100%;
  max-width: 300px;
}

.identify-button.tmc {
  background-color: #006758;
  color: white;
  border: none;
}

.identify-button.tmc:hover {
  background-color: #005447;
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 103, 88, 0.2);
}

.additional-info {
  margin-top: 3rem;
  padding-top: 2rem;
  border-top: 1px solid #e2e8f0;
}

.info-link {
  color: #006758;
  text-decoration: none;
  display: inline-block;
  margin-bottom: 0.75rem;
  transition: color 0.2s ease;
}

.info-link:hover {
  color: #005447;
  text-decoration: underline;
}

ul {
  list-style: none;
  padding: 0;
  margin-bottom: 1.5rem;
}

.company-note {
  color: #718096;
  font-style: italic;
  font-size: 0.9rem;
  text-align: center;
  margin-top: 1.5rem;
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