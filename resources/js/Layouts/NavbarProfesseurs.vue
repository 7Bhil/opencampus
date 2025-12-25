<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Navbar Horizontale -->
        <nav class="navbar fixed top-0 left-0 right-0 z-50 bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg">
            <div class="container mx-auto px-6 py-3 flex justify-between items-center">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <span class="text-2xl">🎓</span>
                    <span class="text-xl font-bold">OpenCampus</span>
                </div>

                <!-- Menu Navigation -->
                <div class="hidden md:flex items-center space-x-6">
                    <Link
                        :href="route('professeur.dashboard')"
                        class="nav-link hover:text-indigo-200 transition-colors duration-200"
                        :class="{ 'active': $page.url === '/professeur/dashboard' }"
                    >
                        Accueil
                    </Link>
                    <Link
                        :href="route('professeur.dashboard')"
                        class="nav-link hover:text-indigo-200 transition-colors duration-200"
                        :class="{ 'active': $page.url.startsWith('/professeur/publications') }"
                    >
                        Mes publications
                    </Link>
                    <Link
                        :href="route('professeur.devoirs.index')"
                        class="nav-link hover:text-indigo-200 transition-colors duration-200"
                        :class="{ 'active': $page.url.startsWith('/professeur/devoirs') }"
                    >
                        Devoirs
                    </Link>
                    <Link
                        :href="route('professeur.dashboard')"
                        class="nav-link hover:text-indigo-200 transition-colors duration-200"
                        :class="{ 'active': $page.url.startsWith('/professeur/etudiants') }"
                    >
                        Étudiants
                    </Link>
                </div>

                <!-- Menu Utilisateur -->
                <div class="flex items-center space-x-4">
                    <!-- Notification -->
                    <button class="relative p-2 hover:bg-white/10 rounded-full transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                    <!-- Avatar avec dropdown au clic -->
                    <div class="relative" ref="dropdownContainer">
                        <button
                            @click="showUserMenu = !showUserMenu"
                            class="flex items-center space-x-2 focus:outline-none"
                        >
                            <div class="w-10 h-10 bg-white text-indigo-600 rounded-full flex items-center justify-center font-bold">
                                {{ $page.props.auth.user.name.substring(0, 2).toUpperCase() }}
                            </div>
                            <span class="hidden md:inline">{{ $page.props.auth.user.name }}</span>
                            <svg
                                class="w-4 h-4 transition-transform duration-200"
                                :class="{ 'rotate-180': showUserMenu }"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div
                            v-show="showUserMenu"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50 border border-gray-100"
                        >
                            <Link
                                :href="route('profile.edit')"
                                @click="showUserMenu = false"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors"
                            >
                                👤 Mon profil
                            </Link>
                            <Link
                                :href="route('professeur.dashboard')"
                                @click="showUserMenu = false"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors"
                            >
                                ⚙️ Paramètres
                            </Link>
                            <hr class="my-1 border-gray-200">

                            <!-- Formulaire de déconnexion -->
                            <form @submit.prevent="logout" class="w-full">
                                <button
                                    type="submit"
                                    class="block w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 transition-colors"
                                >
                                    🔓 Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Sidebar Verticale -->
        <aside class="sidebar fixed left-0 top-0 h-full w-64 bg-white shadow-xl pt-16 z-40">
            <div class="py-4 px-3">
                <!-- Recherche -->
                <div class="mb-6 px-3">
                    <div class="relative">
                        <input
                            type="text"
                            placeholder="Rechercher..."
                            class="w-full pl-10 pr-4 py-2 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white"
                        >
                        <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Menu Sidebar -->
                <nav class="space-y-1">
                    <Link
                        :href="route('professeur.dashboard')"
                        class="sidebar-item group"
                        :class="{ 'active': $page.url === '/professeur/dashboard' }"
                    >
                        <span class="sidebar-icon">📊</span>
                        <span class="sidebar-label">Tableau de bord</span>
                    </Link>

                    <Link
                        :href="route('professeur.cours.index')"
                        class="sidebar-item group"
                        :class="{ 'active': $page.url.startsWith('/professeur/cours') }"
                    >
                        <span class="sidebar-icon">📚</span>
                        <span class="sidebar-label">Mes cours</span>
                    </Link>

                    <Link
                        :href="route('professeur.cours.create')"
                        class="sidebar-item group"
                        :class="{ 'active': $page.url.startsWith('/professeur/cours/create') }"
                    >
                        <span class="sidebar-icon">📝</span>
                        <span class="sidebar-label">Publier</span>
                    </Link>

                    <Link
                        :href="route('professeur.devoirs.index')"
                        class="sidebar-item group"
                        :class="{ 'active': $page.url.startsWith('/professeur/devoirs') }"
                    >
                        <span class="sidebar-icon">📋</span>
                        <span class="sidebar-label">Devoirs</span>
                    </Link>

                    <Link
                        :href="route('professeur.dashboard')"
                        class="sidebar-item group"
                        :class="{ 'active': $page.url.startsWith('/professeur/etudiants') }"
                    >
                        <span class="sidebar-icon">👥</span>
                        <span class="sidebar-label">Étudiants</span>
                    </Link>

                    <Link
                        :href="route('professeur.dashboard')"
                        class="sidebar-item group"
                        :class="{ 'active': $page.url.startsWith('/professeur/statistiques') }"
                    >
                        <span class="sidebar-icon">📈</span>
                        <span class="sidebar-label">Statistiques</span>
                    </Link>

                    <Link
                        :href="route('professeur.dashboard')"
                        class="sidebar-item group"
                        :class="{ 'active': $page.url.startsWith('/professeur/telechargements') }"
                    >
                        <span class="sidebar-icon">📄</span>
                        <span class="sidebar-label">Téléchargements</span>
                    </Link>

                    <Link
                        :href="route('professeur.dashboard')"
                        class="sidebar-item group"
                        :class="{ 'active': $page.url.startsWith('/professeur/formations') }"
                    >
                        <span class="sidebar-icon">💬</span>
                        <span class="sidebar-label">Formations</span>
                    </Link>

                    <hr class="my-2 border-gray-200">

                    <Link
                        :href="route('professeur.dashboard')"
                        class="sidebar-item group"
                    >
                        <span class="sidebar-icon">❓</span>
                        <span class="sidebar-label">Aide & Support</span>
                    </Link>
                </nav>
            </div>
        </aside>

        <!-- Contenu Principal -->
        <main class="main-content ml-0 md:ml-64 pt-16">
            <slot />
        </main>

        <!-- Menu Mobile -->
        <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50">
            <div class="flex justify-around py-3">
                <Link
                    :href="route('professeur.dashboard')"
                    class="flex flex-col items-center text-gray-600 hover:text-indigo-600"
                >
                    <span class="text-xl">📊</span>
                    <span class="text-xs mt-1">Accueil</span>
                </Link>
                <Link
                    :href="route('professeur.cours.index')"
                    class="flex flex-col items-center text-gray-600 hover:text-indigo-600"
                >
                    <span class="text-xl">📚</span>
                    <span class="text-xs mt-1">Cours</span>
                </Link>
                <Link
                    :href="route('professeur.dashboard')"
                    class="flex flex-col items-center text-gray-600 hover:text-indigo-600"
                >
                    <span class="text-xl">📝</span>
                    <span class="text-xs mt-1">Publier</span>
                </Link>
                <Link
                    :href="route('professeur.dashboard')"
                    class="flex flex-col items-center text-gray-600 hover:text-indigo-600"
                >
                    <span class="text-xl">📋</span>
                    <span class="text-xs mt-1">Devoirs</span>
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'

