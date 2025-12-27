<!-- resources/js/Pages/Etudiants/Premium.vue -->
<template>
    <EtudiantLayout>
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-8">Abonnement Premium</h1>

                <!-- Messages flash SÉCURISÉS -->
                <div v-if="flashSuccess" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-green-800">{{ flashSuccess }}</p>
                </div>
                <div v-if="flashError" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-red-800">{{ flashError }}</p>
                </div>

                <!-- Statut actuel -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-xl font-semibold mb-4">Votre statut actuel</h2>

                    <div v-if="$page.props.auth.user.is_premium" class="p-4 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <svg class="h-6 w-6 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-green-800 font-medium">Vous êtes premium !</span>
                        </div>

                        <p v-if="$page.props.auth.user.premium_until" class="mt-2 text-gray-700">
                            Valide jusqu'au : {{ formatDate($page.props.auth.user.premium_until) }}
                        </p>

                        <!-- Bouton annuler -->
                        <form @submit.prevent="annulerAbonnement" class="mt-4">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg disabled:opacity-50"
                            >
                                Annuler l'abonnement
                            </button>
                        </form>
                    </div>

                    <div v-else class="p-4 bg-yellow-50 rounded-lg">
                        <p class="text-yellow-800">Vous n'êtes pas encore premium.</p>

                        <!-- Formules d'abonnement -->
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div v-for="formule in formules" :key="formule.duree"
                                 class="border rounded-lg p-4 hover:border-blue-500 cursor-pointer"
                                 :class="{ 'border-blue-500 bg-blue-50': formuleSelectionnee?.duree === formule.duree }"
                                 @click="selectionnerFormule(formule)">
                                <div class="text-center">
                                    <h3 class="font-bold text-lg">{{ formule.duree }} mois</h3>
                                    <p class="text-2xl font-bold text-blue-600 my-2">{{ formule.prix }} f</p>
                                    <p v-if="formule.economie" class="text-green-600 text-sm font-medium">
                                        {{ formule.economie }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Bouton souscrire -->
                        <form @submit.prevent="souscrire" v-if="formuleSelectionnee" class="mt-6">
                            <input type="hidden" name="duree" :value="formuleSelectionnee.duree">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-medium disabled:opacity-50"
                            >
                                <span v-if="form.processing">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Traitement en cours...
                                </span>
                                <span v-else>
                                    Souscrire pour {{ formuleSelectionnee.prix }} f
                                </span>
                            </button>
                        </form>

                        <div v-else class="mt-6 p-3 bg-gray-50 rounded-lg">
                            <p class="text-gray-600 text-center">Sélectionnez une formule d'abonnement</p>
                        </div>
                    </div>

                    <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                        <p class="text-gray-700">
                            <strong>Solde disponible :</strong>
                            <span class="font-bold text-blue-700">{{ $page.props.auth.user.balance }} f</span>
                        </p>
                        <p class="text-sm text-gray-600 mt-1">
                            Le montant sera déduit de votre solde lors de la souscription
                        </p>
                    </div>
                </div>

                <!-- Avantages premium -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-4">🎯 Avantages Premium</h2>
                    <ul class="space-y-3">
                        <li class="flex items-start p-2 hover:bg-gray-50 rounded">
                            <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <strong class="text-gray-800">Publier des cours</strong>
                                <p class="text-gray-600 text-sm">Partagez vos connaissances sur la marketplace OpenCampus</p>
                            </div>
                        </li>
                        <li class="flex items-start p-2 hover:bg-gray-50 rounded">
                            <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <strong class="text-gray-800">Revenus supplémentaires</strong>
                                <p class="text-gray-600 text-sm">Vendez vos cours aux autres étudiants et générez des revenus</p>
                            </div>
                        </li>
                        <li class="flex items-start p-2 hover:bg-gray-50 rounded">
                            <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <strong class="text-gray-800">Visibilité accrue</strong>
                                <p class="text-gray-600 text-sm">Votre profil est mis en avant dans la communauté</p>
                            </div>
                        </li>
                        <li class="flex items-start p-2 hover:bg-gray-50 rounded">
                            <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <strong class="text-gray-800">Support prioritaire</strong>
                                <p class="text-gray-600 text-sm">Bénéficiez d'un support technique en priorité</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </EtudiantLayout>
</template>

<!-- resources/js/Pages/Etudiants/Premium.vue -->
<script setup>
import { ref, computed } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3' // AJOUTEZ usePage
import EtudiantLayout from '../../Layouts/NavbarEtudiants.vue'

// Obtenez $page via usePage()
const page = usePage()

// Formules d'abonnement
const formules = [
    { duree: 1, prix: 1000, economie: '' },
    { duree: 3, prix: 2700, economie: '-10%' },
    { duree: 6, prix: 4800, economie: '-20%' },
    { duree: 12, prix: 8400, economie: '-30%' },
]

const formuleSelectionnee = ref(null)
const form = useForm({
    duree: null
})

// Computed properties sécurisées pour les messages flash
const flashSuccess = computed(() => {
    // Utilisez page.props au lieu de $page.props
    return page.props.flash?.success ||
           page.props.success ||
           (page.props.flash && page.props.flash.success) ||
           null
})

const flashError = computed(() => {
    return page.props.flash?.error ||
           page.props.error ||
           (page.props.flash && page.props.flash.error) ||
           null
})

// Computed pour l'utilisateur (facultatif mais plus propre)
const user = computed(() => page.props.auth.user)

const selectionnerFormule = (formule) => {
    formuleSelectionnee.value = formule
    form.duree = formule.duree
}

const souscrire = () => {
    if (!formuleSelectionnee.value) {
        alert('Veuillez sélectionner une formule')
        return
    }

    // Vérifier le solde
    if (user.value.balance < formuleSelectionnee.value.prix) {
        alert(`Solde insuffisant ! Vous avez ${user.value.balance} f, mais il faut ${formuleSelectionnee.value.prix} f.`)
        return
    }

    form.post(route('etudiant.premium.souscrire'), {
        onSuccess: () => {
            formuleSelectionnee.value = null
        }
    })
}

const annulerAbonnement = () => {
    if (confirm('Êtes-vous sûr de vouloir annuler votre abonnement premium ?\n\nVous ne pourrez plus publier de cours.')) {
        form.post(route('etudiant.premium.annuler'))
    }
}

const formatDate = (dateString) => {
    if (!dateString) return ''
    return new Date(dateString).toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    })
}
</script>
