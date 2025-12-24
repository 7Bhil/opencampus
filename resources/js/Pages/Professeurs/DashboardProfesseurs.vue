<template>
    <ProfesseurLayout>
        <!-- Section de bienvenue -->
        <div class="welcome-section bg-white rounded-2xl shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
                        Bienvenue, {{ $page.props.auth.user.name }}
                        <span class="text-3xl">👋</span>
                    </h1>
                    <p class="text-gray-600 mt-2">Gérez vos cours et suivez vos étudiants</p>
                </div>
                <div class="hidden md:block">
                    <span class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded-full text-sm font-medium">
                        Professeur • {{ $page.props.auth.user.filiere || 'Toutes filières' }}
                    </span>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="stats-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 my-8">
                <div class="stat-card bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl">
                    <div class="text-4xl font-bold text-blue-600 mb-2">42</div>
                    <div class="text-gray-600 text-sm font-medium">Cours publiés</div>
                    <div class="mt-2 text-xs text-blue-500 flex items-center">
                        <span class="inline-block w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                        +3 cette semaine
                    </div>
                </div>

                <div class="stat-card bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl">
                    <div class="text-4xl font-bold text-purple-600 mb-2">18</div>
                    <div class="text-gray-600 text-sm font-medium">Devoirs assignés</div>
                    <div class="mt-2 text-xs text-purple-500 flex items-center">
                        <span class="inline-block w-3 h-3 bg-purple-500 rounded-full mr-2"></span>
                        12 à corriger
                    </div>
                </div>

                <div class="stat-card bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl">
                    <div class="text-4xl font-bold text-green-600 mb-2">156</div>
                    <div class="text-gray-600 text-sm font-medium">Étudiants actifs</div>
                    <div class="mt-2 text-xs text-green-500 flex items-center">
                        <span class="inline-block w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                        92% de participation
                    </div>
                </div>

                <div class="stat-card bg-gradient-to-br from-orange-50 to-orange-100 p-6 rounded-xl">
                    <div class="text-4xl font-bold text-orange-600 mb-2">12</div>
                    <div class="text-gray-600 text-sm font-medium">À corriger</div>
                    <div class="mt-2 text-xs text-orange-500 flex items-center">
                        <span class="inline-block w-3 h-3 bg-orange-500 rounded-full mr-2"></span>
                        Dernier délai: 2 jours
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="action-buttons flex flex-wrap gap-4">
                <Link
                    :href="coursCreateRoute"
                    class="btn-primary flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-lg hover:shadow-lg transition-all duration-300 hover:-translate-y-0.5"
                >
                    <span class="text-xl">➕</span>
                    Nouveau cours
                </Link>

                <Link
                    :href="devoirsCreateRoute"
                    class="btn-secondary flex items-center gap-2 px-6 py-3 bg-white text-gray-700 font-semibold rounded-lg border-2 border-gray-200 hover:border-indigo-500 hover:bg-indigo-50 transition-all duration-300"
                >
                    <span class="text-xl">✏️</span>
                    Créer un devoir
                </Link>

                <button class="btn-tertiary flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-all duration-300">
                    <span class="text-xl">📊</span>
                    Voir les stats
                </button>
            </div>
        </div>

        <!-- Section des devoirs soumis -->
        <div class="section bg-white rounded-2xl shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                    <span class="text-2xl">📝</span>
                    Devoirs soumis récemment
                </h2>
                <Link
                    :href="devoirsIndexRoute"
                    class="text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center gap-2"
                >
                    Voir tout
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </Link>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-4 text-gray-600 font-semibold text-sm">Étudiant</th>
                            <th class="text-left py-3 px-4 text-gray-600 font-semibold text-sm">Devoir</th>
                            <th class="text-left py-3 px-4 text-gray-600 font-semibold text-sm">Date de soumission</th>
                            <th class="text-left py-3 px-4 text-gray-600 font-semibold text-sm">Statut</th>
                            <th class="text-left py-3 px-4 text-gray-600 font-semibold text-sm">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(devoir, index) in devoirs" :key="index" class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="py-4 px-4">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold mr-3">
                                        {{ devoir.etudiant.substring(0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-800">{{ devoir.etudiant }}</div>
                                        <div class="text-xs text-gray-500">{{ devoir.filiere }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <div class="font-medium text-gray-800">{{ devoir.titre }}</div>
                                <div class="text-xs text-gray-500">{{ devoir.matiere }}</div>
                            </td>
                            <td class="py-4 px-4 text-gray-600">{{ devoir.date }}</td>
                            <td class="py-4 px-4">
                                <span :class="{
                                    'badge-warning': devoir.statut === 'À corriger',
                                    'badge-success': devoir.statut === 'Corrigé',
                                    'badge-info': devoir.statut === 'En attente'
                                }" class="badge">
                                    {{ devoir.statut }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex gap-2">
                                    <button v-if="devoir.statut === 'À corriger'" class="btn-action-primary">
                                        Corriger
                                    </button>
                                    <button v-else class="btn-action-secondary">
                                        Voir
                                    </button>
                                    <button class="btn-action-download">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section des publications récentes -->
        <div class="section bg-white rounded-2xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                    <span class="text-2xl">📚</span>
                    Mes publications récentes
                </h2>
                <Link
                    :href="coursIndexRoute"
                    class="text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center gap-2"
                >
                    Voir tout
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </Link>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="(publication, index) in publications" :key="index" class="card-publication group">
                    <div class="card-header">
                        <div>
                            <h3 class="card-title">{{ publication.titre }}</h3>
                            <div class="flex items-center gap-2 mt-1">
                                <span :class="{
                                    'badge-cours': publication.type === 'Cours',
                                    'badge-td': publication.type === 'TD',
                                    'badge-tp': publication.type === 'TP'
                                }" class="badge-type">
                                    {{ publication.type }}
                                </span>
                                <span class="text-xs text-gray-500">• {{ publication.filiere }}</span>
                            </div>
                        </div>
                        <button class="card-menu-btn">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                            </svg>
                        </button>
                    </div>

                    <p class="card-description">{{ publication.description }}</p>

                    <div class="card-meta">
                        <div class="flex items-center gap-4 text-sm text-gray-500">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ publication.date }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                {{ publication.vues }} vues
                            </span>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="card-stats">
                            <span class="flex items-center gap-1 text-gray-600">
                                <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                </svg>
                                {{ publication.likes }}
                            </span>
                            <span class="flex items-center gap-1 text-gray-600">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                {{ publication.commentaires }}
                            </span>
                        </div>
                        <div class="card-actions">
                            <Link :href="coursEditRoute(1)" class="btn-edit">Modifier</Link>
                            <button class="btn-delete">Supprimer</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </ProfesseurLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import ProfesseurLayout from '../../Layouts/NavbarProfesseurs.vue'

const page = usePage()

// Fonction helper pour générer les URLs
const generateRoute = (name, params = {}) => {
    const routes = {
        'professeur.cours.create': '/professeur/cours/create',
        'professeur.devoirs.create': '/professeur/devoirs/create',
        'professeur.devoirs.index': '/professeur/devoirs',
        'professeur.cours.index': '/professeur/cours',
        'professeur.cours.edit': (id) => `/professeur/cours/${id}/edit`,
    }

    if (typeof routes[name] === 'function') {
        return routes[name](params)
    }
    return routes[name] || '#'
}

// Routes pré-calculées
const coursCreateRoute = computed(() => generateRoute('professeur.cours.create'))
const devoirsCreateRoute = computed(() => generateRoute('professeur.devoirs.create'))
const devoirsIndexRoute = computed(() => generateRoute('professeur.devoirs.index'))
const coursIndexRoute = computed(() => generateRoute('professeur.cours.index'))
const coursEditRoute = (id) => generateRoute('professeur.cours.edit', id)

// Données temporaires (à remplacer par des props ou store)
const devoirs = [
    { etudiant: 'Sophie Laurent', filiere: 'IG', titre: 'TP3 - Algorithmie', matiere: 'Algorithmique', date: '29 Oct 2025', statut: 'À corriger' },
    { etudiant: 'Max Dubois', filiere: 'IG', titre: 'Projet Base de données', matiere: 'Bases de données', date: '29 Oct 2025', statut: 'À corriger' },
    { etudiant: 'Julie Martin', filiere: 'GC', titre: 'Analyse TCP/IP', matiere: 'Réseaux', date: '28 Oct 2025', statut: 'Corrigé' },
    { etudiant: 'Thomas Bernard', filiere: 'GE', titre: 'Compte rendu marché', matiere: 'Marketing', date: '27 Oct 2025', statut: 'En attente' },
]

const publications = [
    { titre: 'Machine Learning Avancé', type: 'Cours', filiere: 'IG', description: 'Deep Learning, CNN et RNN - Cours complet avec exemples pratiques.', date: '28 Oct 2025', vues: 234, likes: 45, commentaires: 12 },
    { titre: 'Exercices Python', type: 'TD', filiere: 'IG', description: '20 exercices corrigés sur les structures de données en Python.', date: '26 Oct 2025', vues: 178, likes: 89, commentaires: 24 },
    { titre: 'Introduction au Marketing Digital', type: 'Cours', filiere: 'GC', description: 'Stratégies digitales et analyse de marché pour débutants.', date: '24 Oct 2025', vues: 156, likes: 32, commentaires: 8 },
]
</script>

<style scoped>
/* Styles personnalisés pour le dashboard */
.welcome-section {
    background: linear-gradient(135deg, #f6f9ff 0%, #f0f4ff 100%);
}

.stat-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.badge {
    @apply px-3 py-1 rounded-full text-xs font-semibold;
}

.badge-warning {
    @apply bg-yellow-100 text-yellow-800;
}

.badge-success {
    @apply bg-green-100 text-green-800;
}

.badge-info {
    @apply bg-blue-100 text-blue-800;
}

.badge-cours {
    @apply bg-blue-100 text-blue-700;
}

.badge-td {
    @apply bg-purple-100 text-purple-700;
}

.badge-tp {
    @apply bg-green-100 text-green-700;
}

.btn-action-primary {
    @apply px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors;
}

.btn-action-secondary {
    @apply px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-300 transition-colors;
}

.btn-action-download {
    @apply p-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors;
}

.card-publication {
    @apply bg-white border border-gray-200 rounded-xl p-5 hover:shadow-lg transition-all duration-300 hover:-translate-y-1;
}

.card-header {
    @apply flex justify-between items-start mb-4;
}

.card-title {
    @apply text-lg font-bold text-gray-800;
}

.badge-type {
    @apply px-2 py-1 text-xs font-semibold rounded-full;
}

.card-menu-btn {
    @apply p-1 hover:bg-gray-100 rounded;
}

.card-description {
    @apply text-gray-600 text-sm mb-4 line-clamp-2;
}

.card-meta {
    @apply mb-4;
}

.card-footer {
    @apply flex justify-between items-center;
}

.card-stats {
    @apply flex gap-4;
}

.btn-edit {
    @apply px-3 py-1 text-sm text-blue-600 hover:text-blue-800 font-medium;
}

.btn-delete {
    @apply px-3 py-1 text-sm text-red-600 hover:text-red-800 font-medium;
}

/* Responsive */
@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }

    .action-buttons {
        flex-direction: column;
    }

    .btn-primary, .btn-secondary, .btn-tertiary {
        width: 100%;
        justify-content: center;
    }
}
</style>
