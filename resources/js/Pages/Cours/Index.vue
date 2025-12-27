<template>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- En-tête -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Marketplace des Cours</h1>
                    <p class="text-gray-600 mt-2">Découvrez des cours de qualité par des professeurs et étudiants</p>
                </div>

                <div class="mt-4 md:mt-0 flex space-x-3">
                    <Link v-if="$page.props.auth.user && $page.props.auth.user.canPublishCours"
                          :href="route('cours.create')"
                          class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
                        Publier un cours
                    </Link>

                    <Link v-else-if="$page.props.auth.user && $page.props.auth.user.account_type === 'Etudiant'"
                          :href="route('etudiant.premium.index')"
                          class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium">
                        Devenir Premium
                    </Link>
                </div>
            </div>

            <!-- Filtres -->
            <div class="bg-white rounded-xl shadow p-4 mb-6">
                <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                    <input v-model="search" type="text"
                           placeholder="Rechercher un cours..."
                           class="flex-grow px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                    <select v-model="selectedCategory"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Toutes les catégories</option>
                        <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
                    </select>

                    <select v-model="selectedType"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tous les types</option>
                        <option value="gratuit">Gratuit</option>
                        <option value="payant">Payant</option>
                    </select>
                </div>
            </div>

            <!-- Liste des cours -->
            <div v-if="filteredCours.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="cours in filteredCours" :key="cours.id"
                     class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <!-- Badge auteur -->
                    <div class="px-4 py-2" :class="cours.auteur.account_type === 'Professeur' ? 'bg-blue-50' : 'bg-green-50'">
                        <span :class="cours.auteur.account_type === 'Professeur' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'"
                              class="px-2 py-1 text-xs rounded-full font-medium">
                            {{ cours.auteur.account_type === 'Professeur' ? '👨‍🏫 Professeur' : '👨‍🎓 Étudiant' }}
                        </span>
                        <span v-if="!cours.est_public" class="ml-2 px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">
                            En attente
                        </span>
                    </div>

                    <!-- Contenu -->
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-2 truncate">{{ cours.titre }}</h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ cours.description }}</p>

                        <div class="flex justify-between items-center mb-4">
                            <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded">{{ cours.matiere }}</span>
                            <div v-if="cours.est_payant">
                                <span class="text-lg font-bold text-gray-900">{{ cours.prix }}€</span>
                            </div>
                            <span v-else class="bg-green-100 text-green-800 px-2 py-1 text-sm rounded font-medium">
                                Gratuit
                            </span>
                        </div>

                        <div class="flex justify-between items-center mt-4 pt-4 border-t">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center mr-2">
                                    <span class="text-indigo-600 font-bold text-sm">
                                        {{ cours.auteur.name.substring(0, 1) }}
                                    </span>
                                </div>
                                <span class="text-sm text-gray-700 truncate">{{ cours.auteur.name }}</span>
                            </div>

                            <Link :href="route('cours.show', cours.id)"
                                  class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                Voir le cours →
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Message si aucun cours -->
            <div v-else class="text-center py-12 bg-white rounded-xl shadow">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Aucun cours disponible</h3>
                <p class="mt-2 text-gray-500">Soyez le premier à publier un cours !</p>
                <div class="mt-6">
                    <Link v-if="$page.props.auth.user && $page.props.auth.user.canPublishCours"
                          :href="route('cours.create')"
                          class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        Publier un cours
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    cours: Array,
    canPublish: Boolean
})

const search = ref('')
const selectedCategory = ref('')
const selectedType = ref('')

// Extraire les catégories uniques
const categories = computed(() => {
    return [...new Set(props.cours.map(c => c.categorie).filter(Boolean))]
})

// Filtrer les cours
const filteredCours = computed(() => {
    return props.cours.filter(cours => {
        // Filtre recherche
        const matchSearch = !search.value ||
            cours.titre.toLowerCase().includes(search.value.toLowerCase()) ||
            cours.description.toLowerCase().includes(search.value.toLowerCase()) ||
            cours.matiere.toLowerCase().includes(search.value.toLowerCase())

        // Filtre catégorie
        const matchCategory = !selectedCategory.value ||
            cours.categorie === selectedCategory.value

        // Filtre type
        const matchType = !selectedType.value ||
            (selectedType.value === 'gratuit' && !cours.est_payant) ||
            (selectedType.value === 'payant' && cours.est_payant)

        return matchSearch && matchCategory && matchType
    })
})
</script>