const showUserMenu = ref(false)
const dropdownContainer = ref(null)

// Méthode de déconnexion
const logout = () => {
    showUserMenu.value = false
    router.post(route('logout'))
}

// Fermer le menu en cliquant à l'extérieur
const handleClickOutside = (event) => {
    if (dropdownContainer.value && !dropdownContainer.value.contains(event.target)) {
        showUserMenu.value = false
    }
}

onMounted(() => {
    document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside)
})
</script>

<style scoped>
.navbar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.nav-link {
    @apply px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200;
}

.nav-link.active {
    @apply bg-white/20 text-white;
}

.sidebar {
    @apply border-r border-gray-200;
    height: 100vh;
}

.sidebar-item {
    @apply flex items-center px-4 py-3 text-gray-700 rounded-lg transition-all duration-200 hover:bg-indigo-50 hover:text-indigo-700;
}

.sidebar-item.active {
    @apply bg-indigo-600 text-white hover:bg-indigo-700;
}

.sidebar-icon {
    @apply mr-3 text-lg;
}

.sidebar-label {
    @apply text-sm font-medium;
}

.main-content {
    min-height: calc(100vh - 4rem);
}

.rotate-180 {
    transform: rotate(180deg);
}

/* Responsive */
@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
    }

    .main-content {
        margin-left: 0;
    }
}
</style>
