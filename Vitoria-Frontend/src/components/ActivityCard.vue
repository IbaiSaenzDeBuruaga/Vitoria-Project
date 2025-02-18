<template>
  <div class="activity-card">
    <div class="activity-image">
      <img :src="imageUrl" :alt="nombre" />
    </div>
    <div class="activity-info">
      <h3>{{ nombre }}</h3>
      <p><calendar-icon /> {{ dates }}</p>
      <p><clock-icon /> {{ schedule }}</p>
      <p><calendar-days-icon /> {{ formattedDays }}</p>
      <button class="register-button" @click="handleRegister">
        Inscribirse
        <chevron-right-icon />
      </button>
    </div>
  </div>
</template>

<script setup>
import { ChevronRightIcon, CalendarIcon, ClockIcon, CalendarDaysIcon } from 'lucide-vue-next'
import { useAuthStore } from '../stores/authStore';
import { computed } from 'vue';

const props = defineProps({
  nombre: String,
  imagen: String,
  dates: String,
  schedule: String,
  days: {  // Updated to expect an array
    type: Array,
    required: true
  },
  id: Number
});

const emit = defineEmits(['register', 'show-login']);
const authStore = useAuthStore();

const handleRegister = () => {
  if (authStore.isLoggedIn) {
    emit('register', props.id);
  } else {
    emit('show-login');
  }
};

const VITE_IMAGE_URL = import.meta.env.VITE_IMAGE_URL;

const imageUrl = computed(() => {
  return props.imagen ? `${VITE_IMAGE_URL}${props.imagen}` : '/placeholder.svg';
});

const formattedDays = computed(() => {  // Formats days from array to string
  return props.days.join(', ');
});

</script>


<style scoped>
.activity-card {
  background-color: white;
  border-radius: 0.5rem;
  overflow: hidden;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.activity-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

.activity-image {
  height: 200px;
  overflow: hidden;
}

.activity-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.activity-info {
  padding: 1rem;
}

.activity-info h3 {
  font-size: 1.25rem;
  font-weight: bold;
  margin-bottom: 0.5rem;
  color: #006758;
}

.activity-info p {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.875rem;
  color: #4a5568;
  margin-bottom: 0.25rem;
}

.register-button {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.25rem;
  width: 100%;
  border-radius: 0.25rem;
  background-color: #006758;
  padding: 0.5rem 1rem;
  font-size: 0.875rem;
  font-weight: 500;
  color: white;
  border: none;
  cursor: pointer;
  transition: background-color 0.3s ease;
  margin-top: 1rem;
}

.register-button:hover {
  background-color: #005647;
}
</style>