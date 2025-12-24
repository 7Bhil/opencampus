<!-- resources/js/Pages/Professeurs/CoursShow.vue -->
<template>
    <ProfesseurLayout>
        <div class="py-12">
            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                <!-- Bouton retour -->
                <div class="mb-6">
                    <Link
                        :href="route('professeur.cours.index')"
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
                                    {{ cours.prix }} €
                                </span>
                                <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-medium">
                                    {{ cours.matiere }}
                                </span>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <Link
                                :href="route('professeur.cours.edit', cours.id)"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
                            >
                                Modifier
                            </Link>
                            <button
                                @click="supprimerCours"
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg"
                            >
                                Supprimer
                            </button>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Description</h3>
                        <p class="text-gray-700">{{ cours.description || 'Aucune description fournie.' }}</p>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8 pt-6 border-t">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-900">{{ cours.likes_count || 0 }}</div>
                            <div class="text-blue-700">Likes</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-900">{{ cours.telechargements_count || 0 }}</div>
                            <div class="text-green-700">Téléchargements</div>
                        </div>
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <div class="text-2xl font-bold text-purple-900">{{ cours.nombre_vues || 0 }}</div>
                            <div class="text-purple-700">Vues</div>
                        </div>
                        <div class="text-center p-4 bg-yellow-50 rounded-lg">
                            <div class="text-2xl font-bold text-yellow-900">
                                {{ cours.est_public ? 'Public' : 'Privé' }}
                            </div>
                            <div class="text-yellow-700">Visibilité</div>
                        </div>
                    </div>
                </div>

                <!-- Fichier -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Fichier du cours</h2>
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <svg class="h-12 w-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <div class="ml-4">
                                <p class="font-medium text-gray-900">{{ getFileName(cours.fichier_path) }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ cours.telechargements_count || 0 }} téléchargement(s) •
                                    {{ formatDate(cours.created_at) }}
                                </p>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <a
                                :href="'/storage/' + cours.fichier_path"
                                target="_blank"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
                            >
                                Télécharger
                            </a>
                            <Link
                                :href="route('professeur.cours.edit', cours.id)"
                                class="border border-blue-600 text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-lg"
                            >
                                Remplacer
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Informations -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Détails -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Détails du cours</h2>
                        <div class="space-y-4">
                            <div class="flex justify-between py-2 border-b">
                                <span class="text-gray-600">Date de création</span>
                                <span class="font-medium">{{ formatDate(cours.created_at) }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b">
                                <span class="text-gray-600">Dernière modification</span>
                                <span class="font-medium">{{ formatDate(cours.updated_at) }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b">
                                <span class="text-gray-600">Statut</span>
                                <span :class="{
                                    'text-green-600 font-medium': cours.est_public,
                                    'text-red-600 font-medium': !cours.est_public
                                }">
                                    {{ cours.est_public ? 'Public' : 'Privé' }}
                                </span>
                            </div>
                            <div class="flex justify-between py-2 border-b">
                                <span class="text-gray-600">Type</span>
                                <span :class="{
                                    'text-green-600 font-medium': !cours.est_payant,
                                    'text-yellow-600 font-medium': cours.est_payant
                                }">
                                    {{ cours.est_payant ? 'Payant' : 'Gratuit' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions rapides -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Actions rapides</h2>
                        <div class="space-y-3">
                            <Link
                                :href="route('professeur.cours.edit', cours.id)"
                                class="w-full flex items-center justify-center p-3 border border-blue-600 text-blue-600 hover:bg-blue-50 rounded-lg"
                            >
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Modifier le cours
                            </Link>
                            <button
                                @click="toggleVisibilite"
                                class="w-full flex items-center justify-center p-3 border border-purple-600 text-purple-600 hover:bg-purple-50 rounded-lg"
                            >
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                {{ cours.est_public ? 'Rendre privé' : 'Rendre public' }}
                            </button>
                            <a
                                :href="'/storage/' + cours.fichier_path"
                                target="_blank"
                                class="w-full flex items-center justify-center p-3 border border-green-600 text-green-600 hover:bg-green-50 rounded-lg"
                            >
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Télécharger le fichier
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </ProfesseurLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import ProfesseurLayout from '../../Layouts/NavbarProfesseurs.vue'

const props = defineProps({
    cours: Object
})

const formatDate = (dateString) => {
    const date = new Date(dateString)
    return date.toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}

const getFileName = (path) => {
    return path.split('/').pop()
}

const supprimerCours = () => {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce cours ? Cette action est irréversible.')) {
        router.delete(route('professeur.cours.destroy', props.cours.id))
    }
}

const toggleVisibilite = () => {
    router.put(route('professeur.cours.update', props.cours.id), {
        est_public: !props.cours.est_public
    }, {
        preserveScroll: true
    })
}
</script>
