<template>
    <div class="login-page">
      <div class="login-container">
        <h1>Tramitación online</h1>
        
        <!-- BakQ Section -->
        <section class="login-option">
          <h2>BakQ y Certificados digitales</h2>
          <p>Para operar en la Sede Electrónica es necesario identificarse con la BakQ u otro certificado digital.</p>
          <div class="option-content">
            <div class="cards-image">
              <!-- <img src="/placeholder.svg?height=150&width=200" alt="Tarjetas BakQ" /> -->
            </div>
            <button class="identify-button bakq">
              Identificarse con certificado
            </button>
          </div>
        </section>
  
        <!-- TMC Section -->
        <section class="login-option">
          <h2>Tarjeta Municipal Ciudadana (TMC)</h2>
          <p>Para trámites online que <strong>NO requieren firma digital</strong>, es posible identificarse con Tarjeta Municipal Ciudadana.</p>
          <div class="option-content">
            <div class="tmc-card">
              <!-- <img src="/placeholder.svg?height=150&width=200" alt="Tarjeta Municipal" /> -->
            </div>
            <button class="identify-button tmc" @click="goToTMCLogin">
              Identificarse con TMC
            </button>
          </div>
        </section>
  
        <!-- Additional Information -->
        <section class="additional-info">
          <h2>Información para la tramitación online</h2>
          <ul>
            <li><a href="#" class="info-link">Medios de identificación</a></li>
            <li><a href="#" class="info-link">Comprobaciones técnicas para el uso de certificados digitales</a></li>
            <li><a href="#" class="info-link">Ayuda y preguntas frecuentes</a></li>
          </ul>
          <p class="company-note">Las empresas tienen que utilizar un certificado de entidad.</p>
        </section>
      </div>
    </div>
  </template>
  
  <script setup>
  import { useRouter, useRoute } from 'vue-router'
  import { defineEmits } from 'vue'
  
  const router = useRouter()
  const route = useRoute()
  const emit = defineEmits(['tmc-selected'])

  
  const goToTMCLogin = () => {
    emit('tmc-selected')
  }
  </script>
  
  <style scoped>
  .login-page {
    min-height: 100vh;
    background-color: #f8fafc;
    padding: 2rem;
  }
  
  .login-container {
    max-width: 800px;
    margin: 0 auto;
    background-color: white;
    border-radius: 8px;
    padding: 2rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }
  
  h1 {
    color: #1a1a1a;
    font-size: 2rem;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #006758;
  }
  
  .login-option {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background-color: #f8fafc;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
  }
  
  h2 {
    color: #006758;
    font-size: 1.5rem;
    margin-bottom: 1rem;
  }
  
  .option-content {
    display: flex;
    align-items: center;
    gap: 2rem;
    margin-top: 1.5rem;
  }
  
  .cards-image, .tmc-card {
    flex-shrink: 0;
  }
  
  .identify-button {
    padding: 0.75rem 1.5rem;
    border-radius: 4px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
  }
  
  .identify-button.bakq {
    background-color: #1a56db;
    color: white;
    border: none;
  }
  
  .identify-button.bakq:hover {
    background-color: #1e429f;
  }
  
  .identify-button.tmc {
    background-color: #006758;
    color: white;
    border: none;
  }
  
  .identify-button.tmc:hover {
    background-color: #005447;
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
    margin-bottom: 0.5rem;
  }
  
  .info-link:hover {
    text-decoration: underline;
  }
  
  ul {
    list-style: none;
    padding: 0;
    margin-bottom: 1.5rem;
  }
  
  .company-note {
    color: #64748b;
    font-style: italic;
  }
  
  @media (max-width: 640px) {
    .login-page {
      padding: 1rem;
    }
  
    .option-content {
      flex-direction: column;
      text-align: center;
    }
  
    .identify-button {
      width: 100%;
    }
  }
  </style>