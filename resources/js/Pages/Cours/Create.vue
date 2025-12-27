<!-- resources/js/Pages/Cours/Create.vue -->
<template>
    <component :is="layout">
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <!-- En-tête avec message selon le type d'utilisateur -->
                <div class="mb-8">
                    <Link
                        :href="backRoute"
                        class="text-blue-600 hover:text-blue-800 flex items-center mb-4"
                    >
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Retour
                    </Link>

                    <h1 class="text-3xl font-bold text-gray-900">
                        {{ title }}
                    </h1>

                    <div v-if="userType === 'Etudiant'" class="mt-2 p-3 bg-blue-50 rounded-lg">
                        <p class="text-blue-700">
                            📚 En tant qu'étudiant premium, vous pouvez soumettre des cours !
                            Ils seront modérés par l'administrateur avant publication.
                        </p>
                    </div>
                    <div v-else-if="userType === 'Professeur'" class="mt-2 p-3 bg-green-50 rounded-lg">
                        <p class="text-green-700">
                            👨‍🏫 En tant que professeur, vos cours sont publiés directement.
                        </p>
                    </div>
                </div>

                <!-- Formulaire universel -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <CoursForm
                        :user-type="userType"
                        :submit-route="route('cours.store')"
                        :cancel-route="backRoute"
                        :success-route="backRoute"
                    />
                </div>
            </div>
        </div>
    </component>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import CoursForm from '../../Components/CoursForm.vue'
import NavbarEtudiants from '../../Layouts/NavbarEtudiants.vue'
import NavbarProfesseurs from '../../Layouts/NavbarProfesseurs.vue'

const props = defineProps({
    userType: String,
    isPremium: Boolean,
})

// Layout conditionnel
const layout = computed(() => {
    if (props.userType === 'Professeur') {
        return NavbarProfesseurs
    }
    return NavbarEtudiants
})

// Route de retour conditionnelle
const backRoute = computed(() => {
    if (props.userType === 'Professeur') {
        return route('professeur.mes-cours')
    }
    return route('cours.index')
})

// Titre conditionnel
const title = computed(() => {
    if (props.userType === 'Etudiant') {
        return 'Soumettre un cours (Étudiant Premium)'
    }
    return 'Publier un nouveau cours'
})
</script>
