<template>
  <NavbarUser>
    <div class="min-h-screen bg-gray-50">
      <!-- Header -->
      <div class="bg-white shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex-1">
              <div class="flex items-center gap-3 mb-2">
                <button @click="$inertia.visit(route('etudiant.devoirs.index'))"
                        class="text-gray-500 hover:text-gray-700">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                  </svg>
                </button>
                <h1 class="text-2xl font-bold text-gray-900">
                  {{ devoir.titre }}
                </h1>
              </div>
              <p class="text-gray-600">
                {{ devoir.matiere }} • Par {{ devoir.professeur?.name || 'Professeur' }}
              </p>
            </div>
            <div class="flex items-center gap-3">
              <span v-if="estEnRetard"
                    class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-red-100 text-red-800">
                En retard
              </span>
              <span v-else-if="joursRestants <= 2"
                    class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                {{ joursRestants }} jour(s) restant(s)
              </span>
              <span v-else
                    class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                {{ joursRestants }} jour(s) restant(s)
              </span>
            </div>
          </div>
        </div>
      </div>

      <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Messages flash -->
        <!-- Messages flash - LIGNES 44-68 -->
<div v-if="$page.props.flash && $page.props.flash.success" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
  <div class="flex items-center">
    <svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
    </svg>
    <span class="text-green-800 font-medium">{{ $page.props.flash.success }}</span>
  </div>
</div>

<div v-if="$page.props.flash && $page.props.flash.error" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
  <div class="flex items-center">
    <svg class="h-5 w-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
    </svg>
    <span class="text-red-800 font-medium">{{ $page.props.flash.error }}</span>
  </div>
