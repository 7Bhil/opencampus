<!-- resources/js/Pages/Etudiants/Cours.vue -->
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
        <!-- Message si aucun cours -->
        <div v-if="cours.data.length === 0" class="text-center py-12">
          <div class="text-6xl mb-4">📭</div>
          <h3 class="text-2xl font-bold text-gray-700 mb-2">Aucun cours disponible</h3>
          <p class="text-gray-500">Les professeurs n'ont pas encore publié de cours.</p>
        </div>

        <!-- Grille des cours -->
        <section v-if="cours.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <!-- Cours -->
          <article
            v-for="course in cours.data"
            :key="course.id"
            class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300"
          >
            <div class="flex justify-between items-start mb-4">
              <h2 class="text-xl font-bold text-gray-800 pr-4 leading-tight">{{ course.titre }}</h2>
              <span
                class="px-4 py-2 rounded-full text-white text-sm font-semibold whitespace-nowrap flex-shrink-0"
                :class="course.est_payant ? 'bg-red-500' : 'bg-green-500'"
              >
                {{ course.est_payant ? 'Premium' : 'Gratuit' }}
              </span>
            </div>

            <div class="flex flex-wrap gap-4 mb-4 text-gray-600 text-sm">
              <p class="flex items-center gap-2">
                <i class="bi bi-person-video3"></i>
                {{ course.professeur?.name || 'Professeur inconnu' }}
              </p>
              <p class="flex items-center gap-2">
                <i class="bi bi-calendar-date"></i>
                {{ new Date(course.created_at).toLocaleDateString('fr-FR') }}
              </p>
              <p class="flex items-center gap-2">
                <i class="bi bi-mortarboard-fill"></i>
                {{ course.matiere }}
              </p>
            </div>

            <p class="text-gray-600 mb-4 leading-relaxed">{{ course.description }}</p>

            <hr class="border-gray-200 my-4">

            <div class="flex justify-between items-center">
              <div class="text-gray-500 text-sm">
                <i class="bi bi-eye"></i> {{ course.nombre_vues }} vues
              </div>
              <Link
                :href="route('etudiant.cours.show', course.id)"
                class="px-6 py-2 rounded-full text-white font-semibold shadow-lg transition-transform hover:scale-105"
                :class="course.est_payant ? 'bg-gradient-to-r from-red-500 to-pink-500' : 'bg-gradient-to-r from-purple-600 to-blue-500'"
              >
                {{ course.est_payant ? `Acheter (${course.prix} €)` : 'Consulter' }}
              </Link>
            </div>
          </article>
        </section>

        <!-- Pagination -->
        <div v-if="cours.data.length > 0" class="mt-8 flex justify-center">
          <Component
            :is="link.url ? 'Link' : 'span'"
            v-for="link in cours.links"
            :key="link.label"
            :href="link.url"
            :class="[
              'px-4 py-2 mx-1 rounded-lg',
              link.active ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100',
              !link.url ? 'text-gray-400 cursor-not-allowed' : ''
            ]"
            v-html="link.label"
          />
        </div>
      </main>
    </div>
  </NavbarUser>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import NavbarUser from '@/Layouts/NavbarEtudiants.vue'

defineProps({
  cours: Object
})
</script>

<style>
@import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css');
</style>
