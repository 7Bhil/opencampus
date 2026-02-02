<template>
    <NavbarUser>
        <Head title="Paramètres" />

        <div class="max-w-7xl mx-auto">
            <!-- En-tête -->
            <div class="mb-8">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">
                            Paramètres ⚙️
                        </h1>
                        <p class="text-gray-600 text-lg">
                            Gérez vos préférences et vos informations personnelles
                        </p>
                    </div>
                    <button @click="saveSettings" class="bg-[#5b7ce6] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#4a6bcf] transition-colors flex items-center gap-2">
                        <span>💾</span>
                        Enregistrer les modifications
                    </button>
                </div>
            </div>

            <!-- Sections principales -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Menu latéral -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-32">
                        <nav class="space-y-2">
                            <button
                                v-for="tab in tabs"
                                :key="tab.id"
                                @click="activeTab = tab.id"
                                class="w-full text-left p-4 rounded-xl transition-all font-medium flex items-center gap-3"
                                :class="activeTab === tab.id
                                    ? 'bg-[#5b7ce6] text-white'
                                    : 'text-gray-600 hover:bg-gray-100'"
                            >
                                <span class="text-xl">{{ tab.icon }}</span>
                                <span>{{ tab.label }}</span>
                            </button>
                        </nav>
                    </div>
                </div>

                <!-- Contenu des paramètres -->
                <div class="lg:col-span-2">
                    <!-- Profil -->
                    <div v-if="activeTab === 'profile'" class="bg-white rounded-2xl shadow-lg p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <span>👤</span>
                            Informations personnelles
                        </h2>

                        <div class="space-y-6">
                            <!-- Photo de profil -->
                            <div class="flex items-center gap-6 mb-8">
                                <div class="relative">
                                    <div class="w-24 h-24 bg-gradient-to-r from-[#5b7ce6] to-[#b567b8] rounded-full flex items-center justify-center text-white text-3xl font-bold">
                                        {{ userInitial }}
                                    </div>
                                    <button class="absolute -bottom-2 -right-2 bg-white p-2 rounded-full shadow-lg hover:scale-110 transition-transform">
                                        <span class="text-gray-600">📷</span>
                                    </button>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Photo de profil</h3>
                                    <p class="text-sm text-gray-500">PNG, JPG jusqu'à 5MB</p>
                                </div>
                            </div>

                            <!-- Formulaire -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Nom complet
                                    </label>
                                    <input
                                        type="text"
                                        v-model="userProfile.name"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5b7ce6] focus:border-transparent transition-all"
                                        placeholder="Votre nom"
                                    />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Email
                                    </label>
                                    <input
                                        type="email"
                                        v-model="userProfile.email"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5b7ce6] focus:border-transparent transition-all"
                                        placeholder="votre@email.com"
                                    />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Téléphone
                                    </label>
                                    <input
                                        type="tel"
                                        v-model="userProfile.phone"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5b7ce6] focus:border-transparent transition-all"
                                        placeholder="+33 1 23 45 67 89"
                                    />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Date de naissance
                                    </label>
                                    <input
                                        type="date"
                                        v-model="userProfile.birthdate"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5b7ce6] focus:border-transparent transition-all"
                                    />
                                </div>
                            </div>

                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    À propos de moi
                                </label>
                                <textarea
                                    v-model="userProfile.bio"
                                    rows="4"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5b7ce6] focus:border-transparent transition-all"
                                    placeholder="Parlez-nous un peu de vous..."
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Notifications -->
                    <div v-if="activeTab === 'notifications'" class="bg-white rounded-2xl shadow-lg p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <span>🔔</span>
                            Paramètres de notifications
                        </h2>

                        <div class="space-y-6">
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                                <div>
                                    <h3 class="font-semibold text-gray-900">Notifications par email</h3>
                                    <p class="text-sm text-gray-500">Recevoir des emails pour les nouvelles activités</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" v-model="notifications.email" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#5b7ce6]"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                                <div>
                                    <h3 class="font-semibold text-gray-900">Notifications de cours</h3>
                                    <p class="text-sm text-gray-500">Nouveaux cours et mises à jour</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" v-model="notifications.courses" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#5b7ce6]"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                                <div>
                                    <h3 class="font-semibold text-gray-900">Notifications de devoirs</h3>
                                    <p class="text-sm text-gray-500">Rappels et dates limites</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" v-model="notifications.assignments" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#5b7ce6]"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                                <div>
                                    <h3 class="font-semibold text-gray-900">Messages privés</h3>
                                    <p class="text-sm text-gray-500">Notifications de nouveaux messages</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" v-model="notifications.messages" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#5b7ce6]"></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Sécurité -->
                    <div v-if="activeTab === 'security'" class="bg-white rounded-2xl shadow-lg p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <span>🔒</span>
                            Sécurité et confidentialité
                        </h2>

                        <div class="space-y-6">
                            <!-- Changement de mot de passe -->
                            <div class="p-6 bg-gray-50 rounded-xl">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Changer le mot de passe</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Mot de passe actuel
                                        </label>
                                        <input
                                            type="password"
                                            v-model="security.currentPassword"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5b7ce6] focus:border-transparent transition-all"
                                            placeholder="••••••••"
                                        />
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Nouveau mot de passe
                                            </label>
                                            <input
                                                type="password"
                                                v-model="security.newPassword"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5b7ce6] focus:border-transparent transition-all"
                                                placeholder="••••••••"
                                            />
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Confirmer le mot de passe
                                            </label>
                                            <input
                                                type="password"
                                                v-model="security.confirmPassword"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5b7ce6] focus:border-transparent transition-all"
                                                placeholder="••••••••"
                                            />
                                        </div>
                                    </div>
                                    <button @click="updatePassword" class="mt-4 bg-[#5b7ce6] text-white px-4 py-2 rounded-lg font-semibold hover:bg-[#4a6bcf] transition-colors">
                                        Mettre à jour le mot de passe
                                    </button>
                                </div>
                            </div>

                            <!-- Authentification à deux facteurs -->
                            <div class="p-6 bg-gray-50 rounded-xl">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">Authentification à deux facteurs</h3>
                                        <p class="text-sm text-gray-500">Ajoutez une couche de sécurité supplémentaire</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" v-model="security.twoFactorEnabled" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#5b7ce6]"></div>
                                    </label>
                                </div>
                                <p v-if="security.twoFactorEnabled" class="text-sm text-green-600 flex items-center gap-1">
                                    <span>✅</span> Activée
                                </p>
                            </div>

                            <!-- Sessions actives -->
                            <div class="p-6 bg-gray-50 rounded-xl">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Sessions actives</h3>
                                <div class="space-y-3">
                                    <div v-for="session in activeSessions" :key="session.id" class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-200">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ session.device }}</p>
                                            <p class="text-sm text-gray-500">{{ session.location }} • {{ session.lastActive }}</p>
                                        </div>
                                        <button v-if="!session.current" @click="terminateSession(session.id)" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                            Déconnecter
                                        </button>
                                        <span v-else class="text-sm text-[#5b7ce6] font-medium">Session actuelle</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Apparence -->
                    <div v-if="activeTab === 'appearance'" class="bg-white rounded-2xl shadow-lg p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <span>🎨</span>
                            Apparence et thème
                        </h2>

                        <div class="space-y-6">
                            <!-- Thème -->
                            <div class="p-6 bg-gray-50 rounded-xl">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Thème</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <button
                                        @click="appearance.theme = 'light'"
                                        class="p-4 border-2 rounded-xl text-left transition-all"
                                        :class="appearance.theme === 'light'
                                            ? 'border-[#5b7ce6] bg-blue-50'
                                            : 'border-gray-200 hover:border-gray-300'"
                                    >
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="w-6 h-6 rounded-full bg-yellow-400 flex items-center justify-center">
                                                <span class="text-xs">☀️</span>
                                            </div>
                                            <span class="font-medium">Clair</span>
                                        </div>
                                        <p class="text-sm text-gray-500">Thème clair par défaut</p>
                                    </button>

                                    <button
                                        @click="appearance.theme = 'dark'"
                                        class="p-4 border-2 rounded-xl text-left transition-all"
                                        :class="appearance.theme === 'dark'
                                            ? 'border-[#5b7ce6] bg-blue-50'
                                            : 'border-gray-200 hover:border-gray-300'"
                                    >
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="w-6 h-6 rounded-full bg-gray-800 flex items-center justify-center">
                                                <span class="text-xs text-white">🌙</span>
                                            </div>
                                            <span class="font-medium">Sombre</span>
                                        </div>
                                        <p class="text-sm text-gray-500">Thème sombre relaxant</p>
                                    </button>

                                    <button
                                        @click="appearance.theme = 'auto'"
                                        class="p-4 border-2 rounded-xl text-left transition-all"
                                        :class="appearance.theme === 'auto'
                                            ? 'border-[#5b7ce6] bg-blue-50'
                                            : 'border-gray-200 hover:border-gray-300'"
                                    >
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="w-6 h-6 rounded-full bg-gradient-to-r from-yellow-400 to-gray-800 flex items-center justify-center">
                                                <span class="text-xs">⚡</span>
                                            </div>
                                            <span class="font-medium">Automatique</span>
                                        </div>
                                        <p class="text-sm text-gray-500">Suit les paramètres système</p>
                                    </button>
                                </div>
                            </div>

                            <!-- Taille des polices -->
                            <div class="p-6 bg-gray-50 rounded-xl">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Taille des polices</h3>
                                <div class="flex items-center gap-4">
                                    <button
                                        @click="appearance.fontSize = 'small'"
                                        class="px-4 py-2 rounded-lg transition-all"
                                        :class="appearance.fontSize === 'small'
                                            ? 'bg-[#5b7ce6] text-white'
                                            : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                                    >
                                        Petite
                                    </button>
                                    <button
                                        @click="appearance.fontSize = 'medium'"
                                        class="px-4 py-2 rounded-lg transition-all"
                                        :class="appearance.fontSize === 'medium'
                                            ? 'bg-[#5b7ce6] text-white'
                                            : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                                    >
                                        Moyenne
                                    </button>
                                    <button
                                        @click="appearance.fontSize = 'large'"
                                        class="px-4 py-2 rounded-lg transition-all"
                                        :class="appearance.fontSize === 'large'
                                            ? 'bg-[#5b7ce6] text-white'
                                            : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                                    >
                                        Grande
                                    </button>
                                </div>
                            </div>

                            <!-- Couleurs d'accent -->
                            <div class="p-6 bg-gray-50 rounded-xl">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Couleur d'accent</h3>
                                <div class="flex flex-wrap gap-3">
                                    <button
                                        v-for="color in accentColors"
                                        :key="color.name"
                                        @click="appearance.accentColor = color.name"
                                        class="w-10 h-10 rounded-full border-2 transition-transform hover:scale-110"
                                        :class="appearance.accentColor === color.name ? 'border-gray-800' : 'border-gray-300'"
                                        :style="{ backgroundColor: color.value }"
                                        :title="color.label"
                                    >
                                        <span v-if="appearance.accentColor === color.name" class="text-white text-sm">✓</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </NavbarUser>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import NavbarUser from '../../Layouts/NavbarEtudiants.vue';

