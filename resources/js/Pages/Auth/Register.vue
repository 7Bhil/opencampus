<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-purple-50 flex items-center justify-center p-4">
        <div class="max-w-md w-full">
            <!-- Logo et titre -->
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-500 rounded-3xl shadow-2xl mb-6">
                    <span class="text-3xl text-white">🎓</span>
                </div>
                <h1 class="text-4xl font-black bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-3">
                    Rejoindre OpenCampus
                </h1>
                <p class="text-gray-600">
                    Créez votre compte et accédez à toutes les ressources
                </p>
            </div>

            <!-- Carte du formulaire -->
            <div class="bg-white rounded-3xl shadow-2xl p-8 border border-gray-100">
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Nom complet -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Nom complet
                        </label>
                        <div class="relative">
                            <input
                                id="name"
                                type="text"
                                class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                v-model="form.name"
                                required
                                autofocus
                                autocomplete="name"
                                placeholder="Jean Dupont"
                            />
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="bi bi-person text-lg"></i>
                            </div>
                        </div>
                        <p v-if="form.errors.name" class="mt-2 text-sm text-red-600">
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Adresse email
                        </label>
                        <div class="relative">
                            <input
                                id="email"
                                type="email"
                                class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                v-model="form.email"
                                required
                                autocomplete="email"
                                placeholder="exemple@email.com"
                            />
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="bi bi-envelope text-lg"></i>
                            </div>
                        </div>
                        <p v-if="form.errors.email" class="mt-2 text-sm text-red-600">
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <!-- Type de compte -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Type de compte
                        </label>
                        <div class="relative">
                            <select
                                id="account_type"
                                class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all appearance-none bg-white"
                                v-model="form.account_type"
                                required
                                @change="handleAccountTypeChange"
                            >
                                <option value="Etudiant">👨‍🎓 Étudiant</option>
                                <option value="Professeur">👨‍🏫 Professeur</option>
                            </select>
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="bi bi-person-badge text-lg"></i>
                            </div>
                            <div class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none">
                                <i class="bi bi-chevron-down"></i>
                            </div>
                        </div>
                        <p v-if="form.errors.account_type" class="mt-2 text-sm text-red-600">
                            {{ form.errors.account_type }}
                        </p>
                    </div>

                    <!-- Filière (uniquement pour les étudiants) -->
                    <div v-if="form.account_type === 'Etudiant'">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Filière
                        </label>
                        <div class="relative">
                            <select
                                id="filiere"
                                class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all appearance-none bg-white"
                                v-model="form.filiere"
                                required
                            >
                                <option value="" disabled selected>Choisir votre filière</option>
                                <option value="IG">💻 IG - Informatique et Gestion</option>
                                <option value="GC">💰 GC - Gestion Commerciale</option>
                                <option value="GE">🏢 GE - Gestion des Entreprises</option>
                                <option value="GB">🏦 GB - Gestion des Banques</option>
                                <option value="GRH">👥 GRH - Gestion des Ressources Humaines</option>
                                <option value="GTL">🚚 GTL - Gestion des Transports et Logistique</option>
                            </select>
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="bi bi-mortarboard text-lg"></i>
                            </div>
                            <div class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none">
                                <i class="bi bi-chevron-down"></i>
                            </div>
                        </div>
                        <p v-if="form.errors.filiere" class="mt-2 text-sm text-red-600">
                            {{ form.errors.filiere }}
                        </p>

                        <!-- Description de la filière sélectionnée -->
                        <div v-if="form.filiere" class="mt-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-sm text-gray-600 font-medium mb-1">
                                Filière sélectionnée :
                            </p>
                            <p class="text-sm text-gray-700">
                                {{ getFiliereDescription(form.filiere) }}
                            </p>
                        </div>
                    </div>

                    <!-- Mot de passe -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Mot de passe
                        </label>
                        <div class="relative">
                            <input
                                :type="showPassword ? 'text' : 'password'"
                                id="password"
                                class="w-full px-4 py-3 pl-12 pr-12 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                v-model="form.password"
                                required
                                autocomplete="new-password"
                                placeholder="Minimum 8 caractères"
                            />
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="bi bi-lock text-lg"></i>
                            </div>
                            <button
                                type="button"
                                class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-500 transition-colors"
                                @click="showPassword = !showPassword"
                            >
                                <i :class="showPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                            </button>
                        </div>
                        <p v-if="form.errors.password" class="mt-2 text-sm text-red-600">
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <!-- Confirmation mot de passe -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Confirmer le mot de passe
                        </label>
                        <div class="relative">
                            <input
                                :type="showConfirmPassword ? 'text' : 'password'"
                                id="password_confirmation"
                                class="w-full px-4 py-3 pl-12 pr-12 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                v-model="form.password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="Répétez votre mot de passe"
                            />
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="bi bi-shield-lock text-lg"></i>
                            </div>
                            <button
                                type="button"
                                class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-500 transition-colors"
                                @click="showConfirmPassword = !showConfirmPassword"
                            >
                                <i :class="showConfirmPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                            </button>
                        </div>
                        <p v-if="form.errors.password_confirmation" class="mt-2 text-sm text-red-600">
                            {{ form.errors.password_confirmation }}
                        </p>
                    </div>

                    <!-- Informations sur les comptes -->
                    <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                        <h3 class="font-semibold text-blue-800 mb-2 text-sm">À propos des comptes :</h3>
                        <ul class="text-xs text-blue-600 space-y-1">
                            <li class="flex items-start gap-2">
                                <i class="bi bi-person text-blue-500 mt-0.5"></i>
                                <span><span class="font-semibold">Étudiant :</span> Accès aux cours, devoirs et téléchargements. Sélectionnez votre filière parmi les 6 options disponibles.</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="bi bi-person-badge text-blue-500 mt-0.5"></i>
                                <span><span class="font-semibold">Professeur :</span> Publication de cours, gestion des devoirs et suivi des étudiants.</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Bouton d'inscription -->
                    <button
                        type="submit"
                        class="w-full py-3 bg-gradient-to-r from-blue-500 to-purple-500 text-white font-semibold rounded-xl hover:scale-105 hover:shadow-xl transition-all transform disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                        :disabled="form.processing"
                    >
                        <span v-if="form.processing" class="flex items-center justify-center gap-2">
                            <i class="bi bi-arrow-clockwise animate-spin"></i>
                            Création du compte...
                        </span>
                        <span v-else class="flex items-center justify-center gap-2">
                            <i class="bi bi-person-plus"></i>
                            Créer mon compte
                        </span>
                    </button>
                </form>

                <!-- Lien vers la connexion -->
                <div class="mt-8 pt-6 border-t border-gray-200 text-center">
                    <p class="text-gray-600 text-sm">
                        Vous avez déjà un compte ?
                        <Link
                            :href="route('login')"
                            class="text-blue-500 hover:text-blue-600 font-semibold transition-colors"
                        >
                            Se connecter
                        </Link>
                    </p>
                </div>
            </div>

            <!-- Conditions -->
            <div class="mt-8 text-center text-gray-500 text-xs">
                <p>En vous inscrivant, vous acceptez nos conditions d'utilisation et notre politique de confidentialité</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

// États pour afficher/masquer les mots de passe
const showPassword = ref(false)
const showConfirmPassword = ref(false)

// Formulaire
const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    account_type: 'Etudiant',
    filiere: '',
})

// Gestion du changement de type de compte
const handleAccountTypeChange = () => {
    // Réinitialiser la filière si on passe de Étudiant à Professeur
    if (form.account_type !== 'Etudiant') {
        form.filiere = ''
    }
}

// Fonction pour obtenir la description de la filière
const getFiliereDescription = (filiereCode) => {
    const descriptions = {
        'IG': 'Informatique et Gestion - Programmation, réseaux et gestion des systèmes informatiques',
        'GC': 'Gestion Commerciale - Marketing, vente et management commercial',
        'GE': 'Gestion des Entreprises - Management, comptabilité et administration des entreprises',
        'GB': 'Gestion des Banques - Finance, opérations bancaires et gestion financière',
        'GRH': 'Gestion des Ressources Humaines - Recrutement, formation et développement du personnel',
        'GTL': 'Gestion des Transports et Logistique - Supply chain, transport et logistique d\'entreprise'
    }
    return descriptions[filiereCode] || ''
}

// Soumission du formulaire
const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    })
}
</script>

<style>
@import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css');
</style>
