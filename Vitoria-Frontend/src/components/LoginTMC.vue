// LoginTMC.vue
<template>
    <div class="login-page">
        <div class="login-container">
            <h1>Tramitación online con TMC</h1>

            <div class="info-message">
                <p>
                    Para poder inscribirte en una actividad debes estar conectado.
                </p>
            </div>

            <div class="form-container">
                <h2>Tarjeta Municipal Ciudadana</h2>
                <p class="form-note">
                    Los campos marcados con asterisco son obligatorios.
                </p>

                <form @submit.prevent="handleSubmit" class="login-form">
                    <div class="form-group">
                        <label for="cardCode">Código de tarjeta: *</label>
                        <input type="text" id="cardCode" v-model="formData.cardCode" required />
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
                                <input
                                    type="number"
                                    :id="'ship' + letter"
                                    v-model="formData[letter]"
                                    min="0"
                                    max="9"
                                    required
                                />
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="submit-button">Conectar</button>
                </form>
            </div>

            <!-- Error Modal -->
            <div v-if="showErrorModal" class="modal-overlay">
                <div class="modal-content">
                    <span class="close-modal" @click="closeErrorModal">×</span>
                    <h2>Error de inicio de sesión</h2>
                    <p>{{ errorMessage }}</p>
                    <button @click="closeErrorModal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { EyeIcon, EyeOffIcon } from 'lucide-vue-next';
import { defineEmits } from 'vue';
import axios from 'axios';
import { useAuthStore } from '../stores/authStore';

const emit = defineEmits(['back-to-login', 'login-success']);

const showPassword = ref(false);
const shipLetters = ref([]);
const formData = reactive({
    cardCode: '',
    password: '',
});

const API_URL = import.meta.env.VITE_API_AUTH_URL;

const authStore = useAuthStore();

const togglePassword = () => {
    showPassword.value = !showPassword.value;
};

const getRandomLetters = () => {
    const letters = 'ABCDEFGHIJKLMOP';
    const randomLetters = [];
    for (let i = 0; i < 3; i++) {
        let newLetter;
        do {
            newLetter = letters.charAt(Math.floor(Math.random() * letters.length));
        } while (randomLetters.includes(newLetter));
        randomLetters.push(newLetter);
        formData[newLetter] = '';
    }
    return randomLetters;
};

onMounted(() => {
    shipLetters.value = getRandomLetters();

    Object.keys(formData).forEach((key) => {
        if (!['cardCode', 'password'].includes(key)) {
            delete formData[key];
        }
    });
    shipLetters.value.forEach((letter) => {
        formData[letter] = '';
    });
});

const calculatePosition = (letter) => {
    const letterMap = {
        A: 0, B: 1, C: 2, D: 3, E: 4, F: 5, G: 6, H: 7, I: 8, J: 9, K: 10, L: 11, M: 12, N: 13, O: 14, P: 15,
    };

    const upperLetter = letter.toUpperCase();
    if (letterMap.hasOwnProperty(upperLetter)) {
        return letterMap[upperLetter];
    } else {
        console.warn(`Invalid letter found: ${letter}. Ignoring.`);
        return null;
    }
};

const showErrorModal = ref(false);
const errorMessage = ref('');

const closeErrorModal = () => {
    showErrorModal.value = false;
    errorMessage.value = '';
};

const handleSubmit = async () => {
    const submissionData = {
        numeros_introducidos: '',
        posicion_1: null,
        posicion_2: null,
        posicion_3: null,
        n_tarjeta: formData.cardCode,
        password: formData.password,
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
        submissionData['posicion_' + (i + 1)] = letterPosition;
        submissionData.numeros_introducidos += numberEntered;
    }

    if (!isValid) {
        return;
    }

    try {
        const response = await axios.post(`${API_URL}/auth/login_page`, submissionData);
        console.log('Login API Response:', response);

        if (response.status === 200) {
            const token = response.data.access_token;
            authStore.setToken(token);
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
            console.log('Login successful - emitting login-success, token:', token);
            emit('login-success');
        } else {
          // More specific error handling:
          errorMessage.value = "Usuario o contraseña incorrectos.";  // Default error
          showErrorModal.value = true;
          console.error('Login failed:', response);
        }

    } catch (error) {
      // More specific error messages based on error type:
        if (error.response) {
             if (error.response.status === 401 || error.response.status === 400) {  // Common unauthorized/bad request codes
                errorMessage.value = "Usuario o contraseña incorrectos.";
            } else {
                errorMessage.value = error.response.data.message || 'Error en la respuesta del servidor.';
            }

        } else if (error.request) {
            errorMessage.value = 'No se recibió respuesta del servidor. Inténtalo de nuevo más tarde.';
        } else {
            errorMessage.value = 'Error al configurar la solicitud: ' + error.message;
        }
        showErrorModal.value = true;
        console.error('Login error:', error);
    }
    console.log('Form submitted:', JSON.stringify(submissionData));
};

const backToLogin = () => {
    emit('back-to-login');
};
</script>

<style scoped>
/* Existing styles from before */
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

/* Modal styles */
.modal-overlay {
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

.modal-content {
  background-color: white;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
  max-width: 80%;
  text-align: center;
  position: relative;
}

.close-modal {
  position: absolute;
  top: 1rem;
  right: 1rem;
  font-size: 1.5rem;
  cursor: pointer;
  color: #64748b;
}

.modal-content h2 {
  color: #e53e3e;
  margin-bottom: 1rem;
}

.modal-content p {
  color: #4a5568;
  margin-bottom: 1.5rem;
}

.modal-content button {
  background-color: #006758;
  color: white;
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.modal-content button:hover {
  background-color: #005447;
}
</style>