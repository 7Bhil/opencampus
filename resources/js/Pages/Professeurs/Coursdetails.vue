<!-- resources/js/Pages/Professeurs/Cours/Show.vue -->
<template>
    <ProfesseurLayout>
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <!-- Bouton retour -->
                <div class="mb-6">
                    <Link
                        :href="route('professeurs.cours.index')"
                        class="text-blue-600 hover:text-blue-800 flex items-center"
                    >
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Retour à mes cours
                    </Link>
                </div>

                <!-- En-tête -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">{{ cours.titre }}</h1>
                            <div class="flex items-center mt-2 space-x-4">
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                    {{ cours.categorie }}
                                </span>
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                    {{ cours.est_payant ? 'Payant' : 'Gratuit' }}
                                </span>
                                <span v-if="cours.est_payant" class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                                    {{ cours.prix }} f
                                </span>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <Link
                                :href="route('professeurs.cours.edit', cours.id)"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
                            >
                                Modifier
                            </Link>
                        </div>
                    </div>

                    <div class="mt-6">
                        <p class="text-gray-700">{{ cours.description }}</p>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8 pt-6 border-t">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900">{{ cours.likes_count }}</div>
                            <div class="text-gray-600">Likes</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900">{{ cours.telechargements_count }}</div>
                            <div class="text-gray-600">Téléchargements</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900">{{ cours.nombre_vues }}</div>
                            <div class="text-gray-600">Vues</div>
                        </div>
                    </div>
                </div>

                <!-- Fichier -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Fichier du cours</h2>
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <svg class="h-10 w-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <div class="ml-4">
                                <p class="font-medium text-gray-900">{{ getFileName(cours.fichier_path) }}</p>
                                <p class="text-sm text-gray-500">Téléchargé {{ cours.telechargements_count }} fois</p>
                            </div>
                        </div>
                        <a
                            :href="route('cours.download', cours.id)"
                            target="_blank"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg"
                        >
                            Télécharger
                        </a>
                    </div>
                </div>

                <!-- Graphique des téléchargements (simplifié) -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Activité récente</h2>
                    <div class="space-y-4">
                        <div v-for="telechargement in telechargementsRecent" :key="telechargement.id"
                             class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <img :src="telechargement.user.avatar || 'https://ui-avatars.com/api/?name=' + telechargement.user.name"
                                     class="h-8 w-8 rounded-full">
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ telechargement.user.name }}</p>
                                    <p class="text-xs text-gray-500">a téléchargé le cours</p>
                                </div>
                            </div>
                            <span class="text-sm text-gray-500">
                                {{ formatDate(telechargement.created_at) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </ProfesseurLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import ProfesseurLayout from '../../Layouts/NavbarProfesseurs.vue'

const props = defineProps({
    cours: Object,
    telechargementsRecent: Array
})

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'short',
        hour: '2-digit',
        minute: '2-digit'
    })
}

const getFileName = (path) => {
    return path.split('/').pop()
}
</script>
