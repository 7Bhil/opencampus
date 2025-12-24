<template>
  <NavbarUser>
    <div class="min-h-screen bg-gray-50">
      <!-- En-tête -->
      <header class="bg-blue-600 rounded-b-lg py-12 px-4 shadow-lg">
        <div class="max-w-6xl mx-auto text-center">
          <div class="flex items-center justify-center gap-3 mb-4">
            <div class="text-4xl">📝</div>
            <h1 class="text-4xl md:text-5xl font-bold text-white">Mes devoirs</h1>
          </div>
          <p class="text-xl text-white opacity-90">Gérez et soumettez vos travaux à rendre</p>
        </div>
      </header>

      <main class="max-w-7xl mx-auto px-4 py-8">
        <!-- Statistiques rapides -->
        <section class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
          <div class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-blue-500">
            <div class="text-3xl font-bold text-blue-600 mb-2">{{ stats.enCours }}</div>
            <div class="text-gray-600">En cours</div>
          </div>
          <div class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-orange-500">
            <div class="text-3xl font-bold text-orange-600 mb-2">{{ stats.urgent }}</div>
            <div class="text-gray-600">Urgents</div>
          </div>
          <div class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-green-500">
            <div class="text-3xl font-bold text-green-600 mb-2">{{ stats.soumis }}</div>
            <div class="text-gray-600">Soumis</div>
          </div>
          <div class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-red-500">
            <div class="text-3xl font-bold text-red-600 mb-2">{{ stats.expires }}</div>
            <div class="text-gray-600">Expirés</div>
          </div>
        </section>

        <!-- Barre d'actions et recherche -->
        <section class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
          <div class="flex gap-3">
            <button
              class="px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-xl font-semibold shadow hover:bg-gray-50 transition-all flex items-center gap-2"
              @click="filterByStatus('all')"
              :class="{ 'bg-blue-50 border-blue-200 text-blue-700': activeStatusFilter === 'all' }"
            >
              <i class="bi bi-list-check"></i>
              Tous les devoirs
            </button>
          </div>

          <div class="flex gap-3">
            <select v-model="sortBy" class="px-4 py-3 border border-gray-300 rounded-xl bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="date">Trier par date</option>
              <option value="urgence">Trier par urgence</option>
              <option value="matiere">Trier par matière</option>
            </select>

            <div class="relative">
              <input
                type="text"
                placeholder="Rechercher un devoir..."
                v-model="searchQuery"
                class="pl-10 pr-4 py-3 border border-gray-300 rounded-xl bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-64"
              >
              <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
          </div>
        </section>

        <!-- Filtres par statut -->
        <section class="mb-8">
          <div class="flex flex-wrap gap-3">
            <button
              v-for="status in statusFilters"
              :key="status.id"
              class="px-4 py-2 rounded-full font-medium text-sm transition-all flex items-center gap-2"
              :class="status.class"
              @click="filterByStatus(status.id)"
            >
              <i :class="status.icon"></i>
              {{ status.label }}
            </button>
          </div>
        </section>

        <!-- Liste des devoirs -->
        <section class="space-y-6">
          <!-- Devoirs urgents (à rendre dans moins de 24h) -->
          <div v-if="urgentAssignments.length > 0" class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center gap-2">
              <i class="bi bi-exclamation-triangle-fill text-red-500"></i>
              Devoirs urgents
              <span class="bg-red-500 text-white text-sm px-3 py-1 rounded-full">{{ urgentAssignments.length }}</span>
            </h2>
            <div class="space-y-4">
              <article
                v-for="assignment in urgentAssignments"
                :key="assignment.id"
                class="bg-red-50 border border-red-200 rounded-2xl p-6 hover:shadow-lg transition-all duration-300"
              >
                <div class="flex justify-between items-start mb-4">
                  <div class="flex-1">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ assignment.title }}</h3>
                    <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-3">
                      <span class="flex items-center gap-2">
                        <i class="bi bi-book"></i>
                        {{ assignment.subject }}
                      </span>
                      <span class="flex items-center gap-2">
                        <i class="bi bi-person"></i>
                        {{ assignment.professor }}
                      </span>
                      <span class="flex items-center gap-2 text-red-500 font-semibold">
                        <i class="bi bi-clock"></i>
                        {{ getTimeLeft(assignment.dueDate) }}
                      </span>
                    </div>
                    <p class="text-gray-600">{{ assignment.description }}</p>
                  </div>
                  <!-- BON - Bouton fonctionnel avec événement click -->
<button
  class="ml-4 px-6 py-3 bg-red-500 text-white rounded-xl font-semibold hover:bg-red-600 transition-colors flex items-center gap-2 whitespace-nowrap"
  @click="openSubmissionModal(assignment)"
>
  <i class="bi bi-upload"></i>
  Soumettre
</button>
                </div>
              </article>
            </div>
          </div>

          <!-- Devoirs en cours -->
          <div v-if="activeAssignments.length > 0">
            <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center gap-2">
              <i class="bi bi-clock-history text-blue-500"></i>
              Devoirs en cours
              <span class="bg-blue-500 text-white text-sm px-3 py-1 rounded-full">{{ activeAssignments.length }}</span>
            </h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <article
                v-for="assignment in activeAssignments"
                :key="assignment.id"
                class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300"
              >
                <div class="flex justify-between items-start mb-4">
                  <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">{{ assignment.title }}</h3>
                    <div class="flex flex-wrap gap-3 text-sm text-gray-600 mb-3">
                      <span class="flex items-center gap-1">
                        <i class="bi bi-book"></i>
                        {{ assignment.subject }}
                      </span>
                      <span class="flex items-center gap-1">
                        <i class="bi bi-person"></i>
                        {{ assignment.professor }}
                      </span>
                      <span class="flex items-center gap-1" :class="getUrgencyClass(assignment.dueDate)">
                        <i class="bi bi-calendar"></i>
                        {{ formatDate(assignment.dueDate) }}
                      </span>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">{{ assignment.description }}</p>

                    <!-- Barre de progression du temps -->
                    <div class="mb-4">
                      <div class="flex justify-between text-xs text-gray-500 mb-1">
                        <span>Publié le {{ formatDate(assignment.publishedDate) }}</span>
                        <span>Échéance: {{ formatDate(assignment.dueDate) }}</span>
                      </div>
                      <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full transition-all duration-500"
                             :style="{ width: getProgress(assignment.publishedDate, assignment.dueDate) + '%' }"></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="flex justify-between items-center">
                  <span class="text-sm px-3 py-1 rounded-full font-medium" :class="assignment.statusClass">
                    {{ assignment.statusText }}
                  </span>
                  <button
                    class="px-6 py-2 bg-blue-500 text-white rounded-xl font-semibold hover:scale-105 transition-transform flex items-center gap-2"
                    @click="openSubmissionModal(assignment)"
                  >
                    <i class="bi bi-upload"></i>
                    Soumettre
                  </button>
                </div>
              </article>
            </div>
          </div>

          <!-- Devoirs soumis -->
          <div v-if="submittedAssignments.length > 0">
            <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center gap-2">
              <i class="bi bi-check-circle-fill text-green-500"></i>
              Devoirs soumis
              <span class="bg-green-500 text-white text-sm px-3 py-1 rounded-full">{{ submittedAssignments.length }}</span>
            </h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <article
                v-for="assignment in submittedAssignments"
                :key="assignment.id"
                class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 opacity-90"
              >
                <div class="flex justify-between items-start mb-4">
                  <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">{{ assignment.title }}</h3>
                    <div class="flex flex-wrap gap-3 text-sm text-gray-600 mb-3">
                      <span class="flex items-center gap-1">
                        <i class="bi bi-book"></i>
                        {{ assignment.subject }}
                      </span>
                      <span class="flex items-center gap-1">
                        <i class="bi bi-person"></i>
                        {{ assignment.professor }}
                      </span>
                      <span class="flex items-center gap-1 text-green-600">
                        <i class="bi bi-check-lg"></i>
                        Soumis le {{ assignment.submittedDate ? formatDate(assignment.submittedDate) : 'N/A' }}
                      </span>
                    </div>
                    <p class="text-gray-600 text-sm">{{ assignment.description }}</p>
                  </div>
                </div>

                <div class="flex justify-between items-center">
                  <span class="text-sm px-3 py-1 rounded-full font-medium bg-green-100 text-green-700">
                    <i class="bi bi-check-lg"></i>
                    Soumis
                  </span>
                  <button
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition-colors flex items-center gap-2"
                    @click="viewSubmission(assignment)"
                  >
                    <i class="bi bi-eye"></i>
                    Voir
                  </button>
                </div>
              </article>
            </div>
          </div>

          <!-- Devoirs expirés -->
          <div v-if="expiredAssignments.length > 0">
            <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center gap-2">
              <i class="bi bi-clock-fill text-gray-500"></i>
              Devoirs expirés
              <span class="bg-gray-500 text-white text-sm px-3 py-1 rounded-full">{{ expiredAssignments.length }}</span>
            </h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <article
                v-for="assignment in expiredAssignments"
                :key="assignment.id"
                class="bg-gray-50 rounded-2xl p-6 shadow-lg border border-gray-200 opacity-75"
              >
                <div class="flex justify-between items-start mb-4">
                  <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-600 mb-2">{{ assignment.title }}</h3>
                    <div class="flex flex-wrap gap-3 text-sm text-gray-500 mb-3">
                      <span class="flex items-center gap-1">
                        <i class="bi bi-book"></i>
                        {{ assignment.subject }}
                      </span>
                      <span class="flex items-center gap-1">
                        <i class="bi bi-person"></i>
                        {{ assignment.professor }}
                      </span>
                      <span class="flex items-center gap-1 text-red-500 font-semibold">
                        <i class="bi bi-clock"></i>
                        Délai dépassé
                      </span>
                    </div>
                    <p class="text-gray-500 text-sm">{{ assignment.description }}</p>
                  </div>
                </div>

                <div class="flex justify-between items-center">
                  <span class="text-sm px-3 py-1 rounded-full font-medium bg-red-100 text-red-700">
                    <i class="bi bi-x-circle"></i>
                    Expiré
                  </span>
                  <button
                    class="px-4 py-2 bg-gray-300 text-gray-600 rounded-xl font-medium cursor-not-allowed flex items-center gap-2"
                    disabled
                  >
                    <i class="bi bi-lock"></i>
                    Non disponible
                  </button>
                </div>
              </article>
            </div>
          </div>

          <!-- Message si aucun devoir -->
          <div v-if="filteredAssignments.length === 0" class="text-center py-12">
            <div class="text-6xl mb-4">📝</div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Aucun devoir à rendre</h3>
            <p class="text-gray-500">Vous n'avez pas de travaux en cours pour le moment</p>
          </div>
        </section>
      </main>

      <!-- Modal de soumission -->
      <div v-if="showSubmissionModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl p-8 max-w-2xl w-full max-h-[90vh] overflow-y-auto">
          <div class="flex justify-between items-center mb-6">
            <div>
              <h3 class="text-2xl font-bold text-gray-800">Soumettre le devoir</h3>
              <p class="text-gray-600">{{ currentAssignment?.title }}</p>
            </div>
            <button @click="closeSubmissionModal" class="text-gray-400 hover:text-gray-600 text-2xl">
              <i class="bi bi-x"></i>
            </button>
          </div>

          <div class="space-y-6">
            <!-- Informations du devoir -->
            <div class="bg-blue-50 rounded-xl p-4">
              <h4 class="font-semibold text-blue-800 mb-2">Informations du devoir</h4>
              <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                  <span class="text-blue-600">Matière:</span>
                  <p class="font-medium">{{ currentAssignment?.subject }}</p>
                </div>
                <div>
                  <span class="text-blue-600">Professeur:</span>
                  <p class="font-medium">{{ currentAssignment?.professor }}</p>
                </div>
                <div>
                  <span class="text-blue-600">Date limite:</span>
                  <p class="font-medium" :class="isUrgent(currentAssignment?.dueDate) ? 'text-red-500' : ''">
                    {{ currentAssignment?.dueDate ? formatDate(currentAssignment.dueDate) : 'N/A' }}
                  </p>
                </div>
                <div>
                  <span class="text-blue-600">Temps restant:</span>
                  <p class="font-medium" :class="isUrgent(currentAssignment?.dueDate) ? 'text-red-500' : ''">
                    {{ currentAssignment?.dueDate ? getTimeLeft(currentAssignment.dueDate) : 'N/A' }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Zone de dépôt de fichier -->
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-3">Votre travail (PDF uniquement)</label>
              <div
                class="border-2 border-dashed border-gray-300 rounded-2xl p-8 text-center cursor-pointer hover:border-blue-400 transition-colors"
                @click="triggerFileInput"
                @drop="handleFileDrop"
                @dragover.prevent
                @dragenter.prevent
              >
                <input
                  type="file"
                  ref="fileInput"
                  @change="handleFileSelect"
                  accept=".pdf"
                  class="hidden"
                >
                <div class="text-4xl mb-4">📄</div>
                <p class="text-gray-600 mb-2">
                  Glissez votre fichier PDF ici ou cliquez pour parcourir
                </p>
                <p class="text-sm text-gray-400">Taille maximale: 10MB</p>
              </div>

              <!-- Fichier sélectionné -->
              <div v-if="selectedFile" class="mt-4 p-4 bg-green-50 rounded-xl border border-green-200">
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-3">
                    <i class="bi bi-file-earmark-pdf text-2xl text-red-500"></i>
                    <div>
                      <p class="font-medium text-gray-800">{{ selectedFile.name }}</p>
                      <p class="text-sm text-gray-500">{{ (selectedFile.size / 1024 / 1024).toFixed(2) }} MB</p>
                    </div>
                  </div>
                  <button @click="removeFile" class="text-red-500 hover:text-red-700">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </div>
            </div>

            <!-- Commentaire optionnel -->
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-3">Commentaire (optionnel)</label>
              <textarea
                v-model="submissionComment"
                placeholder="Ajoutez un commentaire pour votre professeur..."
                rows="3"
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
              ></textarea>
            </div>

            <!-- Actions -->
            <div class="flex gap-3 pt-4">
              <button
                class="flex-1 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-colors"
                @click="closeSubmissionModal"
              >
                Annuler
              </button>
              <button
                class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-xl font-semibold hover:scale-105 transition-transform disabled:opacity-50 disabled:cursor-not-allowed"
                @click="submitAssignment"
                :disabled="!selectedFile"
              >
                <i class="bi bi-send"></i>
                Soumettre le devoir
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </NavbarUser>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import NavbarUser from '../../Layouts/NavbarEtudiants.vue'

// États réactifs
const searchQuery = ref('')
const activeStatusFilter = ref('all')
const sortBy = ref('date')
const showSubmissionModal = ref(false)
const currentAssignment = ref(null)
const selectedFile = ref(null)
const submissionComment = ref('')
const fileInput = ref(null)

// Statistiques
const stats = ref({
  enCours: 0,
  urgent: 0,
  soumis: 0,
  expires: 0
})

// Filtres par statut
const statusFilters = ref([
  { id: 'all', label: 'Tous', icon: 'bi bi-list-check', class: 'bg-blue-500 text-white hover:bg-blue-600' },
  { id: 'urgent', label: 'Urgent', icon: 'bi bi-exclamation-triangle', class: 'bg-red-100 text-red-700 hover:bg-red-200' },
  { id: 'en-cours', label: 'En cours', icon: 'bi bi-clock', class: 'bg-blue-100 text-blue-700 hover:bg-blue-200' },
  { id: 'soumis', label: 'Soumis', icon: 'bi bi-check-lg', class: 'bg-green-100 text-green-700 hover:bg-green-200' },
  { id: 'expires', label: 'Expirés', icon: 'bi bi-clock-fill', class: 'bg-gray-100 text-gray-700 hover:bg-gray-200' }
])

// Données des devoirs avec exemples dans chaque catégorie
const assignments = ref([
  // URGENT (moins de 24h)
  {
    id: 1,
    title: 'Projet Algorithmique - Arbres binaires',
    subject: 'Algorithmique',
    professor: 'Prof. Martin',
    description: 'Implémentation des opérations sur les arbres binaires de recherche avec analyse de complexité',
    dueDate: new Date(Date.now() + 12 * 60 * 60 * 1000), // 12 heures
    publishedDate: new Date('2025-01-10'),
    submittedDate: null,
    status: 'pending',
    statusText: 'Urgent',
    statusClass: 'bg-red-100 text-red-700'
  },
  {
    id: 2,
    title: 'Rapport Machine Learning',
    subject: 'Intelligence Artificielle',
    professor: 'Prof. Laurent',
    description: 'Analyse comparative des algorithmes de classification sur un jeu de données réel',
    dueDate: new Date(Date.now() + 6 * 60 * 60 * 1000), // 6 heures
    publishedDate: new Date('2025-01-12'),
    submittedDate: null,
    status: 'pending',
    statusText: 'Très urgent',
    statusClass: 'bg-red-100 text-red-700'
  },

  // EN COURS (plus de 24h)
  {
    id: 3,
    title: 'Rapport Base de Données',
    subject: 'Base de données',
    professor: 'Prof. Dubois',
    description: 'Conception et implémentation d\'une base de données pour un système de gestion de bibliothèque',
    dueDate: new Date(Date.now() + 3 * 24 * 60 * 60 * 1000), // 3 jours
    publishedDate: new Date('2025-01-08'),
    submittedDate: null,
    status: 'pending',
    statusText: 'En cours',
    statusClass: 'bg-blue-100 text-blue-700'
  },
  {
    id: 4,
    title: 'Développement Application Web',
    subject: 'Développement Web',
    professor: 'Prof. Bernard',
    description: 'Création d\'une application full-stack avec Vue.js et Node.js',
    dueDate: new Date(Date.now() + 5 * 24 * 60 * 60 * 1000), // 5 jours
    publishedDate: new Date('2025-01-05'),
    submittedDate: null,
    status: 'pending',
    statusText: 'En cours',
    statusClass: 'bg-blue-100 text-blue-700'
  },

  // SOUMIS
  {
    id: 5,
    title: 'TP Réseaux - Configuration TCP/IP',
    subject: 'Réseaux',
    professor: 'Prof. Rousseau',
    description: 'Configuration des services réseau et analyse des protocoles de communication',
    dueDate: new Date('2025-01-15'),
    publishedDate: new Date('2024-12-28'),
    submittedDate: new Date('2025-01-14'),
    status: 'submitted',
    statusText: 'Soumis',
    statusClass: 'bg-green-100 text-green-700'
  },
  {
    id: 6,
    title: 'Analyse de données statistiques',
    subject: 'Statistiques',
    professor: 'Prof. Moreau',
    description: 'Application des tests statistiques sur des données économiques réelles',
    dueDate: new Date('2025-01-10'),
    publishedDate: new Date('2024-12-20'),
    submittedDate: new Date('2025-01-09'),
    status: 'submitted',
    statusText: 'Soumis',
    statusClass: 'bg-green-100 text-green-700'
  },

  // EXPIRÉS
  {
    id: 7,
    title: 'Projet Systèmes d\'exploitation',
    subject: 'Systèmes',
    professor: 'Prof. Leroy',
    description: 'Étude comparative des algorithmes d\'ordonnancement des processus',
    dueDate: new Date('2024-12-20'), // Date passée
    publishedDate: new Date('2024-11-15'),
    submittedDate: null,
    status: 'pending',
    statusText: 'Expiré',
    statusClass: 'bg-red-100 text-red-700'
  },
  {
    id: 8,
    title: 'TD Programmation Orientée Objet',
    subject: 'Programmation',
    professor: 'Prof. Garcia',
    description: 'Exercices sur l\'héritage, le polymorphisme et les design patterns',
    dueDate: new Date('2024-12-10'), // Date passée
    publishedDate: new Date('2024-11-01'),
    submittedDate: null,
    status: 'pending',
    statusText: 'Expiré',
    statusClass: 'bg-red-100 text-red-700'
  }
])

// Fonctions utilitaires pour les dates
const isExpired = (dueDate) => {
  if (!dueDate) return false
  const now = new Date()
  return dueDate.getTime() < now.getTime()
}

const isUrgent = (dueDate) => {
  if (!dueDate || isExpired(dueDate)) return false
  const now = new Date()
  const timeDiff = dueDate.getTime() - now.getTime()
  const hoursDiff = timeDiff / (1000 * 3600)
  return hoursDiff <= 24
}

const getTimeLeft = (dueDate) => {
  if (!dueDate) return 'Date non définie'

  const now = new Date()
  const timeDiff = dueDate.getTime() - now.getTime()

  if (timeDiff <= 0) return 'Dépassé'

  const days = Math.floor(timeDiff / (1000 * 3600 * 24))
  const hours = Math.floor((timeDiff % (1000 * 3600 * 24)) / (1000 * 3600))

  if (days > 0) return `${days} jour${days > 1 ? 's' : ''} et ${hours}h`
  if (hours > 0) return `${hours} heure${hours > 1 ? 's' : ''}`
  return 'Moins d\'une heure'
}

const getProgress = (publishedDate, dueDate) => {
  if (!publishedDate || !dueDate) return 0

  const now = new Date()
  const totalTime = dueDate.getTime() - publishedDate.getTime()
  const elapsedTime = now.getTime() - publishedDate.getTime()

  if (elapsedTime <= 0) return 0
  if (elapsedTime >= totalTime) return 100

  return Math.round((elapsedTime / totalTime) * 100)
}

const formatDate = (date) => {
  if (!date) return 'N/A'
  return date.toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  })
}

