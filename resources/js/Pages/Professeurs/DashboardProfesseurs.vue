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

            <!-- Statistiques RÉELLES avec sécurité -->
            <div class="stats-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 my-8">
                <div class="stat-card bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl">
                    <div class="text-4xl font-bold text-blue-600 mb-2">{{ stats?.total_cours || 0 }}</div>
                    <div class="text-gray-600 text-sm font-medium">Cours publiés</div>
                </div>

                <div class="stat-card bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl">
                    <div class="text-4xl font-bold text-purple-600 mb-2">{{ stats?.total_devoirs || 0 }}</div>
                    <div class="text-gray-600 text-sm font-medium">Devoirs assignés</div>
                </div>

                <div class="stat-card bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl">
                    <div class="text-4xl font-bold text-green-600 mb-2">{{ stats?.total_etudiants || 0 }}</div>
                    <div class="text-gray-600 text-sm font-medium">Étudiants actifs</div>
                </div>

                <div class="stat-card bg-gradient-to-br from-orange-50 to-orange-100 p-6 rounded-xl">
                    <div class="text-4xl font-bold text-orange-600 mb-2">{{ stats?.a_corriger || 0 }}</div>
                    <div class="text-gray-600 text-sm font-medium">À corriger</div>
                </div>
            </div>

            <!-- ... reste du code inchangé ... -->
        </div>

        <!-- ... reste du template ... -->
    </ProfesseurLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import ProfesseurLayout from '../../Layouts/NavbarProfesseurs.vue'

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
    }
})
</script>
