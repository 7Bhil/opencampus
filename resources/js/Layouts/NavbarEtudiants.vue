<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Navbar Horizontale Fixe -->
        <header class="fixed top-0 left-0 right-0 z-50 bg-gradient-to-r from-[#5b7ce6] via-[#9b6ec9] to-[#b567b8] shadow-lg">
            <div class="mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between items-center">
                    <!-- Logo -->
                    <Link href="/dashboard" class="flex items-center gap-2 sm:gap-3 text-white text-xl sm:text-2xl font-bold hover:opacity-90 transition-opacity">
                        <span class="text-2xl sm:text-3xl">🎓</span>
                        <span class="hidden sm:inline">OpenCampus</span>
                        <span class="sm:hidden">OC</span>
                    </Link>

                    <!-- Navigation Desktop -->
                    <nav class="hidden md:flex gap-4 lg:gap-8 items-center">
                        <!-- Marketplace Cours (pour tous) -->
                        <Link
                            :href="route('cours.index')"
                            class="text-white font-medium hover:opacity-85 transition-opacity text-sm lg:text-base"
                            :class="{ 'border-b-2 border-white': $page.url.startsWith('/cours') && !$page.url.startsWith('/cours/create') }"
                        >
                            Marketplace
                        </Link>

                        <!-- Publier un cours (pour profs et admins) -->
                        <!-- Remplacer toute la section "Publier un cours" par : -->
<Link
    v-if="$page.props.auth.user"
    :href="route('cours.create')"
    class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-medium px-4 py-2 rounded-lg text-sm lg:text-base transition-all duration-300 hover:shadow-lg"
>
    📚 Publier un cours