const getUrgencyClass = (dueDate) => {
  if (!dueDate) return 'text-gray-500'

  if (isUrgent(dueDate)) return 'text-red-500 font-semibold'

  const now = new Date()
  const timeDiff = dueDate.getTime() - now.getTime()
  const daysDiff = timeDiff / (1000 * 3600 * 24)

  if (daysDiff <= 3) return 'text-orange-500'
  return 'text-gray-500'
}

// Computed properties avec calcul dynamique
const urgentAssignments = computed(() => {
  return filteredAssignments.value.filter(assignment =>
    assignment.status === 'pending' && isUrgent(assignment.dueDate)
  )
})

const activeAssignments = computed(() => {
  return filteredAssignments.value.filter(assignment =>
    assignment.status === 'pending' && !isUrgent(assignment.dueDate) && !isExpired(assignment.dueDate)
  )
})

const submittedAssignments = computed(() => {
  return filteredAssignments.value.filter(assignment =>
    assignment.status === 'submitted'
  )
})

const expiredAssignments = computed(() => {
  return filteredAssignments.value.filter(assignment =>
    assignment.status === 'pending' && isExpired(assignment.dueDate)
  )
})

const filteredAssignments = computed(() => {
  let filtered = assignments.value

  // Filtre par recherche
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(assignment =>
      assignment.title.toLowerCase().includes(query) ||
      assignment.subject.toLowerCase().includes(query) ||
      assignment.professor.toLowerCase().includes(query)
    )
  }

  // Filtre par statut
  if (activeStatusFilter.value !== 'all') {
    switch (activeStatusFilter.value) {
      case 'urgent':
        filtered = filtered.filter(assignment => isUrgent(assignment.dueDate) && assignment.status === 'pending')
        break
      case 'en-cours':
        filtered = filtered.filter(assignment => assignment.status === 'pending' && !isExpired(assignment.dueDate))
        break
      case 'soumis':
        filtered = filtered.filter(assignment => assignment.status === 'submitted')
        break
      case 'expires':
        filtered = filtered.filter(assignment => isExpired(assignment.dueDate))
        break
    }
  }

  // Tri
  filtered = [...filtered].sort((a, b) => {
    switch (sortBy.value) {
      case 'urgence':
        return (isUrgent(b.dueDate) ? 1 : 0) - (isUrgent(a.dueDate) ? 1 : 0)
      case 'matiere':
        return a.subject.localeCompare(b.subject)
      case 'date':
      default:
        return new Date(a.dueDate) - new Date(b.dueDate)
    }
  })

  return filtered
})

