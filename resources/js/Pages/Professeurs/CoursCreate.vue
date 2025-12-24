<!-- resources/js/Pages/Professeurs/CoursCreate.vue -->
<template>
    <ProfesseurLayout>
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <!-- En-tête -->
                <div class="mb-8">
                    <Link
                        :href="route('professeur.cours.index')"
                        class="text-blue-600 hover:text-blue-800 flex items-center mb-4"
                    >
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Retour à mes cours
                    </Link>
                    <h1 class="text-3xl font-bold text-gray-900">Publier un nouveau cours</h1>
                    <p class="text-gray-600 mt-2">Remplissez les informations de votre cours</p>
                </div>

                <!-- Formulaire -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <form @submit.prevent="submit">
                        <!-- Messages d'erreur -->
                        <div v-if="Object.keys(form.errors).length > 0" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-red-800 font-medium">Veuillez corriger les erreurs suivantes :</span>
                            </div>
                            <ul class="mt-2 list-disc list-inside text-red-700">
                                <li v-for="error in form.errors">{{ error }}</li>
                            </ul>
                        </div>

                        <div class="space-y-6">
                            <!-- Titre -->
                            <div>
                                <label for="titre" class="block text-sm font-medium text-gray-700 mb-2">
                                    Titre du cours *
                                </label>
                                <input
                                    id="titre"
                                    v-model="form.titre"
                                    type="text"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-300': form.errors.titre }"
                                    placeholder="Ex: Introduction à Laravel"
                                    required
                                />
                                <p v-if="form.errors.titre" class="mt-1 text-sm text-red-600">{{ form.errors.titre }}</p>
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
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-300': form.errors.matiere }"
                                    placeholder="Ex: Informatique, Mathématiques"
                                    required
                                />
                            </div>

                            <!-- Catégorie -->
                            <div>
                                <label for="categorie" class="block text-sm font-medium text-gray-700 mb-2">
                                    Catégorie *
                                </label>
                                <select
                                    id="categorie"
                                    v-model="form.categorie"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-300': form.errors.categorie }"
                                    required
                                >
                                    <option value="" disabled>Sélectionnez une catégorie</option>
                                    <option value="Informatique">Informatique</option>
                                    <option value="Mathématiques">Mathématiques</option>
                                    <option value="Physique">Physique</option>
                                    <option value="Chimie">Chimie</option>
                                    <option value="Biologie">Biologie</option>
                                    <option value="Économie">Économie</option>
                                    <option value="Droit">Droit</option>
                                    <option value="Langues">Langues</option>
                                    <option value="Autre">Autre</option>
                                </select>
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
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-300': form.errors.description }"
                                    placeholder="Décrivez votre cours en quelques lignes..."
                                ></textarea>
                            </div>

                            <!-- Type de cours (Gratuit/Payant) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Type de cours
                                </label>
                                <div class="flex space-x-6">
                                    <label class="flex items-center">
                                        <input
                                            type="radio"
                                            v-model="form.est_payant"
                                            :value="false"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                        />
                                        <span class="ml-2 text-gray-700">Gratuit</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input
                                            type="radio"
                                            v-model="form.est_payant"
                                            :value="true"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                        />
                                        <span class="ml-2 text-gray-700">Payant</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Prix (conditionnel) -->
                            <div v-if="form.est_payant">
                                <label for="prix" class="block text-sm font-medium text-gray-700 mb-2">
                                    Prix (f) *
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500">f</span>
                                    </div>
                                    <input
                                        id="prix"
                                        v-model="form.prix"
                                        type="number"
                                        step="50"
                                        min="50"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        :class="{ 'border-red-300': form.errors.prix }"
                                        placeholder="500"
                                    />
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Le prix doit être supérieur à 0 f</p>
                            </div>

                            <!-- Fichier -->
                            <div>
                                <label for="fichier" class="block text-sm font-medium text-gray-700 mb-2">
                                    Fichier du cours *
                                </label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition"
                                     :class="{ 'border-blue-300 bg-blue-50': isDragging }"
                                     @dragover.prevent="isDragging = true"
                                     @dragleave="isDragging = false"
                                     @drop.prevent="handleFileDrop"
                                >
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                                <span>Télécharger un fichier</span>
                                                <input
                                                    id="file-upload"
                                                    ref="fileInput"
                                                    type="file"
                                                    class="sr-only"
                                                    @change="handleFileSelect"
                                                    accept=".pdf,.doc,.docx,.ppt,.pptx,.txt"
                                                    required
                                                />
                                            </label>
                                            <p class="pl-1">ou glissez-déposez</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PDF, DOC, DOCX, PPT, PPTX, TXT jusqu'à 10MB</p>
                                    </div>
                                </div>

                                <!-- Fichier sélectionné -->
                                <div v-if="form.fichier" class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-green-800 font-medium">Fichier sélectionné :</span>
                                        <span class="ml-2 text-gray-700">{{ form.fichier.name }}</span>
                                        <button
                                            type="button"
                                            @click="removeFile"
                                            class="ml-auto text-red-600 hover:text-red-800"
                                        >
                                            Supprimer
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Visibilité -->
                            <div>
                                <div class="flex items-center">
                                    <input
                                        id="est_public"
                                        v-model="form.est_public"
                                        type="checkbox"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                    />
                                    <label for="est_public" class="ml-2 block text-sm text-gray-700">
                                        Rendre ce cours public
                                    </label>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Si désactivé, le cours ne sera visible que par vous</p>
                            </div>

                            <!-- Boutons -->
                            <div class="flex justify-end space-x-4 pt-6 border-t">
                                <Link
                                    :href="route('professeur.cours.index')"
                                    class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition"
                                >
                                    Annuler
                                </Link>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <span v-if="form.processing">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Publication en cours...
                                    </span>
                                    <span v-else>Publier le cours</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </ProfesseurLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link, useForm, router } from '@inertiajs/vue3'
