<!-- resources/js/Pages/Admin/Cours/Moderation.vue -->
<template>
    <AdminLayout :cours-a-moderer-count="cours.length">
        <template #header>
            Cours à modérer ({{ cours.length }})
        </template>

        <div v-if="cours.length === 0" class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun cours à modérer</h3>
            <p class="mt-1 text-sm text-gray-500">Tous les cours ont été traités.</p>
        </div>

        <div v-else class="space-y-6">
            <div v-for="c in cours" :key="c.id" class="bg-white shadow rounded-lg overflow-hidden border-2 border-yellow-200">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ c.titre }}</h3>
                            <p class="text-sm text-gray-600">Soumis par : {{ c.auteur?.name || 'Utilisateur inconnu' }} ({{ c.auteur?.email || 'Email non disponible' }})</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                En attente depuis {{ timeSince(c.created_at) }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Infos du cours -->
                        <div class="space-y-2">
                            <div class="flex">
                                <span class="text-sm font-medium text-gray-700 w-24">Matière :</span>
                                <span class="text-sm text-gray-900">{{ c.matiere }}</span>
                            </div>
                            <div class="flex">
                                <span class="text-sm font-medium text-gray-700 w-24">Catégorie :</span>
                                <span class="text-sm text-gray-900">{{ c.categorie }}</span>
                            </div>
                            <div class="flex">
                                <span class="text-sm font-medium text-gray-700 w-24">Type :</span>
                                <span class="text-sm text-gray-900">
                                    {{ c.est_payant ? 'Payant (' + c.prix + ' f)' : 'Gratuit' }}
                                </span>
                            </div>
                            <div class="flex">
                                <span class="text-sm font-medium text-gray-700 w-24">Fichier :</span>
                                <a :href="route('cours.download', c.id)"
                                   class="text-blue-600 hover:text-blue-900 text-sm"
                                   target="_blank">
                                    📎 {{ getFileName(c.fichier_path) }}
                                </a>
                            </div>
                        </div>

                        <!-- Description et stats -->
                        <div>
                            <div class="mb-3">
                                <span class="text-sm font-medium text-gray-700">Description :</span>
                                <p class="text-sm text-gray-900 mt-1 bg-gray-50 p-2 rounded">
                                    {{ c.description || 'Aucune description fournie' }}
                                </p>
                            </div>
                            <div class="text-xs text-gray-500">
                                Soumis le : {{ formatDate(c.created_at) }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions de modération -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div v-if="!showRejectForm[c.id]" class="flex justify-end space-x-3">
                        <button @click="showRejectReason(c.id)"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Rejeter
                        </button>
                        <button @click="approuverCours(c.id)"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Approuver
                        </button>
                    </div>

                    <!-- Formulaire de rejet avec raison -->
                    <div v-if="showRejectForm[c.id]" class="mt-4 p-4 bg-red-50 rounded-lg border border-red-200">
                        <h4 class="text-sm font-medium text-red-800 mb-2">Indiquez la raison du rejet :</h4>
                        <textarea v-model="rejectReasons[c.id]"
                                  rows="3"
                                  placeholder="Ex: Contenu inapproprié, qualité insuffisante, droits d'auteur..."
                                  class="w-full px-3 py-2 border border-red-300 rounded-md focus:outline-none focus:ring-red-500 focus:border-red-500 text-sm">
                        </textarea>
                        <div class="mt-3 flex justify-end space-x-3">
                            <button @click="cancelReject(c.id)"
                                    class="px-3 py-1 text-sm border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                                Annuler
                            </button>
                            <button @click="rejeterCours(c.id)"
                                    :disabled="rejeterForm.processing"
                                    class="px-3 py-1 text-sm border border-transparent rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span v-if="rejeterForm.processing">Traitement...</span>
                                <span v-else>Confirmer le rejet</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '../../../Layouts/AdminLayout.vue'

const props = defineProps({
    cours: {
        type: Array,
        required: true,
        default: () => []
    }
})

const showRejectForm = reactive({})
const rejectReasons = reactive({})

// Forms séparés pour éviter les conflits
const approbationForm = useForm({})
const rejeterForm = useForm({
    raison_rejet: ''
})

const showRejectReason = (coursId) => {
    showRejectForm[coursId] = true
    rejectReasons[coursId] = ''
}

const cancelReject = (coursId) => {
    showRejectForm[coursId] = false
    rejectReasons[coursId] = ''
}

const approuverCours = (coursId) => {
    if (confirm('Approuver ce cours ? Il sera publié sur la marketplace.')) {
        approbationForm.post(route('admin.cours.approuver', coursId))
    }
}

const rejeterCours = (coursId) => {
    const raison = rejectReasons[coursId]?.trim()

    if (!raison || raison.length < 10) {
        alert('Veuillez indiquer une raison valide (minimum 10 caractères).')
        return
    }

    if (confirm(`Rejeter ce cours avec la raison : "${raison}" ?`)) {
        // Réinitialise et configure le form
        rejeterForm.raison_rejet = raison
        rejeterForm.transform((data) => ({
            raison_rejet: data.raison_rejet
        })).post(route('admin.cours.rejeter', coursId), {
            onSuccess: () => {
                // Supprime le cours de la liste
                const index = props.cours.findIndex(c => c.id === coursId)
                if (index !== -1) {
                    props.cours.splice(index, 1)
                }
                // Reset
                rejectReasons[coursId] = ''
                showRejectForm[coursId] = false
                rejeterForm.reset()
            },
            onError: (errors) => {
                alert('Erreur: ' + (errors.raison_rejet || errors.message || 'Erreur lors du rejet'))
            }
        })
    }
}

const getFileName = (path) => {
    if (!path) return 'Fichier'
    return path.split('/').pop()
}

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}

const timeSince = (dateString) => {
    const date = new Date(dateString)
    const now = new Date()
    const diffMs = now - date
    const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24))

    if (diffDays === 0) {
        const diffHours = Math.floor(diffMs / (1000 * 60 * 60))
        return diffHours === 0 ? 'moins d\'une heure' : `${diffHours} h`
    } else if (diffDays === 1) {
        return '1 jour'
    } else {
        return `${diffDays} jours`
    }
}
</script>
