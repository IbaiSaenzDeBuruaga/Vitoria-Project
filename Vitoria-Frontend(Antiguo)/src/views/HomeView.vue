<template>
  <div class="min-h-screen flex flex-col bg-white">
    <!-- Header -->
    <header class="border-b px-4">
      <div class="flex items-center justify-between py-2">
        <div class="flex items-center gap-2">
          <img
            src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-1L5VsXAhTcKWfqsnra0fltSc84zjdO.png"
            alt="Vitoria Gasteiz"
            class="h-10 w-10"
          />
          <div class="text-lg">
            <span class="font-bold">sede</span> <span>electrónica</span>
          </div>
        </div>
        <button class="text-emerald-800 flex items-center gap-2">
          <user-circle-2-icon class="h-5 w-5" />
          Conectar
        </button>
      </div>
    </header>

    <!-- Filters -->
    <div class="bg-emerald-800 px-4 py-2">
      <div class="max-w-6xl mx-auto flex flex-wrap items-center justify-center gap-6">
        <div class="flex items-center gap-2">
          <label class="text-sm font-medium text-white">Centro cívico</label>
          <select class="rounded-full bg-white px-4 py-1 text-sm">
            <option>Iparralde</option>
          </select>
        </div>

        <div class="flex items-center gap-2">
          <label class="text-sm font-medium text-white">Edad</label>
          <select class="rounded-full bg-white px-4 py-1 text-sm">
            <option>24</option>
          </select>
        </div>

        <div class="flex items-center gap-2">
          <label class="text-sm font-medium text-white">Idioma</label>
          <select class="rounded-full bg-white px-4 py-1 text-sm">
            <option>Castellano</option>
          </select>
        </div>

        <div class="flex items-center gap-2">
          <label class="text-sm font-medium text-white">Horario</label>
          <select class="rounded-full bg-white px-4 py-1 text-sm">
            <option>Mañana</option>
          </select>
        </div>

        <button class="text-white">
          <search-icon class="h-5 w-5" />
        </button>
      </div>
    </div>

    <!-- Activities Grid -->
    <main class="flex-1 px-4 py-8">
      <div class="mx-auto max-w-6xl grid gap-6 md:grid-cols-2">
        <activity-card
          v-for="activity in activities"
          :key="activity.title"
          v-bind="activity"
        />
      </div>
    </main>

    <!-- Pagination -->
    <div class="border-t py-6">
      <div class="flex justify-center gap-2">
        <button
          v-for="page in 5"
          :key="page"
          :class="[
            'h-8 w-8 rounded-full',
            page === 1
              ? 'bg-emerald-800 text-white'
              : 'border border-emerald-800 text-emerald-800'
          ]"
        >
          {{ page }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { UserCircle2Icon, SearchIcon, ChevronRightIcon } from 'lucide-vue-next'
import ActivityCard from '../components/ActivityCard.vue'

const activities = ref([
  {
    title: "Yoga",
    image: "/placeholder.svg?height=200&width=300",
    dates: "29/06/2023 - 29/07/2025",
    schedule: "11:00-11:30",
    days: "Martes y Jueves"
  },
  {
    title: "Club de Lectura",
    image: "/placeholder.svg?height=200&width=300",
    dates: "29/06/2023 - 29/07/2025",
    schedule: "11:00-11:30",
    days: "Martes y Jueves"
  },
  {
    title: "Futbol",
    image: "/placeholder.svg?height=200&width=300",
    dates: "29/06/2023 - 29/07/2025",
    schedule: "11:00-11:30",
    days: "Martes y Jueves"
  },
  {
    title: "Balonmano",
    image: "/placeholder.svg?height=200&width=300",
    dates: "29/06/2023 - 29/07/2025",
    schedule: "11:00-11:30",
    days: "Martes y Jueves"
  },
  {
    title: "Natación",
    image: "/placeholder.svg?height=200&width=300",
    dates: "29/06/2023 - 29/07/2025",
    schedule: "11:00-11:30",
    days: "Martes y Jueves"
  },
  {
    title: "Pilates",
    image: "/placeholder.svg?height=200&width=300",
    dates: "29/06/2023 - 29/07/2025",
    schedule: "11:00-11:30",
    days: "Martes y Jueves"
  },
  {
    title: "Tenis",
    image: "/placeholder.svg?height=200&width=300",
    dates: "29/06/2023 - 29/07/2025",
    schedule: "11:00-11:30",
    days: "Martes y Jueves"
  },
  {
    title: "Baloncesto",
    image: "/placeholder.svg?height=200&width=300",
    dates: "29/06/2023 - 29/07/2025",
    schedule: "11:00-11:30",
    days: "Martes y Jueves"
  }
])
</script>