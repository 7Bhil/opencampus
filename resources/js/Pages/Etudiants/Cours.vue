<template>
  <NavbarUser>
    <div class="min-h-screen bg-gray-50">
      <!-- En-tête -->
      <header class="bg-blue-600 rounded-b-lg py-12 px-4 shadow-lg">
        <div class="max-w-6xl mx-auto text-center">
          <div class="flex items-center justify-center gap-3 mb-4">
            <div class="text-4xl">📚</div>
            <h1 class="text-4xl md:text-5xl font-bold text-white">Tous les cours</h1>
          </div>
          <p class="text-xl text-white opacity-90">Explorez notre bibliothèque de ressources pédagogiques</p>
        </div>
      </header>

      <main class="max-w-7xl mx-auto px-4 py-8">
        <!-- Barre de recherche -->
        <section class="mb-8">
          <div class="max-w-2xl mx-auto">
            <fieldset class="rounded-full p-4 bg-white shadow-lg hover:shadow-xl transition-shadow">
              <div class="flex items-center gap-3 px-2">
                <i class="bi bi-search-heart text-purple-500 text-xl"></i>
                <input
                  type="search"
                  placeholder="Rechercher un cours, une matière, un professeur..."
                  style="border: none;"
                  class="w-full outline-none text-gray-700 placeholder-gray-400"
                  v-model="searchQuery"
                >
              </div>
            </fieldset>
          </div>
        </section>

        <!-- Filtres -->
        <section class="mb-8">
          <div class="flex flex-wrap gap-3 justify-center">
            <button
              class="px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-500 text-white rounded-full font-semibold shadow-lg transition-transform hover:scale-105"
              :class="{ 'ring-2 ring-purple-300': activeFilter === 'all' }"
              @click="setFilter('all')"
            >
              Tous
            </button>

            <fieldset
              v-for="filter in filters"
              :key="filter.id"
              class="border border-gray-200 rounded-full px-6 py-3 bg-white shadow-md cursor-pointer transition-all hover:shadow-lg hover:-translate-y-1"
              @click="setFilter(filter.id)"
            >
              <p class="flex items-center gap-2 m-0" :class="filter.colorClass">
                <i :class="filter.icon"></i>
                {{ filter.label }}
              </p>
            </fieldset>
          </div>
        </section>

        <!-- Grille des cours -->
        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <!-- Cours -->
          <article
            v-for="course in filteredCourses"
            :key="course.id"
            class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300"
          >
            <div class="flex justify-between items-start mb-4">
              <h2 class="text-xl font-bold text-gray-800 pr-4 leading-tight">{{ course.title }}</h2>
              <button
                class="px-4 py-2 rounded-full text-white text-sm font-semibold whitespace-nowrap flex-shrink-0"
                :class="course.typeClass"
              >
                {{ course.type }}
              </button>
            </div>

            <div class="flex flex-wrap gap-4 mb-4 text-gray-600 text-sm">
              <p class="flex items-center gap-2">
                <i class="bi bi-person-video3"></i>
                {{ course.professor }}
              </p>
              <p class="flex items-center gap-2">
                <i class="bi bi-calendar-date"></i>
                {{ course.date }}
              </p>
              <p class="flex items-center gap-2">
                <i class="bi bi-mortarboard-fill"></i>
                {{ course.level }}
              </p>
            </div>

            <p class="text-gray-600 mb-4 leading-relaxed">{{ course.description }}</p>

            <hr class="border-gray-200 my-4">

            <div class="flex justify-end">
              <button
                class="px-6 py-2 rounded-full text-white font-semibold shadow-lg transition-transform hover:scale-105"
                :class="course.buttonClass"
              >
                {{ course.buttonText }}
              </button>
            </div>
          </article>
        </section>
      </main>
    </div>
  </NavbarUser>
</template>

<script setup>
import { ref, computed } from 'vue'
import NavbarUser from '@/Layouts/NavbarEtudiants.vue'

// États réactifs
const searchQuery = ref('')
const activeFilter = ref('all')

// Données des filtres
const filters = ref([
  { id: 'cours', label: 'Cours', icon: 'bi bi-book', colorClass: 'text-blue-500' },
  { id: 'td', label: 'TD', icon: 'bi bi-pencil-square', colorClass: 'text-green-500' },
  { id: 'annales', label: 'Annales', icon: 'bi bi-journal-text', colorClass: 'text-blue-500' },
  { id: 'premium', label: 'Premium', icon: 'bi bi-gem', colorClass: 'text-red-500' },
  { id: 'l1', label: 'L1', icon: 'bi bi-calendar-date', colorClass: 'text-purple-500' },
  { id: 'l2', label: 'L2', icon: 'bi bi-calendar-date', colorClass: 'text-purple-500' },
  { id: 'l3', label: 'L3', icon: 'bi bi-calendar-date', colorClass: 'text-purple-500' },
  { id: 'master', label: 'Master', icon: 'bi bi-calendar-date', colorClass: 'text-purple-500' }
])

// Données des cours
const courses = ref([
  {
    id: 1,
    title: 'Intelligence Artificielle',
    type: 'Cours',
    typeClass: 'bg-blue-500',
    professor: 'Prof. Martin',
    date: '30 Oct 2025',
    level: 'M1',
    description: 'Introduction complète à l\'IA : algorithmes génétiques, réseaux de neurones, apprentissage automatique.',
    buttonText: 'Télécharger',
    buttonClass: 'bg-gradient-to-r from-purple-600 to-blue-500',
    tags: ['cours', 'master']
  },
  {
    id: 2,
    title: 'Programmation Web Full Stack',
    type: 'TD',
    typeClass: 'bg-green-500',
    professor: 'Prof. Dubois',
    date: '29 Oct 2025',
    level: 'L3',
    description: 'Exercices pratiques React, Node.js, MongoDB. Création d\'une application complète de A à Z.',
    buttonText: 'Télécharger',
    buttonClass: 'bg-gradient-to-r from-purple-600 to-blue-500',
    tags: ['td', 'l3']
  },
  {
    id: 3,
    title: 'Annales Analyse 2020-2024',
    type: 'Premium',
    typeClass: 'bg-red-500',
    professor: 'Prof. Laurent',
    date: '28 Oct 2025',
    level: 'L2',
    description: 'Toutes les annales corrigées des 4 dernières années avec explications détaillées et méthodes.',
    buttonText: '4999',
    buttonClass: 'bg-gradient-to-r from-red-500 to-pink-500',
    tags: ['premium', 'annales', 'l2']
  },
  {
    id: 4,
    title: 'Systèmes d\'exploitation',
    type: 'Cours',
    typeClass: 'bg-blue-500',
    professor: 'Prof. Rousseau',
    date: '27 Oct 2025',
    level: 'L3',
    description: 'Processus, threads, gestion mémoire, systèmes de fichiers. Cours complet avec Linux et Windows.',
    buttonText: 'Télécharger',
    buttonClass: 'bg-gradient-to-r from-purple-600 to-blue-500',
    tags: ['cours', 'l3']
  },
  {
    id: 5,
    title: 'Algorithmique et Structures',
    type: 'TD',
    typeClass: 'bg-green-500',
    professor: 'Prof. Martin',
    date: '26 Oct 2025',
    level: 'L2',
    description: '25 Exercices sur les arbres, graphes, tri et recherche avec solutions commentées.',
    buttonText: 'Télécharger',
    buttonClass: 'bg-gradient-to-r from-purple-600 to-blue-500',
    tags: ['td', 'l2']
  },
  {
    id: 6,
    title: 'Sécurité Informatique',
    type: 'Cours',
    typeClass: 'bg-blue-500',
    professor: 'Prof. Bernard',
    date: '25 Oct 2025',
    level: 'M1',
    description: 'Cryptographie, authentification, pare-feu, VPN. Concepts et applications pratiques.',
    buttonText: 'Télécharger',
    buttonClass: 'bg-gradient-to-r from-purple-600 to-blue-500',
    tags: ['cours', 'master']
  }
])

// Filtrage des cours
const filteredCourses = computed(() => {
  let filtered = courses.value

  // Filtre par recherche
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(course =>
      course.title.toLowerCase().includes(query) ||
      course.professor.toLowerCase().includes(query) ||
      course.description.toLowerCase().includes(query)
    )
  }

  // Filtre par catégorie
  if (activeFilter.value !== 'all') {
    filtered = filtered.filter(course =>
      course.tags.includes(activeFilter.value)
    )
  }

  return filtered
})

// Fonction pour changer le filtre
const setFilter = (filter) => {
  activeFilter.value = filter
}
</script>

<style>
/* Import Bootstrap Icons */
@import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css');
</style>
