<template>
    <EtudiantLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- En-tête -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Mes Cours Suivis</h1>
                    <p class="text-gray-600 mt-2">Cours auxquels vous êtes inscrit</p>
                </div>

                <!-- Liste des cours -->
                <div v-if="coursList.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="cours in coursList" :key="cours.id"
                         class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">

                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ cours.titre }}</h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ cours.description || 'Pas de description' }}</p>

                            <div class="flex justify-between items-center mb-4">
                                <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded">{{ cours.matiere }}</span>
                                <span v-if="cours.est_payant" class="text-lg font-bold text-gray-900">
                                    {{ cours.prix }}€
                                </span>
                                <span v-else class="bg-green-100 text-green-800 px-2 py-1 text-sm rounded">
                                    Gratuit
                                </span>
                            </div>

                            <div class="flex justify-between items-center mt-4 pt-4 border-t">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center mr-2">
                                        <span class="text-indigo-600 font-bold text-sm">
                                            {{ cours.auteur?.name?.charAt(0) || 'P' }}
                                        </span>
                                    </div>
                                    <span class="text-sm text-gray-700">{{ cours.auteur?.name || 'Professeur' }}</span>
                                </div>

                                <div class="flex space-x-2">
                                    <Link :href="route('etudiant.cours.show', cours.id)"
                                          class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Voir
                                    </Link>
                                    <Link :href="route('etudiant.cours.download', cours.id)"
                                          class="text-green-600 hover:text-green-800 text-sm font-medium">
                                        Télécharger
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Message si aucun cours -->
                <div v-else class="text-center py-12 bg-white rounded-xl shadow">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Aucun cours suivi</h3>
                    <p class="mt-2 text-gray-500">Commencez par explorer les cours disponibles !</p>
                    <div class="mt-6">
                        <Link :href="route('cours.index')"
                              class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            Explorer les cours
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </EtudiantLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import EtudiantLayout from '../../../Layouts/NavbarEtudiants.vue'

// Définir les props correctement
const props = defineProps({
    cours: {
        type: [Array, Object],
        default: () => []
    }
})

// Convertir en tableau sûr
const coursList = computed(() => {
    const data = props.cours

    if (Array.isArray(data)) {
        return data
    }

    if (data?.data && Array.isArray(data.data)) {
        return data.data
    }

    if (typeof data === 'object' && data !== null) {
        return Object.values(data)
    }

    return []
})
</script>
