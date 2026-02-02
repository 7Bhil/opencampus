<template>
    <div class="min-h-screen bg-gray-50">
        <!-- En-tête du cours -->
        <div class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-2">
                            <span class="px-2 py-1 text-xs font-medium rounded-full"
                                  :class="cours.est_payant ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800'">
                                {{ cours.est_payant ? 'Payant' : 'Gratuit' }}
                            </span>
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                {{ cours.matiere }}
                            </span>
                        </div>

                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ cours.titre }}</h1>

                        <div class="flex items-center space-x-4 text-sm text-gray-600">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ cours.auteur.name }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ formatDate(cours.created_at) }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ cours.nombre_vues }} vues</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-4 md:mt-0 flex space-x-3">
                        <a :href="route('cours.download', cours.id)"
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Télécharger
                        </a>

                        <!-- Bouton achat si payant -->
                        <button v-if="cours.est_payant && !hasPurchased && !isOwner"
                                @click="acheterCours"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Acheter ({{ cours.prix }} f)
                        </button>

                        <!-- Boutons édition pour propriétaire/admin -->
                        <div v-if="isOwner || isAdmin" class="flex space-x-2">
                            <Link :href="route('cours.edit', cours.id)"
                                  class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Éditer
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages flash -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div v-if="$page.props.flash?.success" class="p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
                {{ $page.props.flash.success }}
            </div>
            <div v-if="$page.props.flash?.error" class="p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
                {{ $page.props.flash.error }}
            </div>
            <div v-if="$page.props.flash?.info" class="p-4 bg-blue-50 border border-blue-200 rounded-lg text-blue-800">
                {{ $page.props.flash.info }}
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Contenu principal -->
                <div class="lg:col-span-2">
                    <!-- Description -->
                    <div class="bg-white shadow rounded-lg p-6 mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Description</h2>
                        <div class="prose max-w-none">
                            <p class="text-gray-700 whitespace-pre-line">{{ cours.description || 'Aucune description fournie.' }}</p>
                        </div>
                    </div>

                    <!-- Informations détaillées -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Informations</h2>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Catégorie</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ cours.categorie }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Type</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ cours.est_payant ? 'Cours payant' : 'Cours gratuit' }}
                                    <span v-if="cours.est_payant" class="ml-2 font-semibold">{{ cours.prix }} f</span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Fichier</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ getFileName(cours.fichier_path) }}
                                    <span class="ml-2 text-gray-500">({{ getFileSize(cours.fichier_path) }})</span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Statut</dt>
                                <dd class="mt-1">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full"
                                          :class="cours.est_public ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'">
                                        {{ cours.est_public ? 'Public' : 'En attente de modération' }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Auteur -->
                    <div class="bg-white shadow rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">À propos de l'auteur</h3>
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                                    {{ cours.auteur.name.charAt(0).toUpperCase() }}
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-gray-900">{{ cours.auteur.name }}</h4>
                                <p class="text-sm text-gray-500">
                                    {{ cours.auteur.account_type === 'Professeur' ? 'Professeur' : 'Étudiant' }}
                                </p>
                                <p v-if="cours.auteur.account_type === 'Professeur'" class="text-xs text-gray-400 mt-1">
                                    Publication directe
                                </p>
                                <p v-else class="text-xs text-gray-400 mt-1">
                                    Cours vérifié par l'administration
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Statistiques -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Statistiques</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Vues</span>
                                <span class="text-sm font-medium text-gray-900">{{ cours.nombre_vues }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Achats</span>
                                <span class="text-sm font-medium text-gray-900">{{ cours.nombre_achats || 0 }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Date de publication</span>
                                <span class="text-sm font-medium text-gray-900">{{ formatShortDate(cours.created_at) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions supplémentaires -->
                    <div class="bg-white shadow rounded-lg p-6 mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
                        <div class="space-y-3">
                            <button @click="toggleLike"
                                    class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="w-5 h-5 mr-2" :class="isLiked ? 'text-red-500 fill-current' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                {{ isLiked ? 'Retirer le like' : 'Ajouter aux favoris' }}
                            </button>

                            <button v-if="isOwner"
                                    @click="confirmDelete"
                                    class="w-full flex items-center justify-center px-4 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Supprimer le cours
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, useForm, router } from '@inertiajs/vue3'

const props = defineProps({
    cours: Object,
    isOwner: Boolean,
    hasPurchased: {
        type: Boolean,
        default: false
    },
    isLiked: {
        type: Boolean,
        default: false
    }
})

const form = useForm({})
const isAdmin = computed(() => {
    return props.cours.auteur?.account_type === 'Admin' ||
           (props.$page?.props?.auth?.user?.account_type === 'Admin')
})

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    })
}

const formatShortDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('fr-FR')
}

const getFileName = (path) => {
    if (!path) return 'Document'
    const parts = path.split('/')
    return parts[parts.length - 1]
}

const getFileSize = (path) => {
    // Simuler une taille (tu peux implémenter la vraie taille plus tard)
    return '~2 MB'
}

const toggleLike = () => {
    form.post(route('etudiant.cours.toggle-like', props.cours.id), {
        preserveScroll: true,
        onSuccess: () => {
            // Le state sera mis à jour via les props
        }
    })
}

const acheterCours = () => {
    if (confirm(`Acheter ce cours pour ${props.cours.prix} f ?`)) {
        form.post(route('cours.acheter', props.cours.id))
    }
}

const confirmDelete = () => {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce cours ? Cette action est irréversible.')) {
        router.delete(route('cours.destroy', props.cours.id))
    }
}
</script>
