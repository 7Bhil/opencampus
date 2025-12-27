<template>
  <NavbarUser>
    <div class="min-h-screen bg-gray-50">
      <!-- En-tête avec titre et actions principales -->
      <div class="bg-white shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
              <h1 class="text-2xl font-bold text-gray-900">
                Tableau de bord étudiant
              </h1>
              <p class="mt-1 text-base text-gray-600">
                Bienvenue, {{ $page.props.auth.user.name }}
              </p>
            </div>
            <div class="flex flex-wrap gap-3">
              <Link
                :href="route('etudiant.devoirs.index')"
                class="inline-flex items-center px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors shadow-sm"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Tous les devoirs
              </Link>
              <Link
                :href="route('etudiant.cours.index')"
                class="inline-flex items-center px-4 py-2.5 border border-transparent rounded-lg text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-sm"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                Parcourir les cours
              </Link>
            </div>
          </div>
        </div>
      </div>

      <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Section des indicateurs clés -->
        <div class="mb-10">
          <h2 class="text-lg font-semibold text-gray-900 mb-6">Aperçu de votre activité</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow">
              <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-indigo-50 rounded-lg flex items-center justify-center">
                  <svg class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                  </svg>
                </div>
                <div class="ml-5">
                  <p class="text-sm font-medium text-gray-500">Cours suivis</p>
                  <p class="mt-1 text-2xl font-semibold text-gray-900">{{ stats.cours_disponibles || 0 }}</p>
                </div>
              </div>
              <div class="mt-5">
                <Link
                  :href="route('etudiant.cours.index')"
                  class="text-sm font-medium text-indigo-600 hover:text-indigo-800 flex items-center"
                >
                  Consulter
                  <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                  </svg>
                </Link>
              </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow">
              <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-emerald-50 rounded-lg flex items-center justify-center">
                  <svg class="w-6 h-6 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                  </svg>
                </div>
                <div class="ml-5">
                  <p class="text-sm font-medium text-gray-500">Devoirs soumis</p>
                  <p class="mt-1 text-2xl font-semibold text-gray-900">{{ stats.devoirs_soumis || 0 }}</p>
                </div>
              </div>
              <div class="mt-5">
                <Link
                  :href="route('etudiant.devoirs.index')"
                  class="text-sm font-medium text-emerald-600 hover:text-emerald-800 flex items-center"
                >
                  Voir historique
                  <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                  </svg>
                </Link>
              </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow">
              <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-amber-50 rounded-lg flex items-center justify-center">
                  <svg class="w-6 h-6 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                  </svg>
                </div>
                <div class="ml-5">
                  <p class="text-sm font-medium text-gray-500">Points obtenus</p>
                  <p class="mt-1 text-2xl font-semibold text-gray-900">{{ stats.points_gagnes || 0 }}</p>
                </div>
              </div>
              <div class="mt-5">
                <span class="text-sm text-gray-500">Score total cumulé</span>
              </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow">
              <div class="flex items-center">
                <div :class="stats.devoirs_en_retard > 0 ? 'bg-red-50' : 'bg-gray-50'"
                     class="flex-shrink-0 w-12 h-12 rounded-lg flex items-center justify-center">
                  <svg class="w-6 h-6" :class="stats.devoirs_en_retard > 0 ? 'text-red-600' : 'text-gray-400'" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                  </svg>
                </div>
                <div class="ml-5">
                  <p class="text-sm font-medium text-gray-500">En retard</p>
                  <p class="mt-1 text-2xl font-semibold"
                     :class="stats.devoirs_en_retard > 0 ? 'text-red-600' : 'text-gray-900'">
                    {{ stats.devoirs_en_retard || 0 }}
                  </p>
                </div>
              </div>
              <div class="mt-5">
                <span v-if="stats.devoirs_en_retard > 0"
                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                  <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-red-400" fill="currentColor" viewBox="0 0 8 8">
                    <circle cx="4" cy="4" r="3"/>
                  </svg>
                  Action requise
                </span>
                <span v-else class="text-sm text-gray-500">
                  À jour
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Contenu principal -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Colonne principale -->
          <div class="lg:col-span-2 space-y-8">
            <!-- Section des devoirs en cours -->
            <section>
              <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Devoirs en cours</h2>
                <Link
                  :href="route('etudiant.devoirs.index')"
                  class="text-sm font-medium text-indigo-600 hover:text-indigo-800 flex items-center"
                >
                  Tout voir
                  <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                  </svg>
                </Link>
              </div>

              <div v-if="devoirs_a_rendre?.length" class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="overflow-hidden">
                  <div v-for="devoir in devoirs_a_rendre" :key="devoir.id"
                       class="px-6 py-5 border-b border-gray-100 last:border-b-0 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start justify-between">
                      <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3 mb-2">
                          <h3 class="text-base font-semibold text-gray-900">{{ devoir.titre }}</h3>
                          <span v-if="devoir.statut_urgence === 'urgent' || devoir.statut_urgence === 'tres_urgent'"
                                :class="{
                                  'bg-red-50 text-red-700 border border-red-100': devoir.statut_urgence === 'tres_urgent',
                                  'bg-orange-50 text-orange-700 border border-orange-100': devoir.statut_urgence === 'urgent'
                                }"
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                            {{ getUrgenceText(devoir.statut_urgence) }}
                          </span>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">{{ devoir.matiere }}</p>

                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                          <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>{{ devoir.professeur }}</span>
                          </div>

                          <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>Échéance : {{ formatDate(devoir.date_limite) }}</span>
                          </div>

                          <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span class="font-medium text-gray-700">{{ devoir.points }} points</span>
                          </div>
                        </div>
                      </div>

                      <div class="ml-6 flex-shrink-0">
                        <Link
                          :href="route('etudiant.devoirs.show', devoir.id)"
                          class="inline-flex items-center px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-sm"
                        >
                          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                          </svg>
                          Soumettre
                        </Link>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div v-else class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                <div class="max-w-md mx-auto">
                  <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                  </div>
                  <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun devoir en attente</h3>
                  <p class="text-gray-600 mb-4">Vous avez terminé tous vos devoirs actuels.</p>
                  <Link
                    :href="route('etudiant.cours.index')"
                    class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-800"
                  >
                    Explorer les cours disponibles
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                  </Link>
                </div>
              </div>
            </section>

            <!-- Section des devoirs en retard -->
            <section v-if="devoirs_en_retard?.length">
              <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                  <div class="w-2 h-6 bg-red-500 rounded"></div>
                  <h2 class="text-lg font-semibold text-gray-900">Devoirs en retard</h2>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                  {{ devoirs_en_retard.length }} en attente
                </span>
              </div>

              <div class="bg-white rounded-xl border border-red-200 shadow-sm">
                <div class="px-6 py-4 bg-red-50 border-b border-red-100">
                  <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm font-medium text-red-800">
                      Ces devoirs ont dépassé leur date limite de soumission
                    </p>
                  </div>
                </div>

                <div class="divide-y divide-gray-100">
                  <div v-for="devoir in devoirs_en_retard" :key="devoir.id" class="px-6 py-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start justify-between">
                      <div class="flex-1">
                        <h3 class="text-base font-semibold text-gray-900 mb-1">{{ devoir.titre }}</h3>
                        <p class="text-sm text-gray-600 mb-2">{{ devoir.matiere }}</p>
                        <div class="flex flex-wrap items-center gap-3 text-sm">
                          <span class="text-gray-500">{{ devoir.professeur }}</span>
                          <span class="text-red-600 font-medium">Retard : {{ devoir.jours_retard }} jour(s)</span>
                          <span class="text-gray-500">Échéance : {{ formatDate(devoir.date_limite) }}</span>
                        </div>
                      </div>
                      <div class="ml-6 flex-shrink-0">
                        <Link
                          :href="route('etudiant.devoirs.show', devoir.id)"
                          class="inline-flex items-center px-4 py-2.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors shadow-sm"
                        >
                          Soumettre maintenant
                        </Link>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </section>
          </div>

          <!-- Sidebar -->
          <div class="space-y-8">
            <!-- Cours récents -->
            <section>
              <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Cours récents</h2>
                <Link
                  :href="route('etudiant.cours.index')"
                  class="text-sm font-medium text-indigo-600 hover:text-indigo-800 flex items-center"
                >
                  Tout voir
                  <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                  </svg>
                </Link>
              </div>

              <div v-if="cours_recents?.length" class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="overflow-hidden">
                  <div v-for="cours in cours_recents" :key="cours.id"
                       class="px-6 py-5 border-b border-gray-100 last:border-b-0 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start justify-between gap-3 mb-3">
                      <h3 class="text-sm font-semibold text-gray-900 flex-1">{{ cours.titre }}</h3>
                      <span v-if="cours.est_payant"
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        {{ cours.prix }} €
                      </span>
                      <span v-else
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                        Gratuit
                      </span>
                    </div>
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ cours.description_courte }}</p>
                    <div class="flex items-center justify-between">
                      <span class="text-sm text-gray-500">{{ cours.professeur }}</span>
                      <Link
                        :href="route('etudiant.cours.show', cours.id)"
                        class="text-sm font-medium text-indigo-600 hover:text-indigo-800 flex items-center"
                      >
                        Accéder
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                      </Link>
                    </div>
                  </div>
                </div>
              </div>

              <div v-else class="bg-white rounded-xl border border-gray-200 p-8 text-center">
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                  <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                  </svg>
                </div>
                <p class="text-sm text-gray-600">Aucun cours disponible</p>
              </div>
            </section>

            <!-- Dernières soumissions -->
            <section>
              <h2 class="text-lg font-semibold text-gray-900 mb-4">Dernières soumissions</h2>

              <div v-if="dernieres_soumissions?.length" class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="overflow-hidden">
                  <div v-for="soumission in dernieres_soumissions" :key="soumission.id"
                       class="px-6 py-4 border-b border-gray-100 last:border-b-0">
                    <div class="flex items-start justify-between gap-2 mb-2">
                      <h3 class="text-sm font-semibold text-gray-900 flex-1">{{ soumission.devoir_titre }}</h3>
                      <span :class="getStatutClass(soumission.statut)"
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                        {{ formatStatut(soumission.statut) }}
                      </span>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">{{ soumission.matiere }}</p>
                    <div class="flex items-center justify-between text-sm">
                      <span class="text-gray-500">{{ formatDate(soumission.date_soumission) }}</span>
                      <span v-if="soumission.note !== null" class="font-semibold text-emerald-600">
                        {{ soumission.note }}/20
                      </span>
                      <span v-else class="text-gray-400">En attente</span>
                    </div>
                  </div>
                </div>
              </div>

              <div v-else class="bg-white rounded-xl border border-gray-200 p-8 text-center">
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                  <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                  </svg>
                </div>
                <p class="text-sm text-gray-600 mb-3">Aucune soumission récente</p>
                <Link
                  :href="route('etudiant.devoirs.index')"
                  class="text-sm font-medium text-indigo-600 hover:text-indigo-800 inline-flex items-center"
                >
                  Voir les devoirs
                  <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                  </svg>
                </Link>
              </div>
            </section>

            <!-- Actions rapides -->
            <section>
              <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions rapides</h2>
              <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                <div class="grid grid-cols-2 gap-3">
                  <Link
                    :href="route('etudiant.upload')"
                    class="flex flex-col items-center justify-center p-4 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors group"
                  >
                    <div class="w-10 h-10 bg-indigo-50 rounded-full flex items-center justify-center mb-2 group-hover:bg-indigo-100 transition-colors">
                      <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                      </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Uploader fichier</span>
                  </Link>

                  <Link
                    :href="route('etudiant.notifications')"
                    class="flex flex-col items-center justify-center p-4 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors group"
                  >
                    <div class="w-10 h-10 bg-emerald-50 rounded-full flex items-center justify-center mb-2 group-hover:bg-emerald-100 transition-colors">
                      <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                      </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Notifications</span>
                  </Link>

                  <Link
                    :href="route('etudiant.premium.index')"
                    class="flex flex-col items-center justify-center p-4 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors group"
                  >
                    <div class="w-10 h-10 bg-amber-50 rounded-full flex items-center justify-center mb-2 group-hover:bg-amber-100 transition-colors">
                      <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                      </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Premium</span>
                  </Link>

                  <Link
                    :href="route('etudiant.settings')"
                    class="flex flex-col items-center justify-center p-4 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors group"
                  >
                    <div class="w-10 h-10 bg-purple-50 rounded-full flex items-center justify-center mb-2 group-hover:bg-purple-100 transition-colors">
                      <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                      </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Paramètres</span>
                  </Link>
                </div>
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

const getUrgenceText = (statut) => ({
  expire: 'Expiré',
  tres_urgent: 'Très urgent',
  urgent: 'Urgent',
  normal: 'Normal'
}[statut] || '')

const getStatutClass = (statut) => {
  const classes = {
    'en_attente': 'bg-amber-100 text-amber-800',
    'corrige': 'bg-emerald-100 text-emerald-800',
    'refuse': 'bg-red-100 text-red-800'
  }
  return classes[statut] || 'bg-gray-100 text-gray-800'
}

const formatStatut = (statut) => {
  const statuts = {
    'en_attente': 'En attente',
    'corrige': 'Corrigé',
    'refuse': 'Refusé'
  }
  return statuts[statut] || statut
}

const formatDate = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return date.toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'short',
    year: 'numeric'
  })
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
