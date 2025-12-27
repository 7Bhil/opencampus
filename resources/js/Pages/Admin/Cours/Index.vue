<!-- resources/js/Pages/Admin/Cours.vue -->
<template>
    <AdminLayout>
        <template #header>
            Gestion des cours
        </template>

        <!-- Filtres -->
        <div class="mb-6 bg-white shadow rounded-lg p-4">
            <div class="flex flex-col md:flex-row md:items-center md:space-x-4 space-y-4 md:space-y-0">
                <div class="flex-1">
                    <input type="text" v-model="search" placeholder="Rechercher un cours..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <select v-model="filterStatus" class="px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">Tous les statuts</option>
                        <option value="public">Public</option>
                        <option value="pending">En attente</option>
                        <option value="private">Privé</option>
                    </select>
                </div>
                <div>
                    <select v-model="filterType" class="px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">Tous les types</option>
                        <option value="free">Gratuit</option>
                        <option value="paid">Payant</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Tableau des cours -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Cours
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Auteur
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Type
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="c in filteredCours" :key="c.id">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ c.titre }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ c.matiere }} - {{ c.categorie }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ c.auteur.name }}</div>
                                <div class="text-sm text-gray-500">{{ c.auteur.account_type }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                      :class="getStatusBadgeClass(c)">
                                    {{ getStatusText(c) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span v-if="c.est_payant" class="text-green-600 font-medium">
                                    Payant ({{ c.prix }} f)
                                </span>
                                <span v-else class="text-gray-600">
                                    Gratuit
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ formatDate(c.created_at) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <Link :href="route('cours.show', c.id)"
                                          class="text-blue-600 hover:text-blue-900"
                                          target="_blank">
                                        Voir
                                    </Link>
                                    <Link :href="route('cours.edit', c.id)"
                                          class="text-yellow-600 hover:text-yellow-900">
                                        Éditer
                                    </Link>
                                    <button @click="confirmDelete(c.id)"
                                            class="text-red-600 hover:text-red-900">
                                        Supprimer
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="cours.meta" class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    <Link :href="cours.links.prev"
                          :class="{'opacity-50 cursor-not-allowed': !cours.links.prev}"
                          class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Précédent
                    </Link>
                    <Link :href="cours.links.next"
                          :class="{'opacity-50 cursor-not-allowed': !cours.links.next}"
                          class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Suivant
                    </Link>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Affichage de
                            <span class="font-medium">{{ cours.meta.from }}</span>
                            à
                            <span class="font-medium">{{ cours.meta.to }}</span>
                            sur
                            <span class="font-medium">{{ cours.meta.total }}</span>
                            résultats
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <Link v-for="(link, index) in cours.meta.links"
                                  :key="index"
                                  :href="link.url"
                                  :class="{'bg-blue-50 border-blue-500 text-blue-600 z-10': link.active, 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': !link.active}"
                                  class="relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                <span v-html="link.label"></span>
                            </Link>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router, useForm } from '@inertiajs/vue3'
import AdminLayout from '../../../Layouts/AdminLayout.vue'
import axios from 'axios'

const props = defineProps({
    cours: {
        type: Object,
        required: true,
        default: () => ({ data: [], links: {}, meta: {} })
    }
})

const search = ref('')
const filterStatus = ref('')
const filterType = ref('')
const form = useForm({})

const filteredCours = computed(() => {
    return props.cours.data.filter(c => {
        // Filtre par recherche
        if (search.value && !c.titre.toLowerCase().includes(search.value.toLowerCase()) &&
            !c.matiere.toLowerCase().includes(search.value.toLowerCase())) {
            return false
        }

        // Filtre par statut
        if (filterStatus.value) {
            if (filterStatus.value === 'public' && !c.est_public) return false
            if (filterStatus.value === 'pending' && (!c.est_modere && c.auteur.account_type === 'Etudiant')) return false
            if (filterStatus.value === 'private' && (!c.est_public && c.auteur.account_type === 'Professeur')) return false
        }

        // Filtre par type
        if (filterType.value) {
            if (filterType.value === 'free' && c.est_payant) return false
            if (filterType.value === 'paid' && !c.est_payant) return false
        }

        return true
    })
})

const getStatusText = (cours) => {
    if (cours.est_public) return 'Public'
    if (!cours.est_modere && cours.auteur.account_type === 'Etudiant') return 'En attente'
    return 'Privé'
}

const getStatusBadgeClass = (cours) => {
    if (cours.est_public) return 'bg-green-100 text-green-800'
    if (!cours.est_modere && cours.auteur.account_type === 'Etudiant') return 'bg-yellow-100 text-yellow-800'
    return 'bg-gray-100 text-gray-800'
}

const confirmDelete = (coursId) => {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce cours ?')) {
        // CORRECTION : Utilise axios au lieu de form/delete
        axios.delete(route('admin.cours.delete', coursId))
            .then(() => {
                location.reload() // Recharge la page
            })
            .catch(error => {
                alert('Erreur: ' + error.message)
            })
    }
}

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    })
}
</script>
