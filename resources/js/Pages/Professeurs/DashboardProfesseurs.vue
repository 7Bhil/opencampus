<template>
    <ProfesseurLayout>
        <!-- Section de bienvenue -->
        <div class="welcome-section bg-white rounded-2xl shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
                        Bienvenue, {{ auth.user.name }}
                        <span class="text-3xl">👋</span>
                    </h1>
                    <p class="text-gray-600 mt-2">Tableau de bord professeur</p>
                </div>
                <div class="hidden md:block">
                    <span class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded-full text-sm font-medium">
                        Professeur
                    </span>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="stats-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 my-8">
                <div class="stat-card bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl">
                    <div class="text-4xl font-bold text-blue-600 mb-2">
                        {{ stats.total_cours || 0 }}
                    </div>
                    <div class="text-gray-600 text-sm font-medium">Cours publiés</div>
                </div>

                <div class="stat-card bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl">
                    <div class="text-4xl font-bold text-purple-600 mb-2">
                        {{ stats.total_devoirs || 0 }}
                    </div>
                    <div class="text-gray-600 text-sm font-medium">Devoirs créés</div>
                </div>

                <div class="stat-card bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl">
                    <div class="text-4xl font-bold text-green-600 mb-2">
                        {{ stats.total_etudiants || 0 }}
                    </div>
                    <div class="text-gray-600 text-sm font-medium">Étudiants</div>
                </div>

                <div class="stat-card bg-gradient-to-br from-orange-50 to-orange-100 p-6 rounded-xl">
                    <div class="text-4xl font-bold text-orange-600 mb-2">
                        {{ stats.a_corriger || 0 }}
                    </div>
                    <div class="text-gray-600 text-sm font-medium">À corriger</div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="action-buttons flex flex-wrap gap-4">
                <Link
                    :href="route('cours.create')"
                    class="btn-primary flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-lg hover:shadow-lg transition-all duration-300"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Nouveau cours
                </Link>

                <Link
                    :href="route('professeur.devoirs.create')"
                    class="btn-secondary flex items-center gap-2 px-6 py-3 bg-white text-gray-700 font-semibold rounded-lg border-2 border-gray-200 hover:border-indigo-500 hover:bg-indigo-50 transition-all duration-300"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Créer un devoir
                </Link>
            </div>
        </div>

        <!-- Section devoirs récents -->
        <div class="section bg-white rounded-2xl shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Dernières soumissions
                </h2>
                <Link
                    :href="route('professeur.devoirs.index')"
                    class="text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center gap-2"
                >
                    Voir tous
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </Link>
            </div>

            <div v-if="devoirsRecents && devoirsRecents.length > 0" class="space-y-4">
                <div v-for="soumission in devoirsRecents" :key="soumission.id" class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold">
                                    {{ soumission.etudiant?.charAt(0) || 'E' }}
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ soumission.etudiant || 'Étudiant' }}</h3>
                                    <p class="text-sm text-gray-500">{{ soumission.filiere || 'Non spécifié' }}</p>
                                </div>
                            </div>
                            <h4 class="font-medium text-gray-800">{{ soumission.titre || 'Devoir sans titre' }}</h4>
                            <p class="text-sm text-gray-600 mt-1">{{ soumission.matiere || '' }}</p>
                        </div>
                        <div class="text-right">
                            <span :class="{
                                'bg-yellow-100 text-yellow-800': soumission.statut === 'À corriger',
                                'bg-green-100 text-green-800': soumission.statut === 'Corrigé',
                                'bg-red-100 text-red-800': soumission.statut === 'En retard'
                            }" class="px-3 py-1 rounded-full text-xs font-semibold">
                                {{ soumission.statut || 'En attente' }}
                            </span>
                            <p class="text-sm text-gray-500 mt-2">{{ soumission.date }}</p>
                        </div>
                    </div>
                    <div class="flex justify-end mt-4">
                        <Link
                            :href="route('professeur.devoirs.show', soumission.devoir_id)"
                            class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors"
                        >
                            Voir la soumission
                        </Link>
                    </div>
                </div>
            </div>
            <div v-else class="text-center py-8">
                <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-gray-500 mt-2">Aucune soumission pour le moment</p>
            </div>
        </div>

        <!-- Section cours récents -->
        <div class="section bg-white rounded-2xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Mes cours récents
                </h2>
                <Link
                    :href="route('professeur.mes-cours')"
                    class="text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center gap-2"
                >
                    Voir tous
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </Link>
            </div>

            <div v-if="coursRecents && coursRecents.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="cours in coursRecents" :key="cours.id" class="border border-gray-200 rounded-xl p-5 hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-bold text-gray-900 text-lg">{{ cours.titre }}</h3>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded">
                                    {{ cours.type || 'Cours' }}
                                </span>
                                <span class="text-xs text-gray-500">{{ cours.filiere || 'Toutes filières' }}</span>
                            </div>
                        </div>
                        <span class="text-xs text-gray-500">{{ cours.date }}</span>
                    </div>

                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                        {{ cours.description || 'Aucune description' }}
                    </p>

                    <div class="flex justify-between items-center">
                        <div class="flex gap-4 text-sm text-gray-500">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                </svg>
                                {{ cours.likes || 0 }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                {{ cours.commentaires || 0 }}
                            </span>
                        </div>
                        <Link
                            :href="route('professeur.cours.show', cours.cours_id)"
                            class="px-3 py-1 text-sm text-blue-600 hover:text-blue-800 font-medium"
                        >
                            Voir
                        </Link>
                    </div>
                </div>
            </div>
            <div v-else class="text-center py-8">
                <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <p class="text-gray-500 mt-2">Aucun cours publié pour le moment</p>
                <Link
                    :href="route('cours.create')"
                    class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors"
                >
                    Créer votre premier cours
                </Link>
            </div>
        </div>
    </ProfesseurLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import ProfesseurLayout from '../../Layouts/NavbarProfesseurs.vue'

// Props avec des valeurs par défaut sécurisées
const props = defineProps({
    stats: {
        type: Object,
        default: () => ({
            total_cours: 0,
            total_devoirs: 0,
            total_etudiants: 0,
            a_corriger: 0
        })
    },
    devoirsRecents: {
        type: Array,
        default: () => []
    },
    coursRecents: {
        type: Array,
        default: () => []
    },
    auth: {
        type: Object,
        default: () => ({ user: { name: '' } })
    }
})

// Raccourci pour auth.user
const auth = props.auth
</script>

<style scoped>
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

.btn-primary {
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
}

.btn-secondary {
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }

    .action-buttons {
        flex-direction: column;
    }

    .btn-primary, .btn-secondary {
        width: 100%;
        justify-content: center;
    }
}
</style>