// Mise à jour des statistiques
const updateStats = () => {
  stats.value.enCours = assignments.value.filter(a => a.status === 'pending' && !isExpired(a.dueDate)).length
  stats.value.urgent = assignments.value.filter(a => isUrgent(a.dueDate) && a.status === 'pending').length
  stats.value.soumis = assignments.value.filter(a => a.status === 'submitted').length
  stats.value.expires = assignments.value.filter(a => isExpired(a.dueDate)).length
}

// Fonctions
const filterByStatus = (status) => {
  activeStatusFilter.value = status
}

const openSubmissionModal = (assignment) => {
  // Vérifier si la date est dépassée
  if (isExpired(assignment.dueDate)) {
    alert('La date limite de soumission est dépassée. Vous ne pouvez plus soumettre ce devoir.')
    return
  }

  currentAssignment.value = assignment
  selectedFile.value = null
  submissionComment.value = ''
  showSubmissionModal.value = true
}

const closeSubmissionModal = () => {
  showSubmissionModal.value = false
  currentAssignment.value = null
  selectedFile.value = null
  submissionComment.value = ''
}

const triggerFileInput = () => {
  fileInput.value?.click()
}

const handleFileSelect = (event) => {
  const file = event.target.files[0]
  if (file && file.type === 'application/pdf') {
    selectedFile.value = file
  } else {
    alert('Veuillez sélectionner un fichier PDF')
  }
}

const handleFileDrop = (event) => {
  event.preventDefault()
  const file = event.dataTransfer.files[0]
  if (file && file.type === 'application/pdf') {
    selectedFile.value = file
  } else {
    alert('Veuillez déposer un fichier PDF')
  }
}

const removeFile = () => {
  selectedFile.value = null
  if (fileInput.value) {
    fileInput.value.value = ''
  }
}

const submitAssignment = () => {
  if (!selectedFile.value) return

  // Simulation de soumission
  const assignment = assignments.value.find(a => a.id === currentAssignment.value.id)
  if (assignment) {
    assignment.status = 'submitted'
    assignment.submittedDate = new Date()
    assignment.statusText = 'Soumis'
    assignment.statusClass = 'bg-green-100 text-green-700'
  }

  alert('Devoir soumis avec succès!')
  closeSubmissionModal()
  updateStats()
}

const viewSubmission = (assignment) => {
  console.log('Voir la soumission:', assignment)
}

// Initialisation
onMounted(() => {
  updateStats()
})
</script>

<style>
@import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css');
</style>
