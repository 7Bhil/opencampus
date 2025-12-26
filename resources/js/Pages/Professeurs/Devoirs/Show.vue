<!-- resources/js/Pages/Professeurs/Devoirs/Show.vue -->
<template>
    <ProfesseurLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Bouton retour -->
                <div class="mb-6">
                    <Link
                        href="/professeur/devoirs"
                        class="text-blue-600 hover:text-blue-800 flex items-center"
                    >
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Retour aux devoirs
                    </Link>
                </div>

                <!-- En-tête du devoir -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">{{ devoir.titre }}</h1>
                            <div class="flex items-center mt-2 space-x-4">
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                    {{ devoir.matiere }}
                                </span>
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                    {{ devoir.est_actif ? 'Actif' : 'Inactif' }}
                                </span>
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                                    {{ devoir.points }} points
                                </span>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <Link
                                :href="`/professeur/devoirs/${devoir.id}/edit`"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
                            >
                                Modifier
                            </Link>
                            <button
                                @click="toggleActif"
                                :class="devoir.est_actif ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700'"
                                class="text-white px-4 py-2 rounded-lg"
                            >
                                {{ devoir.est_actif ? 'Désactiver' : 'Activer' }}
                            </button>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Description</h3>
                        <p class="text-gray-700">{{ devoir.description || 'Aucune description fournie.' }}</p>
                    </div>

                    <!-- Informations -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6 pt-6 border-t">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Date limite</h4>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ formatDate(devoir.date_limite) }}
                            </p>
                            <p class="text-sm text-gray-600">
                                {{ getTimeRemaining(devoir.date_limite) }}
                            </p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Date de création</h4>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ formatDate(devoir.created_at) }}
                            </p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Fichier énoncé</h4>
                            <div class="mt-1">
                                <a
                                    :href="`/storage/${devoir.fichier_path}`"
                                    target="_blank"
                                    class="inline-flex items-center text-blue-600 hover:text-blue-800"
                                >
                                    <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Télécharger l'énoncé
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white p-6 rounded-xl shadow">
                        <p class="text-gray-500">Soumissions totales</p>
                        <p class="text-3xl font-bold mt-2">{{ stats.total_soumissions }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow">
                        <p class="text-gray-500">À corriger</p>
                        <p class="text-3xl font-bold mt-2">{{ stats.soumissions_en_attente }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow">
                        <p class="text-gray-500">Corrigées</p>
                        <p class="text-3xl font-bold mt-2">{{ stats.soumissions_corrigees }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow">
                        <p class="text-gray-500">Moyenne</p>
                        <p class="text-3xl font-bold mt-2">
                            {{ stats.moyenne ? stats.moyenne.toFixed(2) : '0.00' }}/{{ devoir.points }}
                        </p>
                    </div>
                </div>

                <!-- Liste des soumissions -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900">Soumissions des étudiants</h2>
                        <p class="text-gray-600 mt-1">Liste des travaux rendus par les étudiants</p>
                    </div>

                    <!-- Filtres -->
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <div class="flex flex-wrap gap-4">
                            <select
                                v-model="filtreStatut"
                                class="border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500 px-4 py-2"
                            >
                                <option value="">Tous les statuts</option>
                                <option value="en_attente">En attente</option>
                                <option value="corrige">Corrigé</option>
                                <option value="rendu_en_retard">Rendu en retard</option>
                            </select>

                            <input
                                v-model="recherche"
                                type="text"
                                placeholder="Rechercher un étudiant..."
                                class="border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500 px-4 py-2 flex-grow"
                            />
                        </div>
                    </div>

                    <!-- Tableau -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Étudiant
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date de soumission
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Statut
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Note
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="soumission in filteredSoumissions" :key="soumission.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                    <span class="text-indigo-600 font-bold">
                                                        {{ soumission.etudiant.name.substring(0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ soumission.etudiant.name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ soumission.etudiant.filiere || 'Non spécifié' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ formatDate(soumission.created_at) }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ formatHeure(soumission.created_at) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span :class="getStatutClasses(soumission)" class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full">
                                            {{ getStatutText(soumission) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div v-if="soumission.note !== null" class="text-sm font-medium text-gray-900">
                                            {{ soumission.note }}/{{ devoir.points }}
                                        </div>
                                        <div v-else class="text-sm text-gray-500">
                                            Non noté
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-3">
                                            <button
                                                @click="telechargerSoumission(soumission)"
                                                class="text-blue-600 hover:text-blue-900"
                                            >
                                                Télécharger
                                            </button>
                                            <button
                                                @click="ouvrirModalCorrection(soumission)"
                                                :class="soumission.statut === 'en_attente' ? 'text-green-600 hover:text-green-900' : 'text-yellow-600 hover:text-yellow-900'"
                                            >
                                                {{ soumission.statut === 'en_attente' ? 'Corriger' : 'Modifier' }}
                                            </button>
                                            <button
                                                @click="supprimerSoumission(soumission)"
                                                class="text-red-600 hover:text-red-900"
                                            >
                                                Supprimer
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Message si aucune soumission -->
                        <div v-if="soumissions.length === 0" class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">Aucune soumission</h3>
                            <p class="mt-2 text-gray-500">Aucun étudiant n'a encore rendu ce devoir.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de correction -->
        <div v-if="modalOuvert" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-medium text-gray-900">
                        {{ soumissionSelectionnee.statut === 'en_attente' ? 'Corriger' : 'Modifier la note de' }} la soumission
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">
                        {{ soumissionSelectionnee.etudiant.name }}
                    </p>
                </div>

                <form @submit.prevent="corrigerSoumission" class="p-6">
                    <div class="mb-4">
                        <label for="note" class="block text-sm font-medium text-gray-700 mb-2">
                            Note (sur {{ devoir.points }} points)
                        </label>
                        <input
                            id="note"
                            v-model="form.note"
                            type="number"
                            step="0.01"
                            min="0"
                            :max="devoir.points"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required
                        />
                    </div>

                    <div class="mb-6">
                        <label for="commentaire" class="block text-sm font-medium text-gray-700 mb-2">
                            Commentaire
                        </label>
                        <textarea
                            id="commentaire"
                            v-model="form.commentaire"
                            rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        ></textarea>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button
                            type="button"
                            @click="fermerModal"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50"
                        >
                            Annuler
                        </button>
                        <button
                            type="submit"
                            :disabled="enCoursCorrection"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
                        >
                            <span v-if="enCoursCorrection">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                En cours...
                            </span>
                            <span v-else>Enregistrer</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </ProfesseurLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import ProfesseurLayout from '../../../Layouts/NavbarProfesseurs.vue'

const props = defineProps({
    devoir: Object,
    soumissions: Array,
    stats: Object
})

// États réactifs
const filtreStatut = ref('')
const recherche = ref('')
const modalOuvert = ref(false)
const enCoursCorrection = ref(false)
const soumissionSelectionnee = ref(null)

// Formulaire de correction
const form = ref({
    note: '',
    commentaire: ''
})

// Formatage des dates
const formatDate = (dateString) => {
    const date = new Date(dateString)
    return date.toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    })
}

const formatHeure = (dateString) => {
    const date = new Date(dateString)
    return date.toLocaleTimeString('fr-FR', {
        hour: '2-digit',
        minute: '2-digit'
    })
}

const getTimeRemaining = (dateLimite) => {
    const now = new Date()
    const limite = new Date(dateLimite)
    const diff = limite - now

    if (diff < 0) return 'Expiré'

    const days = Math.floor(diff / (1000 * 60 * 60 * 24))
    const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))

    if (days > 0) return `${days} jours restants`
    return `${hours} heures restantes`
}

// Classes CSS pour les statuts
const getStatutClasses = (soumission) => {
    const classes = {
        'en_attente': 'bg-yellow-100 text-yellow-800',
        'corrige': 'bg-green-100 text-green-800',
        'rendu_en_retard': 'bg-red-100 text-red-800',
    }
    return classes[soumission.statut] || 'bg-gray-100 text-gray-800'
}

const getStatutText = (soumission) => {
    const textes = {
        'en_attente': 'En attente',
        'corrige': 'Corrigé',
        'rendu_en_retard': 'En retard',
    }
    return textes[soumission.statut] || soumission.statut
}

// Filtrage des soumissions
const filteredSoumissions = computed(() => {
    return props.soumissions.filter(soumission => {
        // Filtre par statut
        const matchStatut = !filtreStatut.value || soumission.statut === filtreStatut.value

        // Filtre par recherche
        const matchRecherche = !recherche.value ||
            soumission.etudiant.name.toLowerCase().includes(recherche.value.toLowerCase()) ||
            (soumission.etudiant.filiere && soumission.etudiant.filiere.toLowerCase().includes(recherche.value.toLowerCase()))

        return matchStatut && matchRecherche
    })
})

// Actions
const toggleActif = () => {
    router.put(`/professeur/devoirs/${props.devoir.id}`, {
        est_actif: !props.devoir.est_actif
    }, {
        preserveScroll: true,
        onSuccess: () => {
            router.reload({ only: ['devoir'] })
        }
    })
}

const telechargerSoumission = (soumission) => {
    window.location.href = `/professeur/devoirs/${props.devoir.id}/soumissions/${soumission.id}/download`
}

const ouvrirModalCorrection = (soumission) => {
    soumissionSelectionnee.value = soumission
    form.value.note = soumission.note || ''
    form.value.commentaire = soumission.commentaire || ''
    modalOuvert.value = true
}

const fermerModal = () => {
    modalOuvert.value = false
    soumissionSelectionnee.value = null
    form.value.note = ''
    form.value.commentaire = ''
    enCoursCorrection.value = false
}

const corrigerSoumission = () => {
    if (!soumissionSelectionnee.value) return

    enCoursCorrection.value = true

    router.post(`/professeur/devoirs/${props.devoir.id}/soumissions/${soumissionSelectionnee.value.id}/corriger`, {
        note: form.value.note,
        commentaire: form.value.commentaire
    }, {
        preserveScroll: true,
        onSuccess: () => {
            fermerModal()
            router.reload({ only: ['soumissions', 'stats'] })
        },
        onError: () => {
            alert('Une erreur est survenue. Veuillez réessayer.')
        },
        onFinish: () => {
            enCoursCorrection.value = false
        }
    })
}

const supprimerSoumission = (soumission) => {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette soumission ? Cette action est irréversible.')) {
        router.delete(`/professeur/devoirs/${props.devoir.id}/soumissions/${soumission.id}`, {
            preserveScroll: true,
            onSuccess: () => {
                router.reload({ only: ['soumissions', 'stats'] })
            }
        })
    }
}
</script>
