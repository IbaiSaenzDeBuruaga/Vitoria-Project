<template>
    <footer class="footer">
      <!-- Top Bar -->
      <div class="top-bar">
        <div class="container">
          <nav class="top-nav">
            <a href="#" class="top-link">
              <message-circle-icon class="icon" />
              Dudas, quejas y sugerencias
            </a>
            <a href="#" class="top-link">
              <calendar-x-icon class="icon" />
              Días inhábiles
            </a>
            <a href="#" class="top-link">
              <clock-icon class="icon" />
              Fecha y hora oficial
            </a>
            <a href="#" class="top-link">
              <key-icon class="icon" />
              Medios de identificación
            </a>
          </nav>
        </div>
      </div>
  
      <!-- Main Footer -->
      <div class="main-footer">
        <div class="container">
          <div class="footer-content">
            <!-- Logo Section -->
            <div class="footer-logo">
              <img
                src=""
                alt="Ayuntamiento de Vitoria-Gasteiz"
                class="logo"
              />
              <div class="address">
                <p class="address-line">Plaza España 1, 01001 Vitoria-Gasteiz</p>
                <p class="phone">010 (+34 945 16 11 00)</p>
                <a href="mailto:informacion@vitoria-gasteiz.org" class="email">
                  informacion@vitoria-gasteiz.org
                </a>
              </div>
            </div>
  
            <!-- Navigation -->
            <nav class="footer-nav">
              <a href="#" class="footer-link">Mapa Web</a>
              <a href="#" class="footer-link">Accesibilidad</a>
              <a href="#" class="footer-link">Política de privacidad</a>
              <a href="#" class="footer-link">Aviso Legal</a>
            </nav>
          </div>
        </div>
      </div>
  
      <!-- Institutional Logos -->
      <div class="institutional">
        <div class="container">
          <div class="logos">
            <div class="logo-item">
              <img
                src=""
                alt="Financiado por la Unión Europea"
                class="eu-logo"
              />
              <span>NextGenerationEU</span>
            </div>
            <img
              src=""
              alt="Gobierno de España"
              class="gov-logo"
            />
            <img
              src=""
              alt="Plan de Recuperación"
              class="plan-logo"
            />
            <img
              src=""
              alt="Ayuntamiento"
              class="city-logo"
            />
          </div>
          <p class="copyright">
            © {{ currentYear }} Ayuntamiento de Vitoria-Gasteiz / Vitoria-Gasteizko Udala
          </p>
        </div>
      </div>
    </footer>
  </template>
  
  <script setup>
  import {
    MessageCircleIcon,
    CalendarXIcon,
    ClockIcon,
    KeyIcon
  } from 'lucide-vue-next'
  
  const currentYear = new Date().getFullYear()
  </script>
  
  <style scoped>
  .footer {
    margin-top: auto;
  }
  
  .top-bar {
    background-color: #006758;
    padding: 0.75rem 0;
  }
  
  .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
  }
  
  .top-nav {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    gap: 1rem;
  }
  
  .top-link {
    color: white;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    transition: opacity 0.2s ease;
  }
  
  .top-link:hover {
    opacity: 0.8;
  }
  
  .icon {
    width: 1rem;
    height: 1rem;
  }
  
  .main-footer {
    background-color: #1a1a1a;
    padding: 3rem 0;
    color: white;
  }
  
  .footer-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 2rem;
  }
  
  .footer-logo {
    display: flex;
    align-items: center;
    gap: 2rem;
  }
  
  .logo {
    width: 80px;
    height: auto;
  }
  
  .address {
    font-size: 0.875rem;
    color: #a3a3a3;
  }
  
  .address-line {
    margin-bottom: 0.5rem;
  }
  
  .phone {
    margin-bottom: 0.5rem;
  }
  
  .email {
    color: white;
    text-decoration: none;
    transition: color 0.2s ease;
  }
  
  .email:hover {
    color: #006758;
  }
  
  .footer-nav {
    display: flex;
    gap: 2rem;
  }
  
  .footer-link {
    color: white;
    text-decoration: none;
    font-size: 0.875rem;
    transition: color 0.2s ease;
  }
  
  .footer-link:hover {
    color: #006758;
  }
  
  .institutional {
    background-color: #242424;
    padding: 2rem 0;
  }
  
  .logos {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 2rem;
    margin-bottom: 2rem;
  }
  
  .logo-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    color: white;
    font-size: 0.75rem;
  }
  
  .eu-logo,
  .gov-logo,
  .plan-logo,
  .city-logo {
    height: 50px;
    width: auto;
    object-fit: contain;
  }
  
  .copyright {
    text-align: center;
    color: #a3a3a3;
    font-size: 0.875rem;
  }
  
  @media (max-width: 768px) {
    .top-nav {
      flex-direction: column;
      align-items: flex-start;
      padding: 0 1rem;
    }
  
    .footer-content {
      flex-direction: column;
      align-items: center;
      text-align: center;
    }
  
    .footer-logo {
      flex-direction: column;
      align-items: center;
      gap: 1rem;
    }
  
    .footer-nav {
      flex-direction: column;
      align-items: center;
      gap: 1rem;
    }
  
    .logos {
      justify-content: center;
    }
  }
  </style>