</div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Colonne principale -->
          <div class="lg:col-span-2">
            <!-- Détails du devoir -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">
              <h2 class="text-lg font-semibold text-gray-900 mb-4">Détails du devoir</h2>

              <div class="prose max-w-none mb-6">
                <p class="text-gray-700 whitespace-pre-line">{{ devoir.description || 'Pas de description fournie.' }}</p>
              </div>

              <!-- Infos importantes -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <h3 class="text-sm font-medium text-gray-500 mb-2">Informations clés</h3>
                  <dl class="space-y-3">
                    <div class="flex justify-between">
                      <dt class="text-sm text-gray-600">Date limite</dt>
                      <dd class="text-sm font-medium text-gray-900">{{ formatDate(devoir.date_limite) }}</dd>
                    </div>
                    <div class="flex justify-between">
                      <dt class="text-sm text-gray-600">Points</dt>
                      <dd class="text-sm font-bold text-blue-600">{{ devoir.points }}</dd>
                    </div>
                    <div class="flex justify-between">
                      <dt class="text-sm text-gray-600">Professeur</dt>
                      <dd class="text-sm font-medium text-gray-900">{{ devoir.professeur?.name || 'Non spécifié' }}</dd>
                    </div>
                  </dl>
                </div>

                <!-- Énoncé -->
                <div>
                  <h3 class="text-sm font-medium text-gray-500 mb-2">Énoncé</h3>
                  <div v-if="devoir.fichier_path" class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                      <div class="flex items-center">
                        <svg class="w-8 h-8 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <div>
                          <p class="text-sm font-medium text-gray-900">Énoncé du devoir</p>
                          <p class="text-xs text-gray-500">PDF • Téléchargeable</p>
                        </div>
                      </div>
                      <button
                        @click="downloadEnonce"
                        class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
                      >
                        Télécharger
                      </button>
                    </div>
                  </div>
                  <div v-else class="text-center py-4 border border-gray-200 rounded-lg">
                    <p class="text-sm text-gray-500">Aucun fichier d'énoncé fourni</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Soumission existante -->
            <div v-if="soumissionExistante" class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
              <h2 class="text-lg font-semibold text-gray-900 mb-4">Votre soumission</h2>

              <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-3">
                  <div class="flex items-center">
                    <svg class="w-8 h-8 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                      <p class="text-sm font-medium text-gray-900">Soumis le {{ formatDate(soumissionExistante.date_soumission) }}</p>
                      <p class="text-xs text-gray-500">Statut: {{ getStatutText(soumissionExistante.statut) }}</p>
                    </div>
                  </div>
                  <span :class="getStatutClass(soumissionExistante.statut)"
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                    {{ getStatutText(soumissionExistante.statut) }}
                  </span>
                </div>

                <div v-if="soumissionExistante.commentaire" class="mt-3 pt-3 border-t border-gray-100">
                  <p class="text-sm text-gray-700">{{ soumissionExistante.commentaire }}</p>
                </div>

                <div v-if="soumissionExistante.note !== null" class="mt-3 pt-3 border-t border-gray-100">
                  <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Note obtenue</span>
                    <span class="text-lg font-bold text-green-600">{{ soumissionExistante.note }}/20</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Formulaire de soumission -->
            <div v-else-if="peutSoumettre" class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
              <h2 class="text-lg font-semibold text-gray-900 mb-4">Soumettre votre travail</h2>

              <form @submit.prevent="submitForm">
                <!-- Fichier -->
                <div class="mb-6">
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Fichier de réponse *
                  </label>
                  <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors">
                    <div class="space-y-1 text-center">
                      <svg v-if="!form.fichier" class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                      </svg>
                      <div v-if="form.fichier" class="flex items-center justify-center">
                        <svg class="w-8 h-8 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-900">{{ form.fichier.name }}</span>
                      </div>
                      <div class="flex text-sm text-gray-600">
                        <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                          <span v-if="!form.fichier">Choisir un fichier</span>
                          <span v-else>Changer de fichier</span>
                          <input type="file" @change="handleFileChange" class="sr-only" accept=".pdf,.doc,.docx,.zip,.rar" required>
                        </label>
                        <p v-if="!form.fichier" class="pl-1">ou glisser-déposer</p>
                      </div>
                      <p class="text-xs text-gray-500">PDF, DOC, DOCX, ZIP, RAR jusqu'à 10MB</p>
                    </div>
                  </div>
                  <p v-if="errors.fichier" class="mt-1 text-sm text-red-600">{{ errors.fichier }}</p>
                </div>

                <!-- Commentaire -->
                <div class="mb-6">
                  <label for="commentaire" class="block text-sm font-medium text-gray-700 mb-2">
                    Commentaire (optionnel)
                  </label>
                  <textarea
                    v-model="form.commentaire"
                    id="commentaire"
                    rows="3"
                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border border-gray-300 rounded-md"
                    placeholder="Ajoutez un commentaire pour votre professeur..."
                  ></textarea>
                </div>

                <!-- Bouton de soumission -->
                <div class="flex justify-end">
                  <button
                    type="submit"
                    :disabled="form.processing"
                    :class="[
                      'px-6 py-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white',
                      form.processing
                        ? 'bg-blue-400 cursor-not-allowed'
                        : 'bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500'
                    ]"
                  >
                    <span v-if="form.processing">
                      <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      Soumission en cours...
                    </span>
                    <span v-else>Soumettre le devoir</span>
                  </button>
                </div>
              </form>
            </div>

            <!-- Impossible de soumettre -->
            <div v-else class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
              <h2 class="text-lg font-semibold text-gray-900 mb-4">Soumission</h2>
              <div class="text-center py-6">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                  <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">
                  {{ estEnRetard ? 'Date limite dépassée' : 'Déjà soumis' }}
                </h3>
                <p class="text-gray-600">
                  {{ estEnRetard
                    ? 'La date limite de soumission est passée. Vous ne pouvez plus soumettre ce devoir.'
                    : 'Vous avez déjà soumis ce devoir.'
                  }}
                </p>
              </div>
            </div>
          </div>

          <!-- Sidebar -->
          <div class="space-y-6">
            <!-- Infos importantes -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
              <h3 class="text-sm font-medium text-gray-900 mb-4">Dates importantes</h3>
              <dl class="space-y-3">
                <div class="flex items-start">
                  <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                  </div>
                  <div class="ml-3">
                    <dt class="text-sm font-medium text-gray-500">Publication</dt>
                    <dd class="text-sm text-gray-900">{{ formatDate(devoir.created_at) }}</dd>
                  </div>
                </div>
                <div class="flex items-start">
                  <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                  </div>
                  <div class="ml-3">
                    <dt class="text-sm font-medium text-gray-500">Date limite</dt>
                    <dd class="text-sm font-bold text-gray-900">{{ formatDate(devoir.date_limite) }}</dd>
                  </div>
                </div>
              </dl>
            </div>

            <!-- Instructions -->
            <div class="bg-blue-50 rounded-xl border border-blue-100 p-6">
              <h3 class="text-sm font-medium text-blue-900 mb-3">💡 Instructions</h3>
              <ul class="space-y-2 text-sm text-blue-800">
                <li class="flex items-start">
                  <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                  </svg>
                  <span>Téléchargez l'énoncé pour connaître les consignes</span>
                </li>
                <li class="flex items-start">
                  <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                  </svg>
                  <span>Format accepté: PDF, DOC, DOCX, ZIP, RAR</span>
                </li>
                <li class="flex items-start">
                  <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                  </svg>
                  <span>Taille maximale: 10MB</span>
                </li>
                <li class="flex items-start">
                  <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                  </svg>
                  <span>Vous ne pouvez soumettre qu'une seule fois</span>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </main>
    </div>
  </NavbarUser>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import NavbarUser from '@/Layouts/NavbarEtudiants.vue'

const props = defineProps({
  devoir: Object,
  soumissionExistante: Object,
  estEnRetard: Boolean,
  joursRestants: Number,
  peutSoumettre: Boolean,
  errors: Object
})

const form = useForm({
  fichier: null,
  commentaire: '',
})

const handleFileChange = (event) => {
  form.fichier = event.target.files[0]
}

const submitForm = () => {
  form.post(route('etudiant.devoirs.soumettre', props.devoir.id), {
    preserveScroll: true,
    forceFormData: true,
  })
}

const formatDate = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return date.toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const getStatutClass = (statut) => {
  const classes = {
    'en_attente': 'bg-yellow-100 text-yellow-800',
    'corrige': 'bg-green-100 text-green-800',
    'refuse': 'bg-red-100 text-red-800'
  }
  return classes[statut] || 'bg-gray-100 text-gray-800'
}

const getStatutText = (statut) => {
  const statuts = {
    'en_attente': 'En attente',
    'corrige': 'Corrigé',
    'refuse': 'Refusé'
  }
  return statuts[statut] || statut
}

const downloadEnonce = () => {
  window.location.href = `/etudiant/devoirs/${props.devoir.id}/download`
}
</script>
