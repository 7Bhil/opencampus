<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transform transition-transform duration-300 ease-in-out lg:translate-x-0"
               :class="{ '-translate-x-full': !sidebarOpen }">
            <!-- Logo -->
            <div class="h-16 flex items-center px-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-gray-900">OpenCampus</h1>
                        <p class="text-xs text-gray-500">Administration</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2">
                <Link :href="route('admin.dashboard')"
                      class="flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors"
                      :class="isCurrentRoute('admin.dashboard')
                        ? 'bg-blue-50 text-blue-700'
                        : 'text-gray-700 hover:bg-gray-100'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>Dashboard</span>
                </Link>

                <Link :href="route('admin.users')"
                      class="flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors"
                      :class="isCurrentRoute('admin.users')
                        ? 'bg-blue-50 text-blue-700'
                        : 'text-gray-700 hover:bg-gray-100'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span>Utilisateurs</span>
                </Link>

                <Link :href="route('admin.cours')"
                      class="flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors"
                      :class="isCurrentRoute('admin.cours')
                        ? 'bg-blue-50 text-blue-700'
                        : 'text-gray-700 hover:bg-gray-100'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <span>Cours</span>
                </Link>

                <Link :href="route('admin.cours-a-moderer')"
                      class="flex items-center justify-between px-4 py-3 rounded-lg text-sm font-medium transition-colors"
                      :class="isCurrentRoute('admin.cours-a-moderer')
                        ? 'bg-blue-50 text-blue-700'
                        : 'text-gray-700 hover:bg-gray-100'">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Modération</span>
                    </div>
                    <span v-if="coursAModererCount > 0"
                          class="inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 rounded-full">
                        {{ coursAModererCount }}
                    </span>
                </Link>

                <div class="pt-6 mt-6 border-t border-gray-200">
                    <Link :href="route('dashboard')"
                          class="flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span>Retour au site</span>
                    </Link>
                </div>
            </nav>

            <!-- User Profile -->
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200 bg-white">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold">
                        {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            {{ $page.props.auth.user?.name || 'Utilisateur' }}
                        </p>
                        <p class="text-xs text-gray-500 truncate">
                            {{ $page.props.auth.user?.email || 'Email inconnu' }}
                        </p>
                    </div>
                </div>
                <button @click="logout"
                        class="w-full flex items-center justify-center space-x-2 px-4 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Déconnexion</span>
                </button>
            </div>
        </aside>

        <!-- Mobile overlay -->
        <div v-if="sidebarOpen"
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-gray-900/50 z-40 lg:hidden"></div>

        <!-- Main content -->
        <div class="lg:pl-64">
            <!-- Top bar -->
            <header class="sticky top-0 z-30 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                    <button @click="sidebarOpen = !sidebarOpen"
                            class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <div class="flex-1 lg:flex-none">
                        <h1 class="text-xl font-semibold text-gray-900">
                            <slot name="header">Dashboard</slot>
                        </h1>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <button class="relative p-2 text-gray-500 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <span v-if="coursAModererCount > 0"
                                  class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>

                        <!-- User menu (mobile) -->
                        <div class="lg:hidden">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-sm font-semibold">
                                {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="p-4 sm:p-6 lg:p-8 flex-1 flex flex-col">
                <!-- Flash messages -->
                <div class="flex-grow">
                    <transition
                        enter-active-class="transition ease-out duration-300"
                        enter-from-class="opacity-0 translate-y-2"
                        enter-to-class="opacity-100 translate-y-0"
                        leave-active-class="transition ease-in duration-200"
                        leave-from-class="opacity-100"
                        leave-to-class="opacity-0">
                        <div v-if="$page.props.flash?.success"
                             class="mb-6 flex items-center space-x-3 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm font-medium text-green-800">{{ $page.props.flash.success }}</p>
                        </div>
                    </transition>

                    <transition
                        enter-active-class="transition ease-out duration-300"
                        enter-from-class="opacity-0 translate-y-2"
                        enter-to-class="opacity-100 translate-y-0"
                        leave-active-class="transition ease-in duration-200"
                        leave-from-class="opacity-100"
                        leave-to-class="opacity-0">
                        <div v-if="$page.props.flash?.error"
                             class="mb-6 flex items-center space-x-3 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm font-medium text-red-800">{{ $page.props.flash.error }}</p>
                        </div>
                    </transition>

                    <!-- Page content slot -->
                    <slot />
                </div>

                <!-- Footer -->
                <footer class="mt-12 py-6 border-t border-gray-100">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-gray-500 font-medium">
                        <div class="flex items-center gap-2">
                            <span>© 2026</span>
                            <span class="text-gray-900 font-bold uppercase tracking-wider">Bhil$</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <span>Administration OpenCampus v1.1.0</span>
                            <span class="text-gray-300">•</span>
                            <span>Licensed under MIT</span>
                        </div>
                    </div>
                </footer>
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, useForm, usePage } from '@inertiajs/vue3'

const props = defineProps({
    coursAModererCount: {
        type: Number,
        default: 0
    }
})

const page = usePage()
const sidebarOpen = ref(false)
const form = useForm({})

const isCurrentRoute = (routeName) => {
    const currentUrl = page.url
    const route = routeName.replace('admin.', '/admin/')
    return currentUrl.startsWith(route) || currentUrl === route
}

const logout = () => {
    form.post(route('logout'))
}
</script>

<style scoped>
/* Smooth transitions */
* {
    transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}
</style>
