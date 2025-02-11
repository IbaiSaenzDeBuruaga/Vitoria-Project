<template>
    <div class="login-page">
      <div class="login-container">
        <h1>Tramitación online con TMC</h1>
        
        <div class="info-message">
          <p>Con la TMC puedes realizar trámites que <strong>solo requieren identificación</strong>. 
             Para realizar trámites que requieren firma digital hay que utilizar BakQ u otro certificado digital.</p>
             <button @click="backToLogin">Volver</button>
        </div>
  
        <div class="form-container">
          <h2>Tarjeta Municipal Ciudadana</h2>
          <p class="form-note">Los campos marcados con asterisco son obligatorios.</p>
  
          <form @submit.prevent="handleSubmit" class="login-form">
            <div class="form-group">
              <label for="cardCode">Código de tarjeta: *</label>
              <input 
                type="text" 
                id="cardCode" 
                v-model="formData.cardCode" 
                required
              />
            </div>
  
            <div class="form-group">
              <label for="password">Contraseña: *</label>
              <div class="password-input">
                <input 
                  :type="showPassword ? 'text' : 'password'"
                  id="password" 
                  v-model="formData.password" 
                  required
                />
                <button 
                  type="button" 
                  class="toggle-password"
                  @click="togglePassword"
                >
                  <eye-icon v-if="!showPassword" />
                  <eye-off-icon v-else />
                </button>
              </div>
            </div>
  
            <div class="ships-game">
              <h3>Juego de barcos:</h3>
              <div class="ships-inputs">
                <div class="ship-input">
                  <label :for="'ship' + shipLetters[0]">{{ shipLetters[0] }} *</label>
                  <input 
                    type="text" 
                    :id="'ship' + shipLetters[0]"
                    v-model="formData[shipLetters[0]]" 
                    maxlength="1"
                    required
                  />
                </div>
                <div class="ship-input">
                  <label :for="'ship' + shipLetters[1]">{{ shipLetters[1] }} *</label>
                  <input 
                    type="text" 
                    :id="'ship' + shipLetters[1]"
                    v-model="formData[shipLetters[1]]" 
                    maxlength="1"
                    required
                  />
                </div>
                <div class="ship-input">
                  <label :for="'ship' + shipLetters[2]">{{ shipLetters[2] }} *</label>
                  <input 
                    type="text" 
                    :id="'ship' + shipLetters[2]"
                    v-model="formData[shipLetters[2]]" 
                    maxlength="1"
                    required
                  />
                </div>
              </div>
            </div>
  
            <button type="submit" class="submit-button">
              Conectar
            </button>
          </form>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, reactive, onMounted } from 'vue'
  import { EyeIcon, EyeOffIcon } from 'lucide-vue-next'
  import { defineEmits } from 'vue'
  
  const emit = defineEmits(['back-to-login', 'login-success'])

  const showPassword = ref(false)
  const shipLetters = ref(['A', 'C', 'J']) // Initial letters
  const formData = reactive({
    cardCode: '',
    password: '',
    A: '',
    C: '',
    J: ''
  })
  
  const togglePassword = () => {
    showPassword.value = !showPassword.value
  }

  const getRandomLetters = () => {
    const letters = 'ABCDEFGHIJKLMOP'; // Letters A to P, excluding Ñ
    const randomLetters = [];
    for (let i = 0; i < 3; i++) {
      randomLetters.push(letters.charAt(Math.floor(Math.random() * letters.length)));
    }
    return randomLetters;
  };
  
  onMounted(() => {
    // Generate random letters when the component is mounted
    shipLetters.value = getRandomLetters();

    // Reset form data for new letters
    formData.A = '';
    formData.C = '';
    formData.J = '';
    // Dynamically add the properties to the reactive object
    shipLetters.value.forEach(letter => {
        formData[letter] = '';
    });

  });
  
  const handleSubmit = () => {
    const submissionData = {
      codigo: formData.cardCode,
      password: formData.password
    };

    // Add ship letters and values to the submission data
    shipLetters.value.forEach(letter => {
      submissionData[letter] = formData[letter];
    });

    console.log('Form submitted:', JSON.stringify(submissionData));
    // Here you can send the data to your backend

    //Simulate succesfull login
    emit('login-success')
  }

  const backToLogin = () => {
    emit('back-to-login')
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
  
  .info-message {
    background-color: #f0fdf4;
    border-left: 4px solid #006758;
    padding: 1rem;
    margin-bottom: 2rem;
  }
  
  .form-container {
    background-color: #f8fafc;
    padding: 2rem;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
  }
  
  h2 {
    color: #006758;
    font-size: 1.5rem;
    margin-bottom: 1rem;
  }
  
  .form-note {
    color: #64748b;
    margin-bottom: 2rem;
    font-size: 0.875rem;
  }
  
  .login-form {
    max-width: 500px;
  }
  
  .form-group {
    margin-bottom: 1.5rem;
  }
  
  label {
    display: block;
    margin-bottom: 0.5rem;
    color: #1a1a1a;
    font-weight: 500;
  }
  
  input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #e2e8f0;
    border-radius: 4px;
    font-size: 1rem;
  }
  
  input:focus {
    outline: none;
    border-color: #006758;
    box-shadow: 0 0 0 2px rgba(0, 103, 88, 0.1);
  }
  
  .password-input {
    position: relative;
  }
  
  .toggle-password {
    position: absolute;
    right: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #64748b;
    cursor: pointer;
    padding: 0.25rem;
  }
  
  .ships-game {
    margin-bottom: 2rem;
  }
  
  .ships-game h3 {
    color: #1a1a1a;
    font-size: 1rem;
    margin-bottom: 1rem;
  }
  
  .ships-inputs {
    display: flex;
    gap: 1rem;
  }
  
  .ship-input {
    width: 60px;
  }
  
  .ship-input input {
    text-align: center;
    font-size: 1.25rem;
  }
  
  .submit-button {
    background-color: #006758;
    color: white;
    padding: 0.75rem 2rem;
    border: none;
    border-radius: 4px;
    font-weight: 500;
    cursor: pointer;
    width: 100%;
    transition: background-color 0.2s ease;
  }
  
  .submit-button:hover {
    background-color: #005447;
  }
  
  @media (max-width: 640px) {
    .login-page {
      padding: 1rem;
    }
  
    .form-container {
      padding: 1rem;
    }
  
    .ships-inputs {
      flex-direction: column;
      gap: 0.5rem;
    }
  
    .ship-input {
      width: 100%;
    }
  }
  </style>