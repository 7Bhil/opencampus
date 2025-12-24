<!-- resources/js/Pages/Professeurs/Devoirs/Index.vue -->
<template>
    <ProfesseurLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- En-tête -->
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900">Mes Devoirs</h2>
                        <p class="text-gray-600 mt-2">Gérez les devoirs et corrigez les soumissions</p>
                    </div>
                    <Link
                        :href="route('professeur.devoirs.create')"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition"
                    >
                        + Créer un devoir
                    </Link>
                </div>

                <!-- Messages flash -->
                <div v-if="$page.props.flash">
                    <div v-if="$page.props.flash.success" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-green-800 font-medium">{{ $page.props.flash.success }}</span>
                        </div>
                    </div>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-xl shadow">
                        <p class="text-gray-500">Total des devoirs</p>
                        <p class="text-3xl font-bold mt-2">{{ stats?.total_devoirs || 0 }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow">
                        <p class="text-gray-500">Soumissions totales</p>
                        <p class="text-3xl font-bold mt-2">{{ stats?.total_soumissions || 0 }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow">
                        <p class="text-gray-500">À corriger</p>
                        <p class="text-3xl font-bold mt-2">{{ stats?.soumissions_en_attente || 0 }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow">
                        <p class="text-gray-500">Devoirs actifs</p>
                        <p class="text-3xl font-bold mt-2">{{ stats?.devoirs_actifs || 0 }}</p>
                    </div>
                </div>

                <!-- Filtres -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <div class="flex flex-wrap gap-4">
                        <select
                            v-model="filtreStatut"
                            class="border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500 px-4 py-2"
                        >
                            <option value="">Tous les statuts</option>
                            <option value="actif">Actifs</option>
                            <option value="inactif">Inactifs</option>
                            <option value="termine">Terminés</option>
                        </select>

                        <select
                            v-model="filtreMatiere"
                            class="border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500 px-4 py-2"
                        >
                            <option value="">Toutes les matières</option>
                            <option v-for="matiere in matieres" :value="matiere">{{ matiere }}</option>
                        </select>

                        <input
                            v-model="recherche"
                            type="text"
                            placeholder="Rechercher un devoir..."
                            class="border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500 px-4 py-2 flex-grow"
                        />
                    </div>
                </div>

                <!-- Tableau des devoirs -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Devoir
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Matière
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date limite
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Soumissions
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Statut
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="devoir in filteredDevoirs" :key="devoir.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-lg bg-indigo-100 flex items-center justify-center">
                                                        <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ devoir.titre }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ devoir.points }} points
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ devoir.matiere }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ formatDate(devoir.date_limite) }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ getTimeRemaining(devoir.date_limite) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-4">
                                                <div class="text-center">
                                                    <div class="text-lg font-bold text-gray-900">
                                                        {{ devoir.soumissions_count || 0 }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">Total</div>
                                                </div>
                                                <div class="text-center">
                                                    <div class="text-lg font-bold text-green-600">
                                                        {{ devoir.soumissions_corrigees_count || 0 }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">Corrigées</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="getStatusClasses(devoir)">
                                                {{ getStatusText(devoir) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-3">
                                                <Link
                                                    :href="route('professeur.devoirs.show', devoir.id)"
                                                    class="text-blue-600 hover:text-blue-900"
                                                    title="Voir les soumissions"
                                                >
                                                    Voir
                                                </Link>
                                                <Link
                                                    :href="route('professeur.devoirs.edit', devoir.id)"
                                                    class="text-indigo-600 hover:text-indigo-900"
                                                    title="Modifier"
                                                >
                                                    Modifier
                                                </Link>
                                                <button
                                                    @click="toggleActif(devoir)"
                                                    :class="devoir.est_actif ? 'text-yellow-600 hover:text-yellow-900' : 'text-green-600 hover:text-green-900'"
                                                    :title="devoir.est_actif ? 'Désactiver' : 'Activer'"
                                                >
                                                    {{ devoir.est_actif ? 'Désactiver' : 'Activer' }}
                                                </button>
                                                <button
                                                    @click="supprimerDevoir(devoir.id)"
                                                    class="text-red-600 hover:text-red-900"
                                                    title="Supprimer"
                                                >
                                                    Supprimer
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Message si aucun devoir -->
                        <div v-if="devoirs.data.length === 0" class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">Aucun devoir créé</h3>
                            <p class="mt-2 text-gray-500">Commencez par créer votre premier devoir.</p>
                            <div class="mt-6">
                                <Link
                                    :href="route('professeur.devoirs.create')"
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
                                >
                                    Créer un devoir
                                </Link>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6 flex justify-center" v-if="devoirs.data.length > 0">
                            <Component
                                :is="link.url ? 'Link' : 'span'"
                                v-for="link in devoirs.links"
                                :href="link.url"
                                v-html="link.label"
                                class="px-4 py-2 mx-1 rounded-lg"
                                :class="{
                                    'bg-blue-600 text-white': link.active,
                                    'bg-gray-100 text-gray-700 hover:bg-gray-200': link.url && !link.active,
                                    'text-gray-400': !link.url
                                }"
                            />
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
import ProfesseurLayout from '../../../Layouts/NavbarProfesseurs.vue'

const props = defineProps({
    devoirs: Object,
    stats: Object,
    matieres: Array
})

const filtreStatut = ref('')
const filtreMatiere = ref('')
const recherche = ref('')

const formatDate = (dateString) => {
    const date = new Date(dateString)
    return date.toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
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

    if (days > 0) return `${days}j ${hours}h`
    return `${hours} heures`
}

const getStatusClasses = (devoir) => {
    const now = new Date()
    const limite = new Date(devoir.date_limite)

    if (!devoir.est_actif) {
        return 'px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800'
    }

    if (limite < now) {
        return 'px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800'
    }

    return 'px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800'
}

const getStatusText = (devoir) => {
    const now = new Date()
    const limite = new Date(devoir.date_limite)

    if (!devoir.est_actif) return 'Inactif'
    if (limite < now) return 'Terminé'
    return 'Actif'
}

const filteredDevoirs = computed(() => {
    return props.devoirs.data.filter(devoir => {
        // Filtre par statut
        let matchStatut = true
        if (filtreStatut.value) {
            if (filtreStatut.value === 'actif') {
                matchStatut = devoir.est_actif && new Date(devoir.date_limite) > new Date()
            } else if (filtreStatut.value === 'inactif') {
                matchStatut = !devoir.est_actif
            } else if (filtreStatut.value === 'termine') {
                matchStatut = devoir.est_actif && new Date(devoir.date_limite) < new Date()
            }
        }

        // Filtre par matière
        const matchMatiere = !filtreMatiere.value || devoir.matiere === filtreMatiere.value

        // Filtre par recherche
        const matchRecherche = !recherche.value ||
            devoir.titre.toLowerCase().includes(recherche.value.toLowerCase()) ||
            devoir.matiere.toLowerCase().includes(recherche.value.toLowerCase())

        return matchStatut && matchMatiere && matchRecherche
    })
})

const toggleActif = (devoir) => {
    router.put(route('professeur.devoirs.update', devoir.id), {
        est_actif: !devoir.est_actif
    }, {
        preserveScroll: true,
        onSuccess: () => {
            // Recharger la page pour voir les changements
            router.reload({ only: ['devoirs'] })
        }
    })
}

const supprimerDevoir = (id) => {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce devoir ? Toutes les soumissions associées seront également supprimées.')) {
        router.delete(route('professeur.devoirs.destroy', id))
    }
}
</script>