import ProfesseurLayout from '../../Layouts/NavbarProfesseurs.vue'

const fileInput = ref(null)
const isDragging = ref(false)

// Initialisation du formulaire
const form = useForm({
    titre: '',
    matiere: '',
    categorie: '',
    description: '',
    est_payant: false,
    prix: null,
    fichier: null,
    est_public: true,
})

// Gestion de la sélection de fichier
const handleFileSelect = (event) => {
    const file = event.target.files[0]
    if (file) {
        // Vérifier la taille (10MB max)
        if (file.size > 10 * 1024 * 1024) {
            alert('Le fichier est trop volumineux (max 10MB)')
            return
        }

        // Vérifier l'extension
        const allowedExtensions = ['.pdf', '.doc', '.docx', '.ppt', '.pptx', '.txt']
        const fileExtension = '.' + file.name.split('.').pop().toLowerCase()

        if (!allowedExtensions.includes(fileExtension)) {
            alert('Type de fichier non autorisé. Formats acceptés : PDF, DOC, DOCX, PPT, PPTX, TXT')
            return
        }

        form.fichier = file
    }
}

// Gestion du drag & drop
const handleFileDrop = (event) => {
    isDragging.value = false
    const file = event.dataTransfer.files[0]

    if (file) {
        // Même vérifications que ci-dessus
        if (file.size > 10 * 1024 * 1024) {
            alert('Le fichier est trop volumineux (max 10MB)')
            return
        }

        const allowedExtensions = ['.pdf', '.doc', '.docx', '.ppt', '.pptx', '.txt']
        const fileExtension = '.' + file.name.split('.').pop().toLowerCase()

        if (!allowedExtensions.includes(fileExtension)) {
            alert('Type de fichier non autorisé. Formats acceptés : PDF, DOC, DOCX, PPT, PPTX, TXT')
            return
        }

        form.fichier = file
    }
}

// Supprimer le fichier sélectionné
const removeFile = () => {
    form.fichier = null
    if (fileInput.value) {
        fileInput.value.value = ''
    }
}

// Soumission du formulaire
const submit = () => {
    form.post(route('professeur.cours.store'), {
        onSuccess: () => {
            form.reset()
        },
        onError: () => {
            // Les erreurs sont gérées automatiquement par Inertia
        }
    })
}
</script>
