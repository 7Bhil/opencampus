<!-- resources/js/Pages/Professeurs/Devoirs/Edit.vue -->
<template>
    <ProfesseurLayout>
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <!-- En-tête -->
                <div class="mb-8">
                    <Link
                        :href="route('professeur.devoirs.show', devoir.id)"
                        class="text-blue-600 hover:text-blue-800 flex items-center mb-4"
                    >
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Retour au devoir
                    </Link>

                    <h1 class="text-3xl font-bold text-gray-900">Modifier le devoir</h1>
                    <p class="text-gray-600 mt-2">Mettez à jour les informations de votre devoir</p>
                </div>

                <!-- Messages d'erreur -->
                <div v-if="Object.keys(errors).length > 0" class="mb-6">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">
                                    Veuillez corriger les erreurs suivantes :
                                </h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li v-for="(error, field) in errors" :key="field">
                                            {{ error }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Message de succès -->
                <div v-if="flash.success" class="mb-6">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-green-800 font-medium">{{ flash.success }}</span>
                        </div>
                    </div>
                </div>

                <!-- Formulaire -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <form @submit.prevent="submit">
                        <div class="p-6 space-y-6">
                            <!-- Titre -->
                            <div>
                                <label for="titre" class="block text-sm font-medium text-gray-700 mb-2">
                                    Titre du devoir *
                                </label>
                                <input
                                    id="titre"
                                    v-model="form.titre"
                                    type="text"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-300': errors.titre }"
                                />
                                <p v-if="errors.titre" class="mt-1 text-sm text-red-600">
                                    {{ errors.titre }}
                                </p>
                            </div>

                            <!-- Matière -->
                            <div>
                                <label for="matiere" class="block text-sm font-medium text-gray-700 mb-2">
                                    Matière *
                                </label>
                                <input
                                    id="matiere"
                                    v-model="form.matiere"
                                    type="text"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-300': errors.matiere }"
                                />
                                <p v-if="errors.matiere" class="mt-1 text-sm text-red-600">
                                    {{ errors.matiere }}
                                </p>
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Description
                                </label>
                                <textarea
                                    id="description"
                                    v-model="form.description"
                                    rows="4"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-300': errors.description }"
                                ></textarea>
                                <p v-if="errors.description" class="mt-1 text-sm text-red-600">
                                    {{ errors.description }}
                                </p>
                            </div>

                            <!-- Date limite -->
                            <div>
                                <label for="date_limite" class="block text-sm font-medium text-gray-700 mb-2">
                                    Date limite *
                                </label>
                                <input
                                    id="date_limite"
                                    v-model="form.date_limite"
                                    type="datetime-local"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-300': errors.date_limite }"
                                />
                                <p v-if="errors.date_limite" class="mt-1 text-sm text-red-600">
                                    {{ errors.date_limite }}
                                </p>
                            </div>

                            <!-- Points -->
                            <div>
                                <label for="points" class="block text-sm font-medium text-gray-700 mb-2">
                                    Points maximum *
                                </label>
                                <input
                                    id="points"
                                    v-model="form.points"
                                    type="number"
                                    min="1"
                                    max="100"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-300': errors.points }"
                                />
                                <p v-if="errors.points" class="mt-1 text-sm text-red-600">
                                    {{ errors.points }}
                                </p>
                            </div>

                            <!-- Fichier actuel -->
                            <div v-if="devoir.fichier_path">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Fichier actuel
                                </label>
                                <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                                    <div class="flex items-center">
                                        <svg class="h-8 w-8 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">
                                                Fichier joint
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ getFileName(devoir.fichier_path) }}
                                            </p>
                                        </div>
                                    </div>
                                    <a
                                        :href="`/storage/${devoir.fichier_path}`"
                                        target="_blank"
                                        class="text-blue-600 hover:text-blue-800 text-sm"
                                    >
                                        Télécharger
                                    </a>
                                </div>
                            </div>

                            <!-- Nouveau fichier -->
                            <div>
                                <label for="fichier" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ devoir.fichier_path ? 'Remplacer le fichier' : 'Joindre un fichier' }}
                                    <span class="text-gray-500 text-xs">(PDF, DOC, DOCX, TXT - Max 10MB)</span>
                                </label>
                                <input
                                    id="fichier"
                                    type="file"
                                    ref="fileInput"
                                    @change="handleFileChange"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-300': errors.fichier }"
                                    accept=".pdf,.doc,.docx,.txt"
                                />
                                <p v-if="form.fichier" class="mt-2 text-sm text-gray-600">
                                    Nouveau fichier sélectionné : {{ form.fichier.name }}
                                </p>
                                <p v-if="errors.fichier" class="mt-1 text-sm text-red-600">
                                    {{ errors.fichier }}
                                </p>
                            </div>

                            <!-- Statut actif -->
                            <div class="flex items-center">
                                <input
                                    id="est_actif"
                                    v-model="form.est_actif"
                                    type="checkbox"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                />
                                <label for="est_actif" class="ml-2 block text-sm text-gray-700">
                                    Rendre ce devoir actif (visible par les étudiants)
                                </label>
                            </div>
                        </div>

                        <!-- Pied de formulaire -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between">
                            <div>
                                <Link
                                    :href="route('professeur.devoirs.show', devoir.id)"
                                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50"
                                >
                                    Annuler
                                </Link>
                            </div>
                            <div class="flex space-x-3">
                                <button
                                    type="button"
                                    @click="supprimerDevoir"
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"
                                >
                                    Supprimer
                                </button>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <span v-if="form.processing">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Enregistrement...
                                    </span>
                                    <span v-else>Enregistrer les modifications</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Modal de confirmation de suppression -->
                <div v-if="showDeleteModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center p-4 z-50">
                    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                Confirmer la suppression
                            </h3>
                            <p class="text-gray-600 mb-6">
                                Êtes-vous sûr de vouloir supprimer le devoir "{{ devoir.titre }}" ?
                                <span class="font-semibold text-red-600 block mt-2">
                                    Attention : Cette action supprimera également toutes les soumissions associées et est irréversible.
                                </span>
                            </p>
                            <div class="flex justify-end space-x-3">
                                <button
                                    type="button"
                                    @click="showDeleteModal = false"
                                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50"
                                >
                                    Annuler
                                </button>
                                <button
                                    type="button"
                                    @click="confirmerSuppression"
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"
                                >
                                    Oui, supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </ProfesseurLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link, router, useForm } from '@inertiajs/vue3'
