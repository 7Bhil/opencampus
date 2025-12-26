<template>
  <NavbarUser>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
      <!-- Header -->
      <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-8 shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-3xl md:text-4xl font-bold mb-2">
                👋 Bienvenue, {{ $page.props.auth.user.name }}
              </h1>
              <p class="text-blue-100 opacity-90">
                Votre tableau de bord étudiant - Suivez vos cours et devoirs
              </p>
            </div>
            <div class="hidden md:block">
              <div class="text-4xl">🎓</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <main class="max-w-7xl mx-auto px-4 py-8">
        <!-- Statistiques en temps réel -->
        <section class="mb-8">
          <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
            <span class="text-blue-500">📊</span> Statistiques
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Cours disponibles -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500 hover:shadow-xl transition-shadow">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-gray-500 text-sm font-medium">Cours disponibles</p>
                  <p class="text-3xl font-bold mt-2 text-gray-800">{{ stats.cours_disponibles || 0 }}</p>
                </div>
                <div class="text-3xl text-blue-500">📚</div>
              </div>
              <div class="mt-4">
                <Link :href="route('etudiant.cours.index')"
                      class="text-blue-600 hover:text-blue-800 text-sm font-medium inline-flex items-center">
                  Voir tous les cours
                  <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                  </svg>
                </Link>
              </div>
            </div>

            <!-- Devoirs soumis -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500 hover:shadow-xl transition-shadow">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-gray-500 text-sm font-medium">Devoirs soumis</p>
                  <p class="text-3xl font-bold mt-2 text-gray-800">{{ stats.devoirs_soumis || 0 }}</p>
                </div>
                <div class="text-3xl text-green-500">📝</div>
              </div>
              <div class="mt-4">
                <Link :href="route('etudiant.devoirs.index')"
                      class="text-green-600 hover:text-green-800 text-sm font-medium inline-flex items-center">
                  Voir mes soumissions
                  <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                  </svg>
                </Link>
              </div>
            </div>

            <!-- Points gagnés -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-purple-500 hover:shadow-xl transition-shadow">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-gray-500 text-sm font-medium">Points gagnés</p>
                  <p class="text-3xl font-bold mt-2 text-gray-800">{{ stats.points_gagnes || 0 }}</p>
                </div>
                <div class="text-3xl text-purple-500">⭐</div>
              </div>
              <div class="mt-4">
                <span class="text-purple-600 text-sm font-medium">Votre score total</span>
              </div>
            </div>

            <!-- Devoirs en retard -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-red-500 hover:shadow-xl transition-shadow">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-gray-500 text-sm font-medium">Devoirs en retard</p>
                  <p class="text-3xl font-bold mt-2 text-gray-800">{{ stats.devoirs_en_retard || 0 }}</p>
                </div>
                <div class="text-3xl text-red-500">⏰</div>
              </div>
              <div class="mt-4">
                <span v-if="stats.devoirs_en_retard > 0" class="text-red-600 text-sm font-medium">
                  {{ stats.devoirs_en_retard }} devoir(s) non rendu(s)
                </span>
                <span v-else class="text-green-600 text-sm font-medium">
                  Aucun retard 🎉
                </span>
              </div>
            </div>
          </div>
        </section>

        <!-- Deux colonnes principales -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Colonne gauche : Devoirs -->
          <div class="lg:col-span-2 space-y-8">
            <!-- Devoirs à rendre -->
            <section class="bg-white rounded-2xl shadow-lg p-6">
              <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                  <span class="text-blue-500">📋</span> Devoirs à rendre
                </h3>
                <Link :href="route('etudiant.devoirs.index')"
                      class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                  Voir tous →
                </Link>
              </div>

              <div v-if="devoirs_a_rendre && devoirs_a_rendre.length > 0" class="space-y-4">
                <div v-for="devoir in devoirs_a_rendre" :key="devoir.id"
                     class="border border-gray-200 rounded-xl p-4 hover:border-blue-300 hover:shadow-md transition-all">
                  <div class="flex justify-between items-start">
                    <div class="flex-1">
                      <div class="flex items-center gap-3 mb-2">
                        <h4 class="font-bold text-gray-800">{{ devoir.titre }}</h4>
                        <span :class="getUrgenceBadgeClass(devoir.statut_urgence)"
                              class="px-2 py-1 rounded-full text-xs font-medium">
                          {{ getUrgenceText(devoir.statut_urgence) }}
                        </span>
                      </div>
                      <p class="text-sm text-gray-600 mb-2">{{ devoir.matiere }}</p>
                      <div class="flex items-center gap-4 text-sm text-gray-500">
                        <span class="flex items-center gap-1">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                          </svg>
                          {{ devoir.professeur }}
                        </span>
                        <span class="flex items-center gap-1">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                          </svg>
                          {{ devoir.date_limite }}
                        </span>
                        <span class="flex items-center gap-1">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                          </svg>
                          {{ devoir.points }} points
                        </span>
                      </div>
                    </div>
                    <Link :href="devoir.lien_devoir"
                          class="ml-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition">
                      Rendre
                    </Link>
                  </div>
                </div>
              </div>

              <div v-else class="text-center py-8">
                <div class="text-6xl mb-4">🎉</div>
                <h4 class="text-lg font-semibold text-gray-700 mb-2">Aucun devoir à rendre</h4>
                <p class="text-gray-500">Tous vos devoirs sont à jour !</p>
              </div>
            </section>

            <!-- Devoirs en retard -->
            <section v-if="devoirs_en_retard && devoirs_en_retard.length > 0" class="bg-white rounded-2xl shadow-lg p-6 border border-red-200">
              <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                  <span class="text-red-500">⏰</span> Devoirs en retard
                </h3>
                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">
                  {{ devoirs_en_retard.length }} retard(s)
                </span>
              </div>

              <div class="space-y-4">
                <div v-for="devoir in devoirs_en_retard" :key="devoir.id"
                     class="border border-red-200 bg-red-50 rounded-xl p-4">
                  <div class="flex justify-between items-start">
                    <div>
                      <h4 class="font-bold text-gray-800">{{ devoir.titre }}</h4>
                      <p class="text-sm text-gray-600 mb-2">{{ devoir.matiere }}</p>
                      <div class="flex items-center gap-4 text-sm text-gray-500">
                        <span>{{ devoir.professeur }}</span>
                        <span class="text-red-600 font-medium">
                          Retard: {{ devoir.jours_retard }} jour(s)
                        </span>
                        <span>Échéance: {{ devoir.date_limite }}</span>
                      </div>
                    </div>
                    <Link :href="route('etudiant.devoirs.show', devoir.id)"
                          class="ml-4 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition">
                      Rendre maintenant
                    </Link>
                  </div>
                </div>
              </div>
            </section>
          </div>

          <!-- Colonne droite : Cours et Soumissions -->
          <div class="space-y-8">
            <!-- Cours récents -->
            <section class="bg-white rounded-2xl shadow-lg p-6">
              <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                  <span class="text-green-500">📚</span> Cours récents
                </h3>
                <Link :href="route('etudiant.cours.index')"
                      class="text-green-600 hover:text-green-800 text-sm font-medium">
                  Tous les cours →
                </Link>
              </div>

              <div v-if="cours_recents && cours_recents.length > 0" class="space-y-4">
                <div v-for="cours in cours_recents" :key="cours.id"
                     class="border border-gray-200 rounded-xl p-4 hover:border-green-300 hover:shadow-md transition-all">
                  <div class="flex justify-between items-start mb-3">
                    <h4 class="font-bold text-gray-800">{{ cours.titre }}</h4>
                    <span v-if="cours.est_payant"
                          class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-medium">
                      {{ cours.prix }}€
                    </span>
                    <span v-else
                          class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">
                      Gratuit
                    </span>
                  </div>
                  <p class="text-sm text-gray-600 mb-3">{{ cours.description_courte }}</p>
                  <div class="flex justify-between items-center text-sm text-gray-500">
                    <span>{{ cours.professeur }}</span>
                    <span>{{ cours.date_publication }}</span>
                  </div>
                  <div class="mt-4">
                    <Link :href="cours.lien_cours"
                          class="text-green-600 hover:text-green-800 text-sm font-medium inline-flex items-center">
                      Consulter le cours
                      <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                      </svg>
                    </Link>
                  </div>
                </div>
              </div>

              <div v-else class="text-center py-4">
                <p class="text-gray-500">Aucun cours disponible pour le moment</p>
              </div>
            </section>

            <!-- Dernières soumissions -->
            <section class="bg-white rounded-2xl shadow-lg p-6">
              <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                <span class="text-purple-500">📨</span> Dernières soumissions
              </h3>

              <div v-if="dernieres_soumissions && dernieres_soumissions.length > 0" class="space-y-4">
                <div v-for="soumission in dernieres_soumissions" :key="soumission.id"
                     class="border border-gray-200 rounded-xl p-4">
                  <h4 class="font-bold text-gray-800 mb-2">{{ soumission.devoir_titre }}</h4>
                  <div class="flex justify-between items-center mb-3">
                    <span class="text-sm text-gray-600">{{ soumission.matiere }}</span>
                    <span :class="soumission.statut_couleur"
                          class="px-2 py-1 rounded text-xs font-medium">
                      {{ soumission.statut }}
                    </span>
                  </div>
                  <div class="flex justify-between items-center text-sm text-gray-500">
                    <span>{{ soumission.professeur }}</span>
                    <span>{{ soumission.date_soumission }}</span>
                  </div>
                  <div v-if="soumission.note !== null" class="mt-3 pt-3 border-t">
                    <div class="flex items-center justify-between">
                      <span class="text-sm text-gray-600">Note obtenue:</span>
                      <span class="text-lg font-bold text-green-600">{{ soumission.note }}/20</span>
                    </div>
                  </div>
                </div>
              </div>

              <div v-else class="text-center py-4">
                <p class="text-gray-500">Aucune soumission pour le moment</p>
                <Link :href="route('etudiant.devoirs.index')"
                      class="mt-2 text-purple-600 hover:text-purple-800 text-sm font-medium inline-block">
                  Voir les devoirs disponibles
                </Link>
              </div>
            </section>

            <!-- Actions rapides -->
            <section class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl shadow-lg p-6 text-white">
              <h3 class="text-xl font-bold mb-4">⚡ Actions rapides</h3>
              <div class="space-y-3">
                <Link :href="route('etudiant.devoirs.index')"
                      class="flex items-center justify-between bg-white/20 hover:bg-white/30 p-3 rounded-lg transition">
                  <span>📝 Voir tous les devoirs</span>
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                  </svg>
                </Link>
                <Link :href="route('etudiant.cours.index')"
                      class="flex items-center justify-between bg-white/20 hover:bg-white/30 p-3 rounded-lg transition">
                  <span>📚 Explorer les cours</span>
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                  </svg>
                </Link>
                <Link :href="route('etudiant.upload')"
                      class="flex items-center justify-between bg-white/20 hover:bg-white/30 p-3 rounded-lg transition">
                  <span>📤 Soumettre un devoir</span>
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                  </svg>
                </Link>
              </div>
            </section>
          </div>
        </div>
      </main>
    </div>
  </NavbarUser>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import NavbarUser from '@/Layouts/NavbarEtudiants.vue'

defineProps({
  stats: Object,
  devoirs_a_rendre: Array,
  devoirs_en_retard: Array,
  cours_recents: Array,
  dernieres_soumissions: Array
})

// Fonctions utilitaires
const getUrgenceBadgeClass = (statut) => {
  const classes = {
    expire: 'bg-red-100 text-red-800',
    tres_urgent: 'bg-red-100 text-red-800',
    urgent: 'bg-orange-100 text-orange-800',
    normal: 'bg-green-100 text-green-800'
  }
  return classes[statut] || 'bg-gray-100 text-gray-800'
}

const getUrgenceText = (statut) => {
  const textes = {
    expire: 'Expiré',
    tres_urgent: 'Très urgent',
    urgent: 'Urgent',
    normal: 'Normal'
  }
  return textes[statut] || 'À faire'
}
</script>

<style scoped>
/* Animations subtiles */
.hover\:shadow-md {
  transition: all 0.3s ease;
}

.hover\:shadow-xl {
  transition: all 0.3s ease;
}

.border-l-4 {
  transition: border-color 0.3s ease;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .text-3xl {
    font-size: 1.5rem;
  }

  .text-4xl {
    font-size: 2rem;
  }
}
</style>
