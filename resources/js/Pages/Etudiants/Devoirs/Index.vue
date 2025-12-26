<!-- resources/js/Pages/Etudiants/Devoirs/Index.vue -->
<template>
  <NavbarUser>
    <div class="min-h-screen bg-gray-50 py-8">
      <div class="max-w-7xl mx-auto px-4">
        <!-- En-tête -->
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-gray-800 mb-2">📝 Devoirs à rendre</h1>
          <p class="text-gray-600">Consultez les devoirs assignés par vos professeurs</p>
        </div>

        <!-- Liste des devoirs -->
        <div v-if="devoirs.data.length === 0" class="text-center py-12 bg-white rounded-lg shadow">
          <div class="text-6xl mb-4">📭</div>
          <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucun devoir à rendre</h3>
          <p class="text-gray-500">Vous n'avez pas de devoirs en attente pour le moment.</p>
        </div>

        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="devoir in devoirs.data" :key="devoir.id" class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
            <div class="p-6">
              <!-- En-tête du devoir -->
              <div class="flex justify-between items-start mb-4">
                <h3 class="text-lg font-semibold text-gray-800">{{ devoir.titre }}</h3>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                  {{ devoir.matiere }}
                </span>
              </div>

              <!-- Description -->
              <p class="text-gray-600 text-sm mb-4">{{ devoir.description }}</p>

              <!-- Infos -->
              <div class="space-y-2 text-sm text-gray-500 mb-6">
                <div class="flex items-center gap-2">
                  <i class="bi bi-person"></i>
                  <span>{{ devoir.professeur?.name }}</span>
                </div>
                <div class="flex items-center gap-2">
                  <i class="bi bi-calendar"></i>
                  <span>Limite : {{ new Date(devoir.date_limite).toLocaleDateString('fr-FR') }}</span>
                </div>
                <div class="flex items-center gap-2">
                  <i class="bi bi-star"></i>
                  <span>{{ devoir.points }} points</span>
                </div>
              </div>

              <!-- Actions -->
              <div class="flex justify-between items-center">
                <Link :href="route('etudiant.devoirs.show', devoir.id)"
                      class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                  Voir le devoir
                </Link>

                <!-- Badge urgence -->
                <span v-if="new Date(devoir.date_limite) < new Date(Date.now() + 3 * 24 * 60 * 60 * 1000)"
                      class="px-3 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">
                  Urgent
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Pagination -->
        <div v-if="devoirs.data.length > 0" class="mt-8">
          <Pagination :links="devoirs.links" />
        </div>
      </div>
    </div>
  </NavbarUser>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import NavbarUser from '@/Layouts/NavbarEtudiants.vue'
import Pagination from '@/Components/Pagination.vue'

defineProps({
  devoirs: Object
})
</script>