</Link>

                        <!-- Devenir Premium (étudiants) -->
                        <Link
                            v-else-if="$page.props.auth.user && $page.props.auth.user.account_type === 'Etudiant'"
                            :href="route('etudiant.premium.index')"
                            class="bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white font-medium px-4 py-2 rounded-lg text-sm lg:text-base transition-all duration-300 hover:shadow-lg"
                        >
                            💎 Devenir Premium
                        </Link>

                        <!-- Liens principaux -->
                        <Link
                            :href="route('dashboard.redirect')"
                            class="text-white font-medium hover:opacity-85 transition-opacity text-sm lg:text-base"
                            :class="{ 'border-b-2 border-white': $page.url.startsWith('/etudiant/dashboard') }"
                        >
                            Accueil
                        </Link>
                        <Link
                            :href="route('etudiant.cours.index')"
                            class="text-white font-medium hover:opacity-85 transition-opacity text-sm lg:text-base"
                            :class="{ 'border-b-2 border-white': $page.url.startsWith('/etudiant/cours') && !$page.url.includes('create') }"
                        >
                            Mes cours suivis
                        </Link>
                        <Link
                            :href="route('etudiant.devoirs.index')"
                            class="text-white font-medium hover:opacity-85 transition-opacity text-sm lg:text-base"
                            :class="{ 'border-b-2 border-white': $page.url.startsWith('/etudiant/devoirs') }"
                        >
                            Devoirs
                        </Link>
                        <Link
                            :href="route('etudiant.premium.index')"
                            class="text-white font-medium hover:opacity-85 transition-opacity text-sm lg:text-base"
                            :class="{ 'border-b-2 border-white': $page.url.startsWith('/etudiant/premium') }"
                        >
                            Premium
                        </Link>

                        <!-- Avatar utilisateur avec dropdown -->
                        <div class="relative">
                            <button
                                @click="toggleUserMenu"
                                class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-[#5b7ce6] font-bold text-sm hover:scale-105 transition-transform"
                            >
                                {{ userInitial }}
                            </button>

                            <!-- Dropdown menu -->
                            <div
                                v-show="showUserMenu"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 border border-gray-100 z-50"
                            >
                                <!-- Mes cours publiés (pour profs et admins) -->
                                <Link
                                    v-if="$page.props.auth.user && ['Professeur', 'Admin'].includes($page.props.auth.user.account_type)"
                                    :href="route('professeur.mes-cours')"
                                    class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors"
                                    @click="closeAllMenus"
                                >
                                    <span>📚</span>
                                    <span>Mes cours publiés</span>
                                </Link>

                                <Link
                                    :href="route('profile.edit')"
                                    class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors"
                                    @click="closeAllMenus"
                                >
                                    <span>👤</span>
                                    <span>Mon profil</span>
                                </Link>
                                <Link
                                    :href="route('etudiant.settings')"
                                    class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors"
                                    @click="closeAllMenus"
                                >
                                    <span>⚙️</span>
                                    <span>Paramètres</span>
                                </Link>
                                <hr class="my-2 border-gray-200">
                                <Link
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                    class="flex items-center gap-2 px-4 py-2 text-red-600 hover:bg-red-50 transition-colors w-full text-left"
                                    @click="closeAllMenus"
                                >
                                    <span>🚪</span>
                                    <span>Déconnexion</span>
                                </Link>
                            </div>
                        </div>
                    </nav>

                    <!-- Hamburger Menu Mobile -->
                    <div class="md:hidden flex items-center">
                        <button
                            @click="toggleMobileMenu"
                            class="inline-flex items-center justify-center p-2 rounded-md text-white hover:bg-white/10 transition duration-150 ease-in-out"
                            aria-label="Menu principal"
                        >
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path
                                    v-show="!showMobileMenu"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                                <path
                                    v-show="showMobileMenu"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Menu Mobile Déroulant -->
            <Transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0 transform -translate-y-2"
                enter-to-class="opacity-100 transform translate-y-0"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="opacity-100 transform translate-y-0"
                leave-to-class="opacity-0 transform -translate-y-2"
            >
                <div
                    v-show="showMobileMenu"
                    class="md:hidden bg-white/10 backdrop-blur-sm border-t border-white/20"
                >
                    <div class="space-y-1 px-4 pb-3 pt-2">
                        <!-- Marketplace Cours -->
                        <Link
                            :href="route('cours.index')"
                            class="block px-3 py-2 rounded-md text-white font-medium hover:bg-white/20 transition-colors"
                            @click="closeMobileMenu"
                        >
                            🛒 Marketplace
                        </Link>

                        <!-- Remplacer toute la section "Publier un cours" par : -->
<Link
    v-if="$page.props.auth.user"
    :href="route('cours.create')"
    class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-medium px-4 py-2 rounded-lg text-sm lg:text-base transition-all duration-300 hover:shadow-lg"
>
    📚 Publier un cours
</Link>

                        <!-- Devenir Premium (étudiants) -->
                        <Link
                            v-else-if="$page.props.auth.user && $page.props.auth.user.account_type === 'Etudiant'"
                            :href="route('etudiant.premium.index')"
                            class="block px-3 py-2 rounded-md text-white font-medium hover:bg-white/20 transition-colors bg-purple-500/30"
                            @click="closeMobileMenu"
                        >
                            💎 Devenir Premium
                        </Link>

                        <!-- Mes cours publiés (pour profs et admins) -->
                        <Link
                            v-if="$page.props.auth.user && ['Professeur', 'Admin'].includes($page.props.auth.user.account_type)"
                            :href="route('professeur.mes-cours')"
                            class="block px-3 py-2 rounded-md text-white font-medium hover:bg-white/20 transition-colors"
                            @click="closeMobileMenu"
                        >
                            📖 Mes cours publiés
                        </Link>

                        <!-- Liens principaux -->
                        <Link
                            :href="route('dashboard.redirect')"
                            class="block px-3 py-2 rounded-md text-white font-medium hover:bg-white/20 transition-colors"
                            @click="closeMobileMenu"
                        >
                            🏠 Accueil
                        </Link>
                        <Link
                            :href="route('etudiant.cours.index')"
                            class="block px-3 py-2 rounded-md text-white font-medium hover:bg-white/20 transition-colors"
                            @click="closeMobileMenu"
                        >
                            📚 Mes cours suivis
                        </Link>
                        <Link
                            :href="route('etudiant.devoirs.index')"
                            class="block px-3 py-2 rounded-md text-white font-medium hover:bg-white/20 transition-colors"
                            @click="closeMobileMenu"
                        >
                            📝 Mes devoirs
                        </Link>
                        <Link
                            :href="route('etudiant.premium.index')"
                            class="block px-3 py-2 rounded-md text-white font-medium hover:bg-white/20 transition-colors"
                            @click="closeMobileMenu"
                        >
                            💎 Premium
                        </Link>
                        <Link
                            :href="route('etudiant.notifications')"
                            class="block px-3 py-2 rounded-md text-white font-medium hover:bg-white/20 transition-colors"
                            @click="closeMobileMenu"
                        >
                            🔔 Notifications
                        </Link>
                        <Link
                            :href="route('profile.edit')"
                            class="block px-3 py-2 rounded-md text-white font-medium hover:bg-white/20 transition-colors"
                            @click="closeMobileMenu"
                        >
                            👤 Mon profil
                        </Link>
                        <Link
                            :href="route('etudiant.settings')"
                            class="block px-3 py-2 rounded-md text-white font-medium hover:bg-white/20 transition-colors"
                            @click="closeMobileMenu"
                        >
                            ⚙️ Paramètres
                        </Link>
                        <hr class="my-2 border-white/20">
                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="block w-full text-left px-3 py-2 rounded-md text-red-300 font-medium hover:bg-red-500/20 transition-colors"
                            @click="closeMobileMenu"
                        >
                            🚪 Déconnexion
                        </Link>
                    </div>
                </div>
            </Transition>
        </header>

        <!-- Container Principal -->
        <div class="flex min-h-screen pt-16">
            <!-- Sidebar Verticale (Desktop uniquement) -->
            <aside class="hidden lg:block fixed left-0 top-16 bottom-0 w-64 bg-white shadow-lg overflow-y-auto z-40">
                <div class="p-4 lg:p-6 space-y-2">
                    <!-- Marketplace Cours -->
                    <Link
                        :href="route('cours.index')"
                        class="flex items-center gap-3 p-3 rounded-xl transition-all font-medium"
                        :class="isActive('/cours') && !isActive('/cours/create') ? 'bg-blue-500 text-white' : 'text-gray-600 hover:bg-gray-100'"
                    >
                        <span class="text-xl">🛒</span>
                        <span class="text-sm lg:text-base">Marketplace</span>
                    </Link>

                    <!-- Publier un cours (pour profs et admins) -->
                    <Link
                        v-if="$page.props.auth.user && ['Professeur', 'Admin'].includes($page.props.auth.user.account_type)"
                        :href="route('cours.create')"
                        class="flex items-center gap-3 p-3 rounded-xl transition-all font-medium"
                        :class="isActive('/cours/create') ? 'bg-green-500 text-white' : 'text-gray-600 hover:bg-gray-100'"
                    >
                        <span class="text-xl">📚</span>
                        <span class="text-sm lg:text-base">Publier un cours</span>
                    </Link>

                    <!-- Devenir Premium (étudiants) -->
                    <Link
                        v-else-if="$page.props.auth.user && $page.props.auth.user.account_type === 'Etudiant'"
                        :href="route('etudiant.premium.index')"
                        class="flex items-center gap-3 p-3 rounded-xl transition-all font-medium bg-gradient-to-r from-purple-500 to-pink-500 text-white hover:from-purple-600 hover:to-pink-600"
                    >
                        <span class="text-xl">💎</span>
                        <span class="text-sm lg:text-base">Devenir Premium</span>
                    </Link>

                    <!-- Mes cours publiés (pour profs et admins) -->
                    <Link
                        v-if="$page.props.auth.user && ['Professeur', 'Admin'].includes($page.props.auth.user.account_type)"
                        :href="route('professeur.mes-cours')"
                        class="flex items-center gap-3 p-3 rounded-xl transition-all font-medium"
                        :class="isActive('/professeur/mes-cours') ? 'bg-green-500 text-white' : 'text-gray-600 hover:bg-gray-100'"
                    >
                        <span class="text-xl">📖</span>
                        <span class="text-sm lg:text-base">Mes cours publiés</span>
                    </Link>

                    <!-- Liens principaux -->
                    <Link
                        :href="route('dashboard.redirect')"
                        class="flex items-center gap-3 p-3 rounded-xl transition-all font-medium"
                        :class="isActive('/dashboard') ? 'bg-[#5b7ce6] text-white' : 'text-gray-600 hover:bg-gray-100'"
                    >
                        <span class="text-xl">🏠</span>
                        <span class="text-sm lg:text-base">Accueil</span>
                    </Link>

                    <Link
                        :href="route('etudiant.cours.index')"
                        class="flex items-center gap-3 p-3 rounded-xl transition-all font-medium"
                        :class="isActive('/etudiant/cours') ? 'bg-[#5b7ce6] text-white' : 'text-gray-600 hover:bg-gray-100'"
                    >
                        <span class="text-xl">📚</span>
                        <span class="text-sm lg:text-base">Mes cours suivis</span>
                    </Link>

                    <Link
                        :href="route('etudiant.devoirs.index')"
                        class="flex items-center gap-3 p-3 rounded-xl transition-all font-medium"
                        :class="isActive('/etudiant/devoirs') ? 'bg-[#5b7ce6] text-white' : 'text-gray-600 hover:bg-gray-100'"
                    >
                        <span class="text-xl">📝</span>
                        <span class="text-sm lg:text-base">Mes devoirs</span>
                    </Link>

                    <Link
                        :href="route('etudiant.premium.index')"
                        class="flex items-center gap-3 p-3 rounded-xl transition-all font-medium"
                        :class="isActive('/etudiant/premium') ? 'bg-[#5b7ce6] text-white' : 'text-gray-600 hover:bg-gray-100'"
                    >
                        <span class="text-xl">💎</span>
                        <span class="text-sm lg:text-base">Premium</span>
                    </Link>

                    <Link
                        :href="route('etudiant.notifications')"
                        class="flex items-center gap-3 p-3 rounded-xl transition-all font-medium"
                        :class="isActive('/etudiant/notifications') ? 'bg-[#5b7ce6] text-white' : 'text-gray-600 hover:bg-gray-100'"
                    >
                        <span class="text-xl">🔔</span>
                        <span class="text-sm lg:text-base">Notifications</span>
                    </Link>

                    <Link
                        :href="route('profile.edit')"
                        class="flex items-center gap-3 p-3 rounded-xl transition-all font-medium"
                        :class="isActive('/profile') ? 'bg-[#5b7ce6] text-white' : 'text-gray-600 hover:bg-gray-100'"
                    >
                        <span class="text-xl">👤</span>
                        <span class="text-sm lg:text-base">Mon profil</span>
                    </Link>

                    <Link
                        :href="route('etudiant.settings')"
                        class="flex items-center gap-3 p-3 rounded-xl transition-all font-medium"
                        :class="isActive('/etudiant/settings') ? 'bg-[#5b7ce6] text-white' : 'text-gray-600 hover:bg-gray-100'"
                    >
                        <span class="text-xl">⚙️</span>
                        <span class="text-sm lg:text-base">Paramètres</span>
                    </Link>
                </div>
            </aside>

            <!-- Contenu Principal -->
            <main class="flex-1 lg:ml-64 min-h-screen bg-gray-50 w-full">
                <div class="p-3 sm:p-4 md:p-6 lg:p-8 w-full max-w-full">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref, onMounted, onUnmounted } from 'vue';

