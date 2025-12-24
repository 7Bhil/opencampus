<template>
    <NavbarUser>
        <Head title="Tableau de bord" />

        <div class="max-w-7xl mx-auto">
            <!-- En-tête -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">
                    Bienvenue, {{ $page.props.auth.user?.name || 'Étudiant' }} 👋
                </h1>
                <p class="text-gray-600 text-lg">
                    Voici un aperçu de votre activité
                </p>
            </div>

            <!-- Statistiques principales -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-8 rounded-2xl shadow-lg border-l-4 border-[#5b7ce6]">
                    <div class="text-5xl font-bold text-[#5b7ce6] mb-2">24</div>
                    <div class="text-gray-500 text-sm">Cours téléchargés</div>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-lg border-l-4 border-[#9b6ec9]">
                    <div class="text-5xl font-bold text-[#9b6ec9] mb-2">8</div>
                    <div class="text-gray-500 text-sm">Devoirs soumis</div>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-lg border-l-4 border-[#b567b8]">
                    <div class="text-5xl font-bold text-[#b567b8] mb-2">156</div>
                    <div class="text-gray-500 text-sm">Points gagnés</div>
                </div>
            </div>

            <!-- Section devoirs urgents -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-6">
                    <span class="text-2xl">📝</span>
                    Devoirs à rendre prochainement
                </h2>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Matière</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Titre</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Date limite</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Statut</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr class="hover:bg-gray-50 transition-colors" v-for="assignment in assignments" :key="assignment.id">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ assignment.subject }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ assignment.title }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ assignment.due_date }}</td>
                                <td class="px-6 py-4">
                                    <span :class="assignment.statusClass">
                                        {{ assignment.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <button :class="assignment.buttonClass">
                                        {{ assignment.buttonText }}
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Section cours récents -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-6">
                    <span class="text-2xl">📚</span>
                    Cours récemment consultés
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div v-for="course in recentCourses" :key="course.id"
                         class="border-2 border-gray-100 rounded-xl p-6 hover:border-[#5b7ce6] hover:shadow-lg transition-all duration-300 cursor-pointer">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ course.title }}</h3>
                                <div class="flex items-center gap-4 text-sm text-gray-500">
                                    <span class="flex items-center gap-1">
                                        <span class="text-sm">🎓</span>
                                        {{ course.professor }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <span class="text-sm">📅</span>
                                        {{ course.date }}
                                    </span>
                                </div>
                            </div>
                            <span :class="course.badgeClass">
                                {{ course.type }}
                            </span>
                        </div>

                        <p class="text-gray-600 text-sm mb-4 leading-relaxed">
                            {{ course.description }}
                        </p>

                        <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                            <div class="flex gap-4 text-sm">
                                <span class="flex items-center gap-1 text-red-500">
                                    <span>❤️</span>
                                    {{ course.likes }}
                                </span>
                                <span class="flex items-center gap-1 text-gray-500">
                                    <span>💬</span>
                                    {{ course.comments }}
                                </span>
                            </div>
                            <button class="text-[#5b7ce6] font-semibold text-sm hover:text-[#4a6bcf] transition-colors">
                                Ouvrir →
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section progression -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mt-8">
                <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-6">
                    <span class="text-2xl">📈</span>
                    Votre progression
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div v-for="progress in progressData" :key="progress.subject" class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-gray-700">{{ progress.subject }}</span>
                            <span class="text-sm text-gray-500">{{ progress.percentage }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-[#5b7ce6] h-2 rounded-full transition-all duration-500"
                                 :style="{ width: progress.percentage + '%' }"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </NavbarUser>
</template>

<script setup>
import { ref } from 'vue'; // Ajoutez cet import
import { Head } from '@inertiajs/vue3';
import NavbarUser from '../../Layouts/NavbarEtudiants.vue';

// Données des devoirs
const assignments = ref([
    {
        id: 1,
        subject: 'Algorithmique',
        title: 'TP3 - Arbres binaires',
        due_date: '02 Nov 2025',
        status: 'À rendre',
        statusClass: 'inline-flex px-3 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full',
        buttonText: 'Soumettre',
        buttonClass: 'bg-[#5b7ce6] text-white px-4 py-2 rounded-lg font-semibold hover:bg-[#4a6bcf] transition-colors'
    },
    {
        id: 2,
        subject: 'Base de données',
        title: 'Projet SQL',
        due_date: '05 Nov 2025',
        status: 'En cours',
        statusClass: 'inline-flex px-3 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full',
        buttonText: 'Continuer',
        buttonClass: 'bg-[#9b6ec9] text-white px-4 py-2 rounded-lg font-semibold hover:bg-[#8a5db8] transition-colors'
    },
    {
        id: 3,
        subject: 'Réseaux',
        title: 'Analyse protocoles TCP/IP',
        due_date: '28 Oct 2025',
        status: 'Rendu',
        statusClass: 'inline-flex px-3 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full',
        buttonText: 'Voir',
        buttonClass: 'bg-[#b567b8] text-white px-4 py-2 rounded-lg font-semibold hover:bg-[#a456a7] transition-colors'
    }
]);

// Données des cours récents
const recentCourses = ref([
    {
        id: 1,
        title: 'Machine Learning Avancé',
        professor: 'Prof. Martin',
        date: '25 Oct 2025',
        type: 'Cours',
        badgeClass: 'px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full',
        description: 'Introduction aux réseaux de neurones convolutionnels et apprentissage par renforcement.',
        likes: 67,
        comments: 18
    },
    {
        id: 2,
        title: 'Développement Web Fullstack',
        professor: 'Prof. Dubois',
        date: '24 Oct 2025',
        type: 'TD',
        badgeClass: 'px-3 py-1 text-xs font-semibold bg-indigo-100 text-indigo-800 rounded-full',
        description: 'Création d\'une application complète avec Laravel, Vue.js et base de données relationnelle.',
        likes: 52,
        comments: 9
    }
]);

// Données de progression
const progressData = ref([
    { subject: 'Algorithmique', percentage: 75 },
    { subject: 'Base de données', percentage: 60 },
    { subject: 'Réseaux', percentage: 45 },
    { subject: 'Mathématiques', percentage: 85 }
]);
</script>
