<template>
    <AdminLayout :cours-a-moderer-count="stats.coursAModerer">
        <template #header>
            Tableau de bord
        </template>

        <!-- Statistiques principales -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Utilisateurs -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-green-600 bg-green-100 px-2 py-1 rounded-full">
                        +12%
                    </span>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Utilisateurs</p>
                    <p class="text-3xl font-bold text-gray-900">{{ stats.totalUsers }}</p>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <Link :href="route('admin.users')"
                          class="text-sm font-medium text-blue-600 hover:text-blue-700 inline-flex items-center">
                        Voir tous
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </Link>
                </div>
            </div>

            <!-- Cours -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-green-600 bg-green-100 px-2 py-1 rounded-full">
                        +8%
                    </span>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Cours total</p>
                    <p class="text-3xl font-bold text-gray-900">{{ stats.totalCours }}</p>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <Link :href="route('admin.cours')"
                          class="text-sm font-medium text-purple-600 hover:text-purple-700 inline-flex items-center">
                        Gérer les cours
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </Link>
                </div>
            </div>

            <!-- Professeurs -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-green-600 bg-green-100 px-2 py-1 rounded-full">
                        Actifs
                    </span>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Professeurs</p>
                    <p class="text-3xl font-bold text-gray-900">{{ stats.totalProfesseurs }}</p>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <Link :href="route('admin.users')"
                          class="text-sm font-medium text-green-600 hover:text-green-700 inline-flex items-center">
                        Voir tous
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </Link>
                </div>
            </div>

            <!-- Modération -->
            <div class="bg-white rounded-xl border-2 p-6 hover:shadow-lg transition-shadow"
                 :class="stats.coursAModerer > 0 ? 'border-orange-200 bg-orange-50' : 'border-gray-200'">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center"
                         :class="stats.coursAModerer > 0 ? 'bg-orange-100' : 'bg-gray-100'">
                        <svg class="w-6 h-6" :class="stats.coursAModerer > 0 ? 'text-orange-600' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span v-if="stats.coursAModerer > 0"
                          class="text-xs font-medium text-orange-600 bg-orange-100 px-2 py-1 rounded-full animate-pulse">
                        En attente
                    </span>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">À modérer</p>
                    <p class="text-3xl font-bold" :class="stats.coursAModerer > 0 ? 'text-orange-600' : 'text-gray-900'">
                        {{ stats.coursAModerer }}
                    </p>
                </div>
                <div class="mt-4 pt-4 border-t" :class="stats.coursAModerer > 0 ? 'border-orange-100' : 'border-gray-100'">
                    <Link :href="route('admin.cours-a-moderer')"
                          class="text-sm font-medium inline-flex items-center"
                          :class="stats.coursAModerer > 0 ? 'text-orange-600 hover:text-orange-700' : 'text-gray-600 hover:text-gray-700'">
                        {{ stats.coursAModerer > 0 ? 'Modérer maintenant' : 'Voir tous' }}
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </Link>
                </div>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Activités récentes -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900">Activités récentes</h2>
                            <button class="text-sm font-medium text-blue-600 hover:text-blue-700">
                                Voir tout
                            </button>
                        </div>
                    </div>

                    <div class="p-6">
                        <div v-if="recentActivities.length > 0" class="space-y-6">
                            <div v-for="(activity, index) in recentActivities" :key="index" class="flex gap-4">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center"
                                         :class="getActivityColor(activity.type)">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path v-if="activity.type === 'user'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            <path v-else-if="activity.type === 'course'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900 font-medium">{{ activity.description }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ activity.time }}</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                          :class="getActivityBadge(activity.type)">
                                        {{ activity.badge }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div v-else class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune activité</h3>
                            <p class="mt-1 text-sm text-gray-500">Les activités récentes apparaîtront ici</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques supplémentaires -->
            <div class="space-y-6">
                <!-- Actions rapides -->
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions rapides</h3>
                    <div class="space-y-3">
                        <Link :href="route('admin.users')"
                              class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:bg-gray-50 hover:border-blue-300 transition-all group">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Ajouter un utilisateur</span>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </Link>

                        <Link :href="route('admin.cours')"
                              class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:bg-gray-50 hover:border-purple-300 transition-all group">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Gérer les cours</span>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-purple-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </Link>

                        <Link :href="route('admin.cours-a-moderer')"
                              class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:bg-gray-50 hover:border-orange-300 transition-all group">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center group-hover:bg-orange-200 transition-colors">
                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Modérer les cours</span>
                            </div>
                            <span v-if="stats.coursAModerer > 0"
                                  class="inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-orange-500 rounded-full">
                                {{ stats.coursAModerer }}
                            </span>
                            <svg v-else class="w-5 h-5 text-gray-400 group-hover:text-orange-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </Link>
                    </div>
                </div>

                <!-- Statistiques système -->
                <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl p-6 text-white">
                    <h3 class="text-lg font-semibold mb-4">Système</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-blue-100">Statut</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Opérationnel
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-blue-100">Dernière sauvegarde</span>
                            <span class="text-sm font-medium">Il y a 2h</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-blue-100">Stockage utilisé</span>
                            <span class="text-sm font-medium">42%</span>
                        </div>
                    </div>
                    <div class="mt-6 pt-6 border-t border-blue-400">
                        <button class="w-full bg-white/20 hover:bg-white/30 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                            Voir les détails
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '../../Layouts/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
    stats: {
        type: Object,
        required: true,
        default: () => ({
            totalUsers: 0,
            totalCours: 0,
            totalProfesseurs: 0,
            coursAModerer: 0
        })
    },
    recentActivities: {
        type: Array,
        default: () => []
    }
})

const getActivityColor = (type) => {
    const colors = {
        user: 'bg-blue-500',
        course: 'bg-purple-500',
        moderation: 'bg-orange-500',
        default: 'bg-gray-500'
    }
    return colors[type] || colors.default
}

const getActivityBadge = (type) => {
    const badges = {
        user: 'bg-blue-100 text-blue-700',
        course: 'bg-purple-100 text-purple-700',
        moderation: 'bg-orange-100 text-orange-700',
        default: 'bg-gray-100 text-gray-700'
    }
    return badges[type] || badges.default
}
</script>
