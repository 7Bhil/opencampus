<template>
  <NavbarUser>
    <div class="min-h-screen bg-gray-50">
      <!-- Header -->
      <div class="bg-white shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
              <h1 class="text-2xl font-bold text-gray-900">Devoirs à rendre</h1>
              <p class="mt-1 text-base text-gray-600">
                Consultez tous les devoirs assignés par vos professeurs
              </p>
            </div>
            <div class="flex items-center gap-3">
              <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                {{ devoirs.total }} devoirs disponibles
              </span>
            </div>
          </div>
        </div>
      </div>

      <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Message flash -->
        <!-- Message flash - LIGNE 25 -->
<div v-if="$page.props.flash && $page.props.flash.success" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
  <div class="flex items-center">
    <svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
    </svg>
    <span class="text-green-800 font-medium">{{ $page.props.flash.success }}</span>
  </div>
</div>

        <!-- Liste des devoirs -->
        <div v-if="devoirs.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="devoir in devoirs.data" :key="devoir.id"
               class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow">

            <!-- En-tête -->
            <div class="flex justify-between items-start mb-4">
              <h3 class="text-lg font-semibold text-gray-900">{{ devoir.titre }}</h3>
              <span v-if="devoir.deja_soumis"
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                Déjà soumis
              </span>
              <span v-else-if="new Date(devoir.date_limite) < new Date(Date.now() + 3 * 24 * 60 * 60 * 1000)"
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                Urgent
              </span>
            </div>

            <!-- Matière et professeur -->
            <div class="mb-4">
              <p class="text-sm text-gray-600 mb-1">{{ devoir.matiere }}</p>
              <p class="text-sm text-gray-500 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                {{ devoir.professeur?.name || 'Professeur' }}
              </p>
            </div>

            <!-- Description -->
            <p class="text-gray-700 text-sm mb-4 line-clamp-2">
              {{ devoir.description || 'Pas de description' }}
            </p>

            <!-- Infos -->
            <div class="space-y-2 text-sm text-gray-600 mb-6">
              <div class="flex justify-between">
                <span class="flex items-center">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                  </svg>
                  Échéance
                </span>
                <span class="font-medium">{{ formatDate(devoir.date_limite) }}</span>
              </div>
              <div class="flex justify-between">
                <span class="flex items-center">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                  </svg>
                  Soumissions
                </span>
                <span>{{ devoir.soumissions_count }}</span>
              </div>
              <div class="flex justify-between">
                <span class="flex items-center">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  Points
                </span>
                <span class="font-bold text-blue-600">{{ devoir.points }}</span>
              </div>
            </div>

            <!-- Actions -->
            <div class="flex space-x-3">
              <Link
                :href="route('etudiant.devoirs.show', devoir.id)"
                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center font-medium py-2.5 rounded-lg transition"
              >
                Voir le devoir
              </Link>
              <button
                v-if="devoir.fichier_path"
                @click="downloadEnonce(devoir.id)"
                class="px-4 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2.5 rounded-lg transition"
                title="Télécharger l'énoncé"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Aucun devoir -->
        <div v-else class="text-center py-12 bg-white rounded-xl border border-gray-200">
          <div class="max-w-md mx-auto">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun devoir disponible</h3>
            <p class="text-gray-600">Aucun devoir n'a été publié pour le moment.</p>
          </div>
        </div>

        <!-- Pagination -->
        <div v-if="devoirs.data.length > 0" class="mt-8 flex justify-center">
          <nav class="flex items-center space-x-2">
            <Component
              :is="link.url ? 'Link' : 'span'"
              v-for="(link, index) in devoirs.links"
              :key="index"
              :href="link.url || ''"
              :class="[
                'px-4 py-2 rounded-lg transition',
                link.active
                  ? 'bg-blue-600 text-white'
                  : link.url
                    ? 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200'
                    : 'text-gray-400 cursor-not-allowed'
              ]"
              v-html="link.label"
            />
          </nav>
        </div>
      </main>
    </div>
  </NavbarUser>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import NavbarUser from '@/Layouts/NavbarEtudiants.vue'

defineProps({
  devoirs: Object
})

const formatDate = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return date.toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'short',
    year: 'numeric'
  })
}

const downloadEnonce = (devoirId) => {
  window.location.href = `/etudiant/devoirs/${devoirId}/download`
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
