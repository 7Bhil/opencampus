<template>
    <ProfesseurLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- En-tête -->
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900">Mes Cours</h2>
                        <p class="text-gray-600 mt-2">Gérez et consultez vos cours publiés</p>
                    </div>
                    <Link
                        :href="route('professeur.cours.create')"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition"
                    >
                        + Publier un nouveau cours
                    </Link>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-xl shadow">
                        <p class="text-gray-500">Total des cours</p>
                        <p class="text-3xl font-bold mt-2">{{ stats.total_cours }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow">
                        <p class="text-gray-500">Total des téléchargements</p>
                        <p class="text-3xl font-bold mt-2">{{ stats.total_telechargements }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow">
                        <p class="text-gray-500">Total des likes</p>
                        <p class="text-3xl font-bold mt-2">{{ stats.total_likes }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow">
                        <p class="text-gray-500">Cours payants</p>
                        <p class="text-3xl font-bold mt-2">{{ stats.cours_payants }}</p>
                    </div>
                </div>

                <!-- Tableau des cours -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <!-- Filtres -->
                        <div class="flex gap-4 mb-6">
                            <select
                                v-model="filtreCategorie"
                                class="border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                            >
                                <option value="">Toutes les catégories</option>
                                <option v-for="categorie in categories" :value="categorie">
                                    {{ categorie }}
                                </option>
                            </select>

                            <select
                                v-model="filtreType"
                                class="border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                            >
                                <option value="">Tous les types</option>
                                <option value="payant">Payant</option>
                                <option value="gratuit">Gratuit</option>
                            </select>
                        </div>

                        <!-- Liste des cours -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Titre du cours
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Catégorie
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Likes
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Téléchargements
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Prix
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="cours in filteredCours" :key="cours.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ cours.titre }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ cours.matiere }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ cours.categorie }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <svg class="h-5 w-5 text-red-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                                </svg>
                                                <span class="text-sm text-gray-900">{{ cours.likes_count }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <svg class="h-5 w-5 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                </svg>
                                                <span class="text-sm text-gray-900">{{ cours.telechargements_count }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <span v-if="cours.est_payant" class="font-bold text-green-600">
                                                {{ cours.prix }} f
                                            </span>
                                            <span v-else class="text-gray-500">
                                                Gratuit
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ formatDate(cours.created_at) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-3">
                                                <Link
                                                    :href="route('professeur.cours.show', cours.id)"
                                                    class="text-blue-600 hover:text-blue-900"
                                                >
                                                    Voir
                                                </Link>
                                                <button
                                                    class="text-indigo-600 hover:text-indigo-900"
                                                    @click="editCours(cours.id)"
                                                >
                                                    Modifier
                                                </button>
                                                <button
                                                    @click="supprimerCours(cours.id)"
                                                    class="text-red-600 hover:text-red-900"
                                                >
                                                    Supprimer
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Message si aucun cours -->
                        <div v-if="cours.data.length === 0" class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">Aucun cours publié</h3>
                            <p class="mt-2 text-gray-500">Commencez par publier votre premier cours.</p>
                            <div class="mt-6">
                                <Link
                                    :href="route('professeur.cours.create')"
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
                                >
                                    Publier un cours
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </ProfesseurLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import ProfesseurLayout from '../../Layouts/NavbarProfesseurs.vue'

const props = defineProps({
    cours: Object,
    stats: Object,
    categories: Array
})

const filtreCategorie = ref('')
const filtreType = ref('')

const formatDate = (dateString) => {
    const date = new Date(dateString)
    return date.toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    })
}

const filteredCours = computed(() => {
    return props.cours.data.filter(cours => {
        const matchCategorie = !filtreCategorie.value || cours.categorie === filtreCategorie.value
        const matchType = !filtreType.value ||
            (filtreType.value === 'payant' && cours.est_payant) ||
            (filtreType.value === 'gratuit' && !cours.est_payant)
        return matchCategorie && matchType
    })
})

const supprimerCours = (id) => {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce cours ?')) {
        router.delete(route('professeur.cours.destroy', id))
    }
}

const editCours = (id) => {
    // Redirige vers la page d'édition
    router.get(route('professeur.cours.edit', id))
}
</script>