import ProfesseurLayout from '../../../Layouts/NavbarProfesseurs.vue'

const props = defineProps({
    devoir: Object,
    errors: {
        type: Object,
        default: () => ({})
    },
    flash: {
        type: Object,
        default: () => ({})
    }
})

const showDeleteModal = ref(false)

// Initialiser le formulaire avec les données du devoir
const form = useForm({
    titre: props.devoir.titre,
    matiere: props.devoir.matiere,
    description: props.devoir.description || '',
    date_limite: formatDateForInput(props.devoir.date_limite),
    points: props.devoir.points,
    fichier: null,
    est_actif: props.devoir.est_actif,
})

const fileInput = ref(null)

// Formater la date pour l'input datetime-local
function formatDateForInput(dateString) {
    const date = new Date(dateString)
    const year = date.getFullYear()
    const month = String(date.getMonth() + 1).padStart(2, '0')
    const day = String(date.getDate()).padStart(2, '0')
    const hours = String(date.getHours()).padStart(2, '0')
    const minutes = String(date.getMinutes()).padStart(2, '0')

    return `${year}-${month}-${day}T${hours}:${minutes}`
}

// Extraire le nom du fichier du chemin
function getFileName(filePath) {
    return filePath.split('/').pop()
}

// Gérer la sélection de fichier
function handleFileChange(event) {
    const file = event.target.files[0]
    if (file) {
        form.fichier = file
    }
}

// Soumettre le formulaire
// Soumettre le formulaire
function submit() {
    // Utilisez form.put() pour une mise à jour
    form.put(route('professeur.devoirs.update', props.devoir.id), {
        preserveScroll: true,
        onSuccess: () => {
            // Redirection après succès
            router.visit(route('professeur.devoirs.show', props.devoir.id))
        },
        onError: (errors) => {
            // Gestion des erreurs si nécessaire
            console.log('Erreurs:', errors)
        }
    })
}

// Ouvrir modal de suppression
function supprimerDevoir() {
    showDeleteModal.value = true
}

// Confirmer la suppression
function confirmerSuppression() {
    router.delete(route('professeur.devoirs.destroy', props.devoir.id), {
        preserveScroll: true,
        onSuccess: () => {
            // Redirection vers la liste après suppression
            router.visit(route('professeur.devoirs.index'))
        }
    })
}
</script>
