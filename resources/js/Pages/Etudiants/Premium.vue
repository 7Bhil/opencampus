<template>
    <NavbarUser>
        <Head title="Premium" />

        <div class="max-w-7xl mx-auto">
            <!-- En-tête -->
            <div class="mb-10 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 mb-6 rounded-full bg-gradient-to-r from-yellow-400 to-orange-500">
                    <span class="text-3xl">💎</span>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Passez à <span class="bg-gradient-to-r from-yellow-400 to-orange-500 bg-clip-text text-transparent">Premium</span>
                </h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Débloquez des fonctionnalités exclusives et maximisez votre apprentissage
                </p>
            </div>

            <!-- Comparaison des plans -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">Choisissez votre plan</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Plan Gratuit -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 border-2 border-gray-200">
                        <div class="text-center mb-8">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Gratuit</h3>
                            <div class="text-4xl font-bold text-gray-900 mb-2">0 f</div>
                            <p class="text-gray-500">Par mois</p>
                        </div>

                        <ul class="space-y-4 mb-8">
                            <li class="flex items-center gap-3">
                                <span class="text-green-500">✓</span>
                                <span>Accès aux cours de base</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="text-green-500">✓</span>
                                <span>3 devoirs par mois</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="text-green-500">✓</span>
                                <span>Support communautaire</span>
                            </li>
                            <li class="flex items-center gap-3 text-gray-400">
                                <span>✗</span>
                                <span class="line-through">Cours certifiants</span>
                            </li>
                            <li class="flex items-center gap-3 text-gray-400">
                                <span>✗</span>
                                <span class="line-through">Téléchargement illimité</span>
                            </li>
                            <li class="flex items-center gap-3 text-gray-400">
                                <span>✗</span>
                                <span class="line-through">Support prioritaire</span>
                            </li>
                        </ul>

                        <button
                            :class="currentPlan === 'free'
                                ? 'w-full bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-semibold cursor-default'
                                : 'w-full border-2 border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:border-gray-400 transition-colors'"
                            disabled
                        >
                            {{ currentPlan === 'free' ? 'Plan actuel' : 'Sélectionner' }}
                        </button>
                    </div>

                    <!-- Plan Premium (Recommandé) -->
                    <div class="bg-white rounded-2xl shadow-2xl p-8 border-2 border-orange-500 relative transform scale-105">
                        <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                            <span class="bg-gradient-to-r from-orange-500 to-red-500 text-white px-4 py-1 rounded-full text-sm font-semibold">
                                POPULAIRE
                            </span>
                        </div>

                        <div class="text-center mb-8">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Premium</h3>
                            <div class="flex items-center justify-center gap-2">
                                <div class="text-5xl font-bold text-gray-900">9,99 f</div>
                                <div class="text-left">
                                    <div class="text-gray-500 text-sm">Par mois</div>
                                    <div class="text-green-600 text-sm font-semibold">Économisez 20%</div>
                                </div>
                            </div>
                        </div>

                        <ul class="space-y-4 mb-8">
                            <li class="flex items-center gap-3">
                                <span class="text-green-500">✓</span>
                                <span class="font-semibold">Tous les cours Premium</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="text-green-500">✓</span>
                                <span>Devoirs illimités</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="text-green-500">✓</span>
                                <span>Certifications incluses</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="text-green-500">✓</span>
                                <span>Téléchargement illimité</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="text-green-500">✓</span>
                                <span>Support prioritaire 24/7</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="text-green-500">✓</span>
                                <span>Contenu exclusif</span>
                            </li>
                        </ul>

                        <button
                            @click="selectedPlan = 'premium-monthly'"
                            :class="currentPlan === 'premium'
                                ? 'w-full bg-gradient-to-r from-orange-500 to-red-500 text-white px-6 py-3 rounded-lg font-semibold cursor-default'
                                : 'w-full bg-gradient-to-r from-orange-500 to-red-500 text-white px-6 py-3 rounded-lg font-semibold hover:opacity-90 transition-opacity'"
                        >
                            {{ currentPlan === 'premium' ? 'Plan actuel' : 'Sélectionner ce plan' }}
                        </button>
                    </div>

                    <!-- Plan Annuel -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 border-2 border-purple-500">
                        <div class="text-center mb-8">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Premium Annuel</h3>
                            <div class="flex items-center justify-center gap-2">
                                <div class="text-4xl font-bold text-gray-900">7,99 f</div>
                                <div class="text-left">
                                    <div class="text-gray-500 text-sm">Par mois</div>
                                    <div class="text-purple-600 text-sm font-semibold">95,88 f par an</div>
                                </div>
                            </div>
                            <div class="mt-2 text-green-600 font-semibold">
                                Économisez 35%
                            </div>
                        </div>

                        <ul class="space-y-4 mb-8">
                            <li class="flex items-center gap-3">
                                <span class="text-green-500">✓</span>
                                <span>Toutes les fonctionnalités Premium</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="text-green-500">✓</span>
                                <span class="font-semibold">2 mois gratuits</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="text-green-500">✓</span>
                                <span>Accès anticipé aux nouveautés</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="text-green-500">✓</span>
                                <span>Webinaires exclusifs</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="text-green-500">✓</span>
                                <span>Coach personnel</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="text-green-500">✓</span>
                                <span>Badge Premium</span>
                            </li>
                        </ul>

                        <button
                            @click="selectedPlan = 'premium-yearly'"
                            :class="currentPlan === 'premium-yearly'
                                ? 'w-full bg-gradient-to-r from-purple-500 to-indigo-500 text-white px-6 py-3 rounded-lg font-semibold cursor-default'
                                : 'w-full bg-gradient-to-r from-purple-500 to-indigo-500 text-white px-6 py-3 rounded-lg font-semibold hover:opacity-90 transition-opacity'"
                        >
                            {{ currentPlan === 'premium-yearly' ? 'Plan actuel' : 'Économiser 35%' }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Fonctionnalités Premium -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-8 mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">Fonctionnalités exclusives Premium</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <div class="w-12 h-12 mb-4 rounded-lg bg-blue-100 flex items-center justify-center">
                            <span class="text-2xl">📚</span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Cours certifiants</h3>
                        <p class="text-gray-600">
                            Obtenez des certifications reconnues par les entreprises après chaque parcours.
                        </p>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <div class="w-12 h-12 mb-4 rounded-lg bg-green-100 flex items-center justify-center">
                            <span class="text-2xl">⚡</span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Accès anticipé</h3>
                        <p class="text-gray-600">
                            Soyez les premiers à découvrir les nouveaux cours et fonctionnalités.
                        </p>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <div class="w-12 h-12 mb-4 rounded-lg bg-purple-100 flex items-center justify-center">
                            <span class="text-2xl">🎓</span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Mentorat personnalisé</h3>
                        <p class="text-gray-600">
                            Sessions individuelles avec des experts pour progresser plus vite.
                        </p>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <div class="w-12 h-12 mb-4 rounded-lg bg-yellow-100 flex items-center justify-center">
                            <span class="text-2xl">💻</span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Projets pratiques</h3>
                        <p class="text-gray-600">
                            Travaillez sur des projets réels avec feedback détaillé.
                        </p>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <div class="w-12 h-12 mb-4 rounded-lg bg-red-100 flex items-center justify-center">
                            <span class="text-2xl">📊</span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Analyses avancées</h3>
                        <p class="text-gray-600">
                            Suivez votre progression avec des statistiques détaillées.
                        </p>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <div class="w-12 h-12 mb-4 rounded-lg bg-indigo-100 flex items-center justify-center">
                            <span class="text-2xl">👥</span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Communauté exclusive</h3>
                        <p class="text-gray-600">
                            Échangez avec d'autres membres Premium et des experts.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Témoignages -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">Ils sont déjà Premium</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full"></div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Marie Dubois</h4>
                                <p class="text-sm text-gray-500">Étudiante en informatique</p>
                            </div>
                        </div>
                        <p class="text-gray-600 italic">
                            "Grâce à Premium, j'ai pu obtenir 3 certifications qui m'ont aidé à décrocher mon stage chez Google !"
                        </p>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-green-400 to-blue-500 rounded-full"></div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Thomas Martin</h4>
                                <p class="text-sm text-gray-500">Développeur freelance</p>
                            </div>
                        </div>
                        <p class="text-gray-600 italic">
                            "Le mentorat personnalisé a totalement transformé ma façon d'apprendre. Je recommande à 100% !"
                        </p>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-orange-400 to-red-500 rounded-full"></div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Sophie Laurent</h4>
                                <p class="text-sm text-gray-500">Étudiante en reconversion</p>
                            </div>
                        </div>
                        <p class="text-gray-600 italic">
                            "Les projets pratiques m'ont donné la confiance nécessaire pour postuler à des emplois tech."
                        </p>
                    </div>
                </div>
            </div>

            <!-- FAQ -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">Questions fréquentes</h2>

                <div class="space-y-6">
                    <div v-for="(faq, index) in faqs" :key="index" class="border-b border-gray-200 pb-6">
                        <button
                            @click="toggleFaq(index)"
                            class="flex justify-between items-center w-full text-left"
                        >
                            <h3 class="text-lg font-semibold text-gray-900">{{ faq.question }}</h3>
                            <span class="text-gray-500 text-xl">
                                {{ activeFaq === index ? '−' : '+' }}
                            </span>
                        </button>
                        <div v-if="activeFaq === index" class="mt-4 text-gray-600">
                            {{ faq.answer }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de paiement -->
            <div v-if="showPaymentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-900">Finaliser l'abonnement</h3>
                        <button @click="showPaymentModal = false" class="text-gray-500 hover:text-gray-700">
                            ✕
                        </button>
                    </div>

                    <!-- Détails de l'abonnement -->
                    <div class="bg-gray-50 rounded-xl p-6 mb-6">
                        <h4 class="font-semibold text-gray-900 mb-4">Récapitulatif</h4>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Plan sélectionné :</span>
                            <span class="font-semibold">{{ getPlanName(selectedPlan) }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Prix :</span>
                            <span class="font-semibold">{{ getPlanPrice(selectedPlan) }}</span>
                        </div>
                        <div class="flex justify-between items-center border-t border-gray-200 pt-4 mt-4">
                            <span class="text-lg font-bold text-gray-900">Total :</span>
                            <span class="text-lg font-bold text-gray-900">{{ getPlanPrice(selectedPlan) }}</span>
                        </div>
                    </div>

                    <!-- Formulaire de paiement -->
                    <form @submit.prevent="processPayment" class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Numéro de carte
                            </label>
                            <div class="relative">
                                <input
                                    type="text"
                                    v-model="payment.cardNumber"
                                    placeholder="1234 5678 9012 3456"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"
                                    maxlength="19"
                                    @input="formatCardNumber"
                                />
                                <div class="absolute right-3 top-3">
                                    <span class="text-2xl">💳</span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Date d'expiration
                                </label>
                                <input
                                    type="text"
                                    v-model="payment.expiryDate"
                                    placeholder="MM/AA"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"
                                    maxlength="5"
                                    @input="formatExpiryDate"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    CVV
                                </label>
                                <input
                                    type="text"
                                    v-model="payment.cvv"
                                    placeholder="123"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"
                                    maxlength="4"
                                />
                            </div>
                        </div>

                        <div>
                            <label class="flex items-center gap-2">
                                <input
                                    type="checkbox"
                                    v-model="payment.saveCard"
                                    class="rounded border-gray-300 text-orange-500 focus:ring-orange-500"
                                />
                                <span class="text-sm text-gray-600">Enregistrer cette carte pour les paiements futurs</span>
                            </label>
                        </div>

                        <button
                            type="submit"
                            :disabled="processingPayment"
                            class="w-full bg-gradient-to-r from-orange-500 to-red-500 text-white px-6 py-3 rounded-lg font-semibold hover:opacity-90 transition-opacity disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                        >
                            <span v-if="processingPayment" class="animate-spin">⏳</span>
                            <span>{{ processingPayment ? 'Traitement...' : `Payer ${getPlanPrice(selectedPlan)}` }}</span>
                        </button>

                        <p class="text-center text-sm text-gray-500">
                            Paiement sécurisé par Stripe. Vos données sont cryptées.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </NavbarUser>
</template>

<script setup>
import { ref, watch } from 'vue';
import { Head } from '@inertiajs/vue3';
import NavbarUser from '../../Layouts/NavbarEtudiants.vue';

// Plan actuel de l'utilisateur
const currentPlan = ref('free'); // 'free', 'premium', 'premium-yearly'

// Plan sélectionné
const selectedPlan = ref('premium-monthly');

// Modal de paiement
const showPaymentModal = ref(false);

// Données de paiement
const payment = ref({
    cardNumber: '',
    expiryDate: '',
    cvv: '',
    saveCard: false
});

// État de traitement
const processingPayment = ref(false);

// FAQ
const activeFaq = ref(null);
const faqs = ref([
    {
        question: "Puis-je annuler mon abonnement à tout moment ?",
        answer: "Oui, vous pouvez annuler votre abonnement Premium à tout moment. Vous conserverez l'accès jusqu'à la fin de votre période de facturation."
    },
    {
        question: "Y a-t-il un engagement ?",
        answer: "Non, aucun engagement. Les abonnements mensuels et annuels sont sans engagement et peuvent être annulés à tout moment."
    },
    {
        question: "Les certifications sont-elles reconnues ?",
        answer: "Oui, nos certifications sont reconnues par de nombreuses entreprises partenaires. Elles attestent de compétences concrètes."
    },
    {
        question: "Puis-je changer de plan ?",
        answer: "Oui, vous pouvez passer d'un plan mensuel à un plan annuel à tout moment. Le changement sera effectif à votre prochaine date de renouvellement."
    },
    {
        question: "Comment fonctionne le remboursement ?",
        answer: "Nous offrons une garantie satisfait ou remboursé de 30 jours. Si vous n'êtes pas satisfait, contactez-nous dans les 30 jours suivant votre achat."
    }
]);

// Méthodes
const toggleFaq = (index) => {
    activeFaq.value = activeFaq.value === index ? null : index;
};

const getPlanName = (plan) => {
    const plans = {
        'premium-monthly': 'Premium Mensuel',
        'premium-yearly': 'Premium Annuel'
    };
    return plans[plan] || 'Premium';
};

const getPlanPrice = (plan) => {
    const prices = {
        'premium-monthly': '9,99 f/mois',
        'premium-yearly': '95,88 f/an'
    };
    return prices[plan] || '9,99 f/mois';
};

const selectPlan = (plan) => {
    selectedPlan.value = plan;
    showPaymentModal.value = true;
};

const formatCardNumber = () => {
    let value = payment.value.cardNumber.replace(/\D/g, '');
    value = value.replace(/(.{4})/g, '$1 ').trim();
    payment.value.cardNumber = value.substring(0, 19);
};

const formatExpiryDate = () => {
    let value = payment.value.expiryDate.replace(/\D/g, '');
    if (value.length >= 2) {
        value = value.substring(0, 2) + '/' + value.substring(2, 4);
    }
    payment.value.expiryDate = value.substring(0, 5);
};

const processPayment = () => {
    processingPayment.value = true;

    // Simulation de traitement de paiement
    setTimeout(() => {
        processingPayment.value = false;
        showPaymentModal.value = false;

        // Mettre à jour le plan de l'utilisateur
        if (selectedPlan.value === 'premium-yearly') {
            currentPlan.value = 'premium-yearly';
        } else {
            currentPlan.value = 'premium';
        }

        // Réinitialiser le formulaire
        payment.value = {
            cardNumber: '',
            expiryDate: '',
            cvv: '',
            saveCard: false
        };

        // Afficher un message de succès
        alert(`Félicitations ! Vous êtes maintenant abonné au plan ${getPlanName(selectedPlan.value)}.`);
    }, 2000);
};

// Mettre à jour le bouton de sélection
watch: {
   watch(selectedPlan, (newPlan) => {
    if (newPlan && !showPaymentModal.value) {
        showPaymentModal.value = true;
    }
});
}
</script>

<style scoped>
/* Animation pour le plan populaire */
.scale-105 {
    transform: scale(1.05);
}

@media (max-width: 768px) {
    .scale-105 {
        transform: scale(1);
    }
}

/* Style pour les entrées de carte */
input[type="text"]::placeholder {
    color: #9CA3AF;
    opacity: 1;
}
</style>
