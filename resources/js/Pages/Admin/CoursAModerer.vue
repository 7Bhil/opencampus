<!-- resources/js/Pages/Admin/CoursAModerer.vue -->
<template>
    <AdminLayout :cours-a-moderer-count="cours.length">
        <template #header>
            Cours à modérer
        </template>

        <div v-if="cours.length === 0" class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun cours à modérer</h3>
            <p class="mt-1 text-sm text-gray-500">Tous les cours ont été traités.</p>
        </div>

        <div v-else class="space-y-6">
            <div v-for="c in cours" :key="c.id" class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">{{ c.titre }}</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            En attente
                        </span>
                    </div>

                    <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">
                                <strong>Auteur :</strong> {{ c.auteur.name }} ({{ c.auteur.account_type }})
                            </p>
                            <p class="text-sm text-gray-600">
                                <strong>Matière :</strong> {{ c.matiere }}
                            </p>
                            <p class="text-sm text-gray-600">
                                <strong>Catégorie :</strong> {{ c.categorie }}
                            </p>
                            <p class="text-sm text-gray-600 mt-2">
                                <strong>Description :</strong>
                                <span class="block mt-1">{{ c.description || 'Aucune description' }}</span>
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">
                                <strong>Soumis le :</strong> {{ formatDate(c.created_at) }}
                            </p>
                            <p class="text-sm text-gray-600">
                                <strong>Type :</strong> {{ c.est_payant ? 'Payant (' + c.prix + ' f)' : 'Gratuit' }}
                            </p>
                            <p class="text-sm text-gray-600">
                                <strong>Fichier :</strong>
                                <a :href="route('cours.download', c.id)"
                                   class="text-blue-600 hover:text-blue-900 ml-1"
                                   target="_blank">
                                    Télécharger
                                </a>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                    <form @submit.prevent="rejeterCours(c.id)">
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Rejeter
                        </button>
                    </form>
                    <form @submit.prevent="approuverCours(c.id)">
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Approuver
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '../../Layouts/AdminLayout.vue'

defineProps({
    cours: {
        type: Array,
        required: true,
        default: () => []
    }
})

const form = useForm({})

const approuverCours = (coursId) => {
    if (confirm('Approuver ce cours ? Il sera publié sur la marketplace.')) {
        form.post(route('admin.cours.approuver', coursId))
    }
}

const rejeterCours = (coursId) => {
    if (confirm('Rejeter ce cours ? L\'auteur sera informé.')) {
        form.post(route('admin.cours.rejeter', coursId))
    }
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
</script>
