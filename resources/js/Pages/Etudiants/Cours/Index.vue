<template>
  <NavbarUser>
    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
      <!-- Hero Section -->
      <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-12">
        <div class="max-w-7xl mx-auto px-4">
          <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">📚 Bibliothèque de Cours</h1>
            <p class="text-xl text-blue-100 max-w-3xl mx-auto">
              Explorez notre collection de ressources pédagogiques. Des cours gratuits et premium pour tous les niveaux.
            </p>
          </div>
        </div>
      </div>

      <!-- Filtres et Stats -->
      <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="flex flex-col lg:flex-row gap-6">
          <!-- Filtres -->
          <div class="lg:w-1/4">
            <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-6">
              <h3 class="text-lg font-bold text-gray-800 mb-4">🔍 Filtres</h3>

              <!-- Recherche -->
              <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                <div class="relative">
                  <input
                    v-model="search"
                    type="text"
                    placeholder="Titre, matière, description..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    @keyup.enter="applyFilters"
                  >
                  <div class="absolute left-3 top-2.5 text-gray-400">
                    🔍
                  </div>
                </div>
              </div>

              <!-- Catégorie -->
              <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
                <select
                  v-model="categorie"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                  <option value="">Toutes les catégories</option>
                  <option v-for="cat in statistiques.categories" :value="cat">{{ cat }}</option>
                </select>
              </div>

              <!-- Matière -->
              <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Matière</label>
                <select
                  v-model="matiere"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                  <option value="">Toutes les matières</option>
                  <option v-for="mat in statistiques.matieres" :value="mat">{{ mat }}</option>
                </select>
              </div>

              <!-- Type -->
              <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                <div class="space-y-2">
                  <label class="flex items-center">
                    <input v-model="type" type="radio" value="" class="mr-2">
                    <span>Tous les cours</span>
                  </label>
                  <label class="flex items-center">
                    <input v-model="type" type="radio" value="gratuit" class="mr-2">
                    <span>Gratuits seulement</span>
                  </label>
                  <label class="flex items-center">
                    <input v-model="type" type="radio" value="payant" class="mr-2">
                    <span>Premium seulement</span>
                  </label>
                </div>
              </div>

              <!-- Boutons -->
              <div class="space-y-3">
                <button
                  @click="applyFilters"
                  class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition"
                >
                  Appliquer les filtres
                </button>
                <button
                  @click="resetFilters"
                  class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2.5 rounded-lg transition"
                >
                  Réinitialiser
                </button>
              </div>
            </div>

            <!-- Stats -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mt-6">
              <h3 class="text-lg font-bold text-gray-800 mb-4">📊 Statistiques</h3>
              <div class="space-y-4">
                <div class="flex justify-between items-center">
                  <span class="text-gray-600">Total des cours</span>
                  <span class="font-bold text-blue-600">{{ statistiques.total_cours }}</span>
                </div>
                <div class="flex justify-between items-center">
                  <span class="text-gray-600">Cours gratuits</span>
                  <span class="font-bold text-green-600">{{ statistiques.cours_gratuits }}</span>
                </div>
                <div class="flex justify-between items-center">
                  <span class="text-gray-600">Cours premium</span>
                  <span class="font-bold text-purple-600">{{ statistiques.cours_payants }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Liste des cours -->
          <div class="lg:w-3/4">
            <!-- En-tête -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
              <div>
                <h2 class="text-2xl font-bold text-gray-800">Cours disponibles</h2>
                <p class="text-gray-600">{{ cours.total }} cours trouvés</p>
              </div>
              <div class="flex items-center space-x-4 mt-4 sm:mt-0">
                <select
                  v-model="tri"
                  class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                  <option value="recent">Plus récents</option>
                  <option value="populaire">Plus populaires</option>
                  <option value="vues">Plus vus</option>
                </select>
              </div>
            </div>

            <!-- Grille des cours -->
            <div v-if="cours.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <div v-for="cour in cours.data" :key="cour.id"
                   class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 border border-gray-100">

                <!-- Badge payant/gratuit -->
                <div class="absolute top-4 right-4 z-10">
                  <span v-if="cour.est_payant"
                        class="bg-gradient-to-r from-red-500 to-pink-600 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                    PREMIUM
                  </span>
                  <span v-else
                        class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                    GRATUIT
                  </span>
                </div>

                <!-- Image/Cover -->
                <div class="h-48 bg-gradient-to-r from-blue-100 to-indigo-100 flex items-center justify-center">
                  <div class="text-center">
                    <div class="text-5xl mb-2">
                      {{ getMatiereIcon(cour.matiere) }}
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 px-4">{{ cour.matiere }}</h3>
                  </div>
                </div>

                <!-- Contenu -->
                <div class="p-6">
                  <!-- Titre et prof -->
                  <div class="mb-4">
                    <h3 class="text-xl font-bold text-gray-800 mb-2 line-clamp-2">{{ cour.titre }}</h3>
                    <div class="flex items-center text-gray-600">
                      <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                      </svg>
                      <span class="text-sm">{{ cour.professeur.name }}</span>
                    </div>
                  </div>

                  <!-- Description -->
                  <p class="text-gray-600 text-sm mb-6 line-clamp-3">
                    {{ cour.description }}
                  </p>

                  <!-- Stats -->
                  <div class="flex items-center justify-between text-sm text-gray-500 mb-6">
                    <div class="flex items-center space-x-4">
                      <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        {{ cour.nombre_vues }}
                      </span>
                      <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        {{ cour.likes_count }}
                      </span>
                      <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        {{ cour.telechargements_count }}
                      </span>
                    </div>
                    <span class="text-xs text-gray-400">
                      {{ cour.created_at }}
                    </span>
                  </div>

                  <!-- Actions -->
                  <div class="flex space-x-3">
                    <Link
                      :href="route('etudiant.cours.show', cour.id)"
                      class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center font-medium py-2.5 rounded-lg transition"
                    >
                      Consulter
                    </Link>
                    <button
                      v-if="cour.fichier_path"
                      @click="downloadCourse(cour)"
                      class="px-4 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2.5 rounded-lg transition"
                      title="Télécharger"
                    >
                      ↓
                    </button>
                  </div>

                  <!-- Prix (si payant) -->
                  <div v-if="cour.est_payant" class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center justify-between">
                      <span class="text-gray-600">Prix:</span>
                      <span class="text-xl font-bold text-red-600">{{ cour.prix }} €</span>
                    </div>
                    <button
                      class="w-full mt-3 bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white font-bold py-2.5 rounded-lg transition shadow-lg"
                    >
                      Acheter maintenant
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Aucun résultat -->
            <div v-else class="text-center py-16 bg-white rounded-2xl shadow-lg">
              <div class="text-6xl mb-4">🔍</div>
              <h3 class="text-xl font-bold text-gray-700 mb-2">Aucun cours trouvé</h3>
              <p class="text-gray-500 mb-6">Essayez de modifier vos critères de recherche</p>
              <button
                @click="resetFilters"
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2.5 rounded-lg transition"
              >
                Réinitialiser les filtres
              </button>
            </div>

            <!-- Pagination -->
            <div v-if="cours.data.length > 0" class="mt-8">
              <nav class="flex items-center justify-center space-x-2">
                <Link
                  v-for="(link, index) in cours.links"
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
          </div>
        </div>
      </div>
    </div>
  </NavbarUser>
</template>

<script setup>
import { ref, watch, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import NavbarUser from '@/Layouts/NavbarEtudiants.vue'

const props = defineProps({
  cours: Object,
  filtres: Object,
  statistiques: Object
})

// Filtres réactifs
const search = ref(props.filtres.search || '')
const categorie = ref(props.filtres.categorie || '')
const matiere = ref(props.filtres.matiere || '')
const type = ref(props.filtres.type || '')
const tri = ref('recent')

// Appliquer les filtres
const applyFilters = () => {
  router.get(route('etudiant.cours.index'), {
    search: search.value,
    categorie: categorie.value,
    matiere: matiere.value,
    type: type.value,
  }, {
    preserveState: true,
    preserveScroll: true,
  })
}

// Réinitialiser les filtres
const resetFilters = () => {
  search.value = ''
  categorie.value = ''
  matiere.value = ''
  type.value = ''
  applyFilters()
}

// Télécharger un cours
const downloadCourse = (cour) => {
  window.location.href = route('etudiant.cours.download', cour.id)
}

// Fonction pour obtenir l'icône de la matière
const getMatiereIcon = (matiere) => {
  const icons = {
    'Mathématiques': '🧮',
    'Informatique': '💻',
    'Physique': '⚛️',
    'Chimie': '🧪',
    'Biologie': '🧬',
    'Français': '📝',
    'Anglais': '🇬🇧',
    'Histoire': '📜',
    'Géographie': '🌍',
    'Économie': '📈',
    'Droit': '⚖️',
    'Philosophie': '🤔',
  }

  return icons[matiere] || '📚'
}

// Watch pour le tri
watch(tri, (newTri) => {
  // Implémenter la logique de tri si nécessaire
  console.log('Tri sélectionné:', newTri)
})
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.sticky {
  position: sticky;
}
</style>