// État de l'onglet actif
const activeTab = ref('profile');

// Tabs disponibles
const tabs = ref([
    { id: 'profile', label: 'Profil', icon: '👤' },
    { id: 'notifications', label: 'Notifications', icon: '🔔' },
    { id: 'security', label: 'Sécurité', icon: '🔒' },
    { id: 'appearance', label: 'Apparence', icon: '🎨' },
]);

// Données utilisateur
const page = usePage();
const userInitial = computed(() => {
    return page.props.auth?.user?.name?.charAt(0).toUpperCase() || 'U';
});

// Profil utilisateur
const userProfile = ref({
    name: page.props.auth?.user?.name || '',
    email: page.props.auth?.user?.email || '',
    phone: '+33 1 23 45 67 89',
    birthdate: '1995-06-15',
    bio: 'Étudiant passionné par l\'informatique et les nouvelles technologies.'
});

// Paramètres de notifications
const notifications = ref({
    email: true,
    courses: true,
    assignments: true,
    messages: false
});

// Paramètres de sécurité
const security = ref({
    currentPassword: '',
    newPassword: '',
    confirmPassword: '',
    twoFactorEnabled: false
});

// Sessions actives
const activeSessions = ref([
    {
        id: 1,
        device: 'Firefox sur Ubuntu',
        location: 'Paris, France',
        lastActive: 'Maintenant',
        current: true
    },
    {
        id: 2,
        device: 'Chrome sur Windows',
        location: 'Lyon, France',
        lastActive: 'Il y a 2 heures',
        current: false
    },
    {
        id: 3,
        device: 'Safari sur iPhone',
        location: 'Marseille, France',
        lastActive: 'Il y a 3 jours',
        current: false
    }
]);

