<template>
  <div class="login-page">
      <div class="login-container">
          <h1>Tramitación online con TMC</h1>

          <div class="info-message">
              <p>Con la TMC puedes realizar trámites que <strong>solo requieren identificación</strong>.
                  Para realizar trámites que requieren firma digital hay que utilizar BakQ u otro certificado
                  digital.</p>
              <button @click="backToLogin">Volver</button>
          </div>

          <div class="form-container">
              <h2>Tarjeta Municipal Ciudadana</h2>
              <p class="form-note">Los campos marcados con asterisco son obligatorios.</p>

              <form @submit.prevent="handleSubmit" class="login-form">
                  <div class="form-group">
                      <label for="cardCode">Código de tarjeta: *</label>
                      <input type="text" id="cardCode" v-model="formData.cardCode" required />
                  </div>

                  <div class="form-group">
                      <label for="password">Contraseña: *</label>
                      <div class="password-input">
                          <input :type="showPassword ? 'text' : 'password'" id="password" v-model="formData.password"
                              required />
                          <button type="button" class="toggle-password" @click="togglePassword">
                              <eye-icon v-if="!showPassword" />
                              <eye-off-icon v-else />
                          </button>
                      </div>
                  </div>

                  <div class="ships-game">
                      <h3>Juego de barcos:</h3>
                      <div class="ships-inputs">
                          <div class="ship-input" v-for="(letter, index) in shipLetters" :key="index">
                              <label :for="'ship' + letter">{{ letter }} *</label>
                              <input type="number" :id="'ship' + letter" v-model="formData[letter]" min="0" max="9"
                                  required />
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
import axios from 'axios'

const emit = defineEmits(['back-to-login', 'login-success'])

const showPassword = ref(false)
const shipLetters = ref([]) // Array to hold the random ship letters
const formData = reactive({
  cardCode: '',
  password: '',
})

const API_URL = import.meta.env.VITE_API_AUTH_URL;

const togglePassword = () => {
  showPassword.value = !showPassword.value
}

const getRandomLetters = () => {
  const letters = 'ABCDEFGHIJKLMOP'; // Letters A to P, excluding Ñ
  const randomLetters = [];
  for (let i = 0; i < 3; i++) {
      let newLetter;
      do {
          newLetter = letters.charAt(Math.floor(Math.random() * letters.length));
      } while (randomLetters.includes(newLetter)); // Ensure unique letters
      randomLetters.push(newLetter);
      formData[newLetter] = ''; // Initialize form data for each letter
  }
  return randomLetters;
};

onMounted(() => {
  // Generate random letters when the component is mounted
  shipLetters.value = getRandomLetters();

  // Reset form data for ship letters
  Object.keys(formData).forEach(key => {
      if (!['cardCode', 'password'].includes(key)) {
          delete formData[key];
      }
  });
  shipLetters.value.forEach(letter => {
      formData[letter] = '';
  });
})

const calculatePosition = (letter) => {
  const letterMap = {
      'A': 0, 'B': 1, 'C': 2, 'D': 3, 'E': 4, 'F': 5, 'G': 6, 'H': 7, 'I': 8,
      'J': 9, 'K': 10, 'L': 11, 'M': 12, 'N': 13, 'O': 14, 'P': 15
  };

  const upperLetter = letter.toUpperCase();
  if (letterMap.hasOwnProperty(upperLetter)) {
      return letterMap[upperLetter]; // Return position as a number
  } else {
      console.warn(`Invalid letter found: ${letter}. Ignoring.`);
      return null;
  }
};

const handleSubmit = async () => {
  // Prepare data for the backend
  const submissionData = {
      numeros_introducidos: '', // To store the numbers entered by the user
      posicion_1: null, // To store position of the first letter
      posicion_2: null, // To store position of the second letter
      posicion_3: null, // To store position of the third letter
      n_tarjeta: formData.cardCode, // Card code
      password: formData.password // Password
  };

  let isValid = true;
  for (let i = 0; i < shipLetters.value.length; i++) {
      const letter = shipLetters.value[i];
      const letterPosition = calculatePosition(letter);
      const numberEntered = formData[letter];

      if (letterPosition === null || numberEntered === '') {
          console.error("Invalid data. Can't calculate position.");
          isValid = false;
          break;
      }
      submissionData["posicion_" + (i + 1)] = letterPosition
      submissionData.numeros_introducidos += numberEntered
  }

  if (!isValid) {
      return; // Exit if any validation fails
  }

  try {
      const response = await axios.post(`${API_URL}/auth/login_page`, submissionData);

      if (response.status === 200) {
          console.log('Login successful:', response.data);
          // Store token
          const token = response.data.access_token;
           // localStorage.setItem('token', token); // Save the token in localStorage
          axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

          emit('login-success'); // Notify the parent component
      } else {
          console.error('Login failed:', response);
          // Handle login failure (show error message, etc.)
      }
  } catch (error) {
      console.error('Login error:', error);
      // Handle error (show error message, etc.)
  }

  console.log('Form submitted:', JSON.stringify(submissionData));
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