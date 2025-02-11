<template>
    <div class="overflow-hidden rounded-lg bg-emerald-800 p-4">
      <div class="flex">
        <div class="relative h-[140px] w-[200px] flex-shrink-0 overflow-hidden rounded-lg mr-4">
          <img
            :src="image || '/placeholder.svg'"
            :alt="title"
            class="object-cover w-full h-full"
          />
        </div>
        <div class="flex flex-1 flex-col justify-between text-white">
          <div>
            <h3 class="text-2xl font-bold">{{ title }}</h3>
            <p class="mt-2 text-sm">Fecha: {{ dates }}</p>
            <p class="text-sm">Horario: {{ schedule }}</p>
            <p class="text-sm">Días: {{ days }}</p>
          </div>
          <button class="flex items-center gap-1 rounded-full bg-white px-4 py-2 text-sm font-medium text-emerald-800 self-end">
            Inscribirse
            <chevron-right-icon class="h-4 w-4" />
          </button>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ChevronRightIcon } from 'lucide-vue-next'
  
  defineProps({
    title: String,
    image: String,
    dates: String,
    schedule: String,
    days: String
  })
  </script>