// Paramètres d'apparence
const appearance = ref({
    theme: 'light',
    fontSize: 'medium',
    accentColor: 'blue'
});

// Couleurs d'accent disponibles
const accentColors = ref([
    { name: 'blue', value: '#5b7ce6', label: 'Bleu' },
    { name: 'purple', value: '#9b6ec9', label: 'Violet' },
    { name: 'pink', value: '#b567b8', label: 'Rose' },
    { name: 'green', value: '#10b981', label: 'Vert' },
    { name: 'orange', value: '#f59e0b', label: 'Orange' },
    { name: 'red', value: '#ef4444', label: 'Rouge' }
]);

// Méthodes
const saveSettings = () => {
    // Ici, vous enverriez les données au backend

    // Simulation de succès
    alert('Paramètres enregistrés avec succès !');
};

const updatePassword = () => {
    if (!security.value.currentPassword) {
        alert('Veuillez entrer votre mot de passe actuel');
        return;
    }

    if (security.value.newPassword !== security.value.confirmPassword) {
        alert('Les mots de passe ne correspondent pas');
        return;
    }

    if (security.value.newPassword.length < 8) {
        alert('Le mot de passe doit contenir au moins 8 caractères');
        return;
    }

    // Ici, vous enverriez la demande de changement au backend

    alert('Mot de passe mis à jour avec succès !');
    security.value.currentPassword = '';
    security.value.newPassword = '';
    security.value.confirmPassword = '';
};

const terminateSession = (sessionId) => {
    activeSessions.value = activeSessions.value.filter(session => session.id !== sessionId);
    // Ici, vous enverriez la demande au backend
    alert('Session déconnectée avec succès');
};
</script>

<style scoped>
/* Style pour les boutons radio personnalisés */
input[type="checkbox"]:checked ~ .peer-checked\:bg-\[\#5b7ce6\] {
    background-color: #5b7ce6;
}
</style>
