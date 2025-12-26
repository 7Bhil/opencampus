<template>
  <NavbarUser>
    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
      <!-- Header -->
      <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-8">
        <div class="max-w-7xl mx-auto px-4">
          <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
              <div class="flex items-center gap-3 mb-2">
                <span class="text-3xl">{{ getMatiereIcon(cours.matiere) }}</span>
                <h1 class="text-3xl md:text-4xl font-bold">{{ cours.titre }}</h1>
              </div>
              <p class="text-blue-100 text-lg">{{ cours.matiere }}</p>
            </div>
            <div class="flex items-center space-x-4">
              <button
                @click="toggleLike"
                :class="[
                  'px-4 py-2 rounded-lg font-medium transition',
                  userLiked
                    ? 'bg-pink-500 hover:bg-pink-600 text-white'
                    : 'bg-white/20 hover:bg-white/30 text-white'
                ]"
              >
                <span class="flex items-center gap-2">
                  <span v-if="userLiked">❤️</span>
                  <span v-else>🤍</span>
                  {{ cours.likes_count }} likes
                </span>
              </button>
              <Link
                :href="route('etudiant.cours.index')"
                class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg font-medium transition"
              >
                ← Retour
              </Link>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Colonne gauche : Contenu principal -->
          <div class="lg:col-span-2">
            <!-- Carte du cours -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
              <!-- Infos du cours -->
              <div class="flex flex-wrap gap-4 mb-6 text-gray-600">
                <div class="flex items-center gap-2">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                  </svg>
                  <span>Professeur: <strong>{{ cours.professeur.name }}</strong></span>
                </div>
                <div class="flex items-center gap-2">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                  </svg>
                  <span>Publié le: {{ cours.created_at }}</span>
                </div>
                <div class="flex items-center gap-2">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                  </svg>
                  <span>{{ cours.nombre_vues }} vues</span>
                </div>
                <div class="flex items-center gap-2">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                  </svg>
                  <span>{{ cours.telechargements_count }} téléchargements</span>
                </div>
              </div>

              <!-- Catégorie et statut -->
              <div class="flex flex-wrap gap-3 mb-8">
                <span v-if="cours.categorie"
                      class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                  {{ cours.categorie }}
                </span>
                <span v-if="cours.est_payant"
                      class="bg-gradient-to-r from-red-500 to-pink-600 text-white px-3 py-1 rounded-full text-sm font-bold">
                  COURS PREMIUM
                </span>
                <span v-else
                      class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-3 py-1 rounded-full text-sm font-bold">
                  COURS GRATUIT
                </span>
              </div>

              <!-- Description -->
              <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">📖 Description</h2>
                <div class="prose max-w-none text-gray-700 bg-gray-50 p-6 rounded-lg">
                  <p class="whitespace-pre-line">{{ cours.description }}</p>
                </div>
              </div>

              <!-- Actions -->
              <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                  <div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Télécharger le cours</h3>
                    <p class="text-gray-600">
                      Format: {{ getFileExtension(cours.fichier_path) }} •
                      <span v-if="cours.est_payant" class="text-red-600 font-bold">
                        Prix: {{ cours.prix }} €
                      </span>
                      <span v-else class="text-green-600 font-bold">
                        Gratuit
                      </span>
                    </p>
                  </div>
                  <div class="flex space-x-4">
                    <button
                      @click="downloadCourse"
                      :class="[
                        'px-6 py-3 rounded-lg font-bold transition shadow-lg',
                        cours.est_payant
                          ? 'bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white'
                          : 'bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white'
                      ]"
                    >
                      <span class="flex items-center gap-2">
                        <span>↓</span>
                        <span>{{ cours.est_payant ? 'Acheter et télécharger' : 'Télécharger gratuitement' }}</span>
                      </span>
                    </button>
                    <button
                      @click="toggleLike"
                      :class="[
                        'px-4 py-3 rounded-lg font-medium transition',
                        userLiked
                          ? 'bg-pink-500 hover:bg-pink-600 text-white'
                          : 'bg-white hover:bg-gray-100 text-gray-800 border border-gray-300'
                      ]"
                    >
                      <span class="flex items-center gap-2">
                        <span v-if="userLiked">❤️</span>
                        <span v-else>🤍</span>
                        <span class="hidden sm:inline">Like</span>
                      </span>
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Commentaires (à implémenter plus tard) -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
              <h2 class="text-2xl font-bold text-gray-800 mb-6">💬 Commentaires</h2>
              <div class="text-center py-8">
                <div class="text-4xl mb-4">💭</div>
                <p class="text-gray-500">La section commentaires sera disponible prochainement</p>
              </div>
            </div>
          </div>

          <!-- Colonne droite : Professeur et Cours similaires -->
          <div class="space-y-8">
            <!-- Carte professeur -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
              <h3 class="text-xl font-bold text-gray-800 mb-4">👨‍🏫 Professeur</h3>
              <div class="flex items-center gap-4 mb-4">
                <div class="w-16 h-16 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                  {{ getInitials(cours.professeur.name) }}
                </div>
                <div>
                  <h4 class="font-bold text-gray-800">{{ cours.professeur.name }}</h4>
                  <p class="text-gray-600 text-sm">Professeur de {{ cours.matiere }}</p>
                </div>
              </div>
              <div class="space-y-2 text-sm text-gray-600">
                <div class="flex items-center gap-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                  </svg>
                  <span>Université OpenCampus</span>
                </div>
                <div class="flex items-center gap-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  <span>Expert certifié</span>
                </div>
              </div>
            </div>

            <!-- Cours similaires -->
            <div v-if="coursSimilaires && coursSimilaires.length > 0" class="bg-white rounded-2xl shadow-lg p-6">
              <h3 class="text-xl font-bold text-gray-800 mb-4">📚 Cours similaires</h3>
              <div class="space-y-4">
                <div v-for="similaire in coursSimilaires" :key="similaire.id"
                     class="border border-gray-200 rounded-xl p-4 hover:border-blue-300 hover:shadow-md transition">
                  <div class="flex justify-between items-start mb-2">
                    <h4 class="font-bold text-gray-800 line-clamp-2">{{ similaire.titre }}</h4>
                    <span v-if="similaire.est_payant"
                          class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">
                      {{ similaire.prix }}€
                    </span>
                    <span v-else
                          class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">
                      Gratuit
                    </span>
                  </div>
                  <p class="text-sm text-gray-600 mb-3">{{ similaire.matiere }}</p>
                  <div class="flex justify-between items-center text-xs text-gray-500">
                    <span>{{ similaire.professeur.name }}</span>
                    <span>{{ similaire.likes_count }} likes</span>
                  </div>
                  <div class="mt-3">
                    <Link
                      :href="route('etudiant.cours.show', similaire.id)"
                      class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                    >
                      Consulter →
                    </Link>
                  </div>
                </div>
              </div>
            </div>

            <!-- Informations -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6">
              <h3 class="text-lg font-bold text-gray-800 mb-4">ℹ️ Informations</h3>
              <ul class="space-y-3 text-sm text-gray-600">
                <li class="flex items-start gap-2">
                  <span class="text-green-500">✓</span>
                  <span>Accès immédiat après téléchargement</span>
                </li>
                <li class="flex items-start gap-2">
                  <span class="text-green-500">✓</span>
                  <span>Contenu vérifié par nos experts</span>
                </li>
                <li class="flex items-start gap-2">
                  <span class="text-green-500">✓</span>
                  <span>Mises à jour gratuites</span>
                </li>
                <li class="flex items-start gap-2">
                  <span class="text-green-500">✓</span>
                  <span>Support disponible</span>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </NavbarUser>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import NavbarUser from '@/Layouts/NavbarEtudiants.vue'

const props = defineProps({
  cours: Object,
  userLiked: Boolean,
  coursSimilaires: Array
})

// Fonctions utilitaires
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

const getInitials = (name) => {
  return name.split(' ').map(n => n[0]).join('').toUpperCase()
}

const getFileExtension = (path) => {
  if (!path) return 'PDF'
  const ext = path.split('.').pop().toUpperCase()
  return ext || 'PDF'
}

// Actions
const toggleLike = () => {
  router.post(route('etudiant.cours.toggle-like', props.cours.id), {}, {
    preserveScroll: true,
    onSuccess: (response) => {
      // Mettre à jour localement
      props.cours.likes_count = response.props.likes_count
      props.userLiked = response.props.liked
    }
  })
}

const downloadCourse = () => {
  window.location.href = route('etudiant.cours.download', props.cours.id)
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.prose {
  max-width: none;
}

.whitespace-pre-line {
  white-space: pre-line;
}
</style>