// États des menus
const showMobileMenu = ref(false);
const showUserMenu = ref(false);

// Accès aux props Inertia
const page = usePage();

// Initiale de l'utilisateur
const userInitial = computed(() => {
    const user = page.props.auth?.user;
    return user?.name?.charAt(0).toUpperCase() || 'U';
});

// Vérifier si une route est active
const isActive = (route) => {
    if (route === '/dashboard') {
        return page.url === '/dashboard';
    }
    if (route === '/cours' && page.url === '/cours') {
        return true;
    }
    if (route === '/cours/create' && page.url === '/cours/create') {
        return true;
    }
    return page.url.startsWith(route);
};

// Gestion des menus
const toggleMobileMenu = () => {
    showMobileMenu.value = !showMobileMenu.value;
    showUserMenu.value = false;
};

const toggleUserMenu = () => {
    showUserMenu.value = !showUserMenu.value;
    showMobileMenu.value = false;
};

const closeMobileMenu = () => {
    showMobileMenu.value = false;
};

const closeAllMenus = () => {
    showMobileMenu.value = false;
    showUserMenu.value = false;
};

// Fermer les menus lors du clic à l'extérieur
const handleClickOutside = (event) => {
    if (!event.target.closest('.relative') && !event.target.closest('.md\\:hidden')) {
        showUserMenu.value = false;
        showMobileMenu.value = false;
    }
};

// Fermer les menus lors du scroll
const handleScroll = () => {
    closeAllMenus();
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
    window.removeEventListener('scroll', handleScroll);
});
</script>
