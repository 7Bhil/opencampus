<!-- resources/js/Pages/Admin/Users.vue -->
<template>
    <AdminLayout>
        <template #header>
            Gestion des utilisateurs
        </template>

        <!-- Filtres -->
        <div class="mb-6 bg-white shadow rounded-lg p-4">
            <div class="flex flex-col md:flex-row md:items-center md:space-x-4 space-y-4 md:space-y-0">
                <div class="flex-1">
                    <input type="text" v-model="search" placeholder="Rechercher un utilisateur..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <select v-model="filterType" class="px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">Tous les types</option>
                        <option value="Etudiant">Étudiant</option>
                        <option value="Professeur">Professeur</option>
                        <option value="Admin">Administrateur</option>
                    </select>
                </div>
                <div>
                    <select v-model="filterPremium" class="px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">Tous</option>
                        <option value="premium">Premium</option>
                        <option value="non-premium">Non premium</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Tableau des utilisateurs -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Utilisateur
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Type
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Cours
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Solde
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Inscription
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="user in filteredUsers" :key="user.id">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <span class="text-blue-600 font-medium">{{ user.name.charAt(0) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ user.name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ user.email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <select v-model="user.account_type"
                                        @change="updateUserStatus(user.id, 'account_type', $event.target.value)"
                                        class="border border-gray-300 rounded px-2 py-1 text-sm">
                                    <option value="Etudiant">Étudiant</option>
                                    <option value="Professeur">Professeur</option>
                                    <option value="Admin">Admin</option>
                                </select>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex items-center space-x-2">
                                        <span v-if="user.is_premium"
                                              class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                            Premium
                                        </span>
                                        <span v-else
                                              class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Standard
                                        </span>
                                        <input type="checkbox"
                                               :checked="user.is_premium"
                                               @change="updateUserStatus(user.id, 'is_premium', $event.target.checked)"
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ user.cours_count }} cours
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm font-medium text-gray-900">
                                        {{ user.balance }} f
                                    </span>
                                    <button @click="editBalance(user)"
                                            class="text-blue-600 hover:text-blue-900 text-sm">
                                        Modifier
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ formatDate(user.created_at) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button @click="resetPassword(user.id)"
                                        class="text-yellow-600 hover:text-yellow-900 mr-4">
                                    Réinit. MDP
                                </button>
                                <button @click="confirmDelete(user.id)"
                                        class="text-red-600 hover:text-red-900">
                                    Supprimer
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="users.meta" class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <!-- Même pagination que pour Cours.vue -->
            </div>
        </div>

        <!-- Modal pour éditer le solde -->
        <div v-if="showBalanceModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="px-6 py-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Modifier le solde de {{ selectedUser?.name }}
                    </h3>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nouveau solde (f)
                        </label>
                        <input type="number" v-model.number="newBalance"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                    <button @click="showBalanceModal = false"
                            class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 hover:bg-gray-50">
                        Annuler
                    </button>
                    <button @click="saveBalance"
                            class="px-4 py-2 bg-blue-600 text-sm font-medium rounded-md text-white hover:bg-blue-700">
                        Enregistrer
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '../../Layouts/AdminLayout.vue'

const props = defineProps({
    users: {
        type: Object,
        required: true,
        default: () => ({ data: [], links: {}, meta: {} })
    }
})

const search = ref('')
const filterType = ref('')
const filterPremium = ref('')
const showBalanceModal = ref(false)
const selectedUser = ref(null)
const newBalance = ref(0)
const form = useForm({})

const filteredUsers = computed(() => {
    return props.users.data.filter(user => {
        // Filtre par recherche
        if (search.value && !user.name.toLowerCase().includes(search.value.toLowerCase()) &&
            !user.email.toLowerCase().includes(search.value.toLowerCase())) {
            return false
        }

        // Filtre par type
        if (filterType.value && user.account_type !== filterType.value) {
            return false
        }

        // Filtre par premium
        if (filterPremium.value === 'premium' && !user.is_premium) return false
        if (filterPremium.value === 'non-premium' && user.is_premium) return false

        return true
    })
})

const editBalance = (user) => {
    selectedUser.value = user
    newBalance.value = user.balance
    showBalanceModal.value = true
}

const saveBalance = () => {
    if (selectedUser.value) {
        form.put(route('admin.users.update-status', selectedUser.value.id), {
            balance: newBalance.value
        }, {
            onSuccess: () => {
                showBalanceModal.value = false
            }
        })
    }
}

const updateUserStatus = (userId, field, value) => {
    form.put(route('admin.users.update-status', userId), {
        [field]: value
    })
}

const resetPassword = (userId) => {
    if (confirm('Réinitialiser le mot de passe de cet utilisateur ? Un email sera envoyé.')) {
        // Implémentez la logique de réinitialisation
        alert('Fonctionnalité à implémenter')
    }
}

const confirmDelete = (userId) => {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Tous ses cours seront aussi supprimés.')) {
        form.delete(route('admin.users.delete', userId))
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
