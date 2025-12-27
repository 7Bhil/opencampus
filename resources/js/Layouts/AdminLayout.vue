<!-- resources/js/Layouts/AdminLayout.vue -->
<template>
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation Admin -->
        <nav class="bg-gray-800 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <span class="text-white font-bold text-xl">Admin OpenCampus</span>
                        </div>

                        <!-- Navigation links -->
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <Link
                                :href="route('admin.dashboard')"
                                class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                                :class="{'border-blue-500 text-white': $page.url === '/admin/dashboard', 'border-transparent text-gray-300 hover:border-gray-300 hover:text-white': $page.url !== '/admin/dashboard'}"
                            >
                                Dashboard
                            </Link>
                            <Link
                                :href="route('admin.users')"
                                class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                                :class="{'border-blue-500 text-white': $page.url.startsWith('/admin/users'), 'border-transparent text-gray-300 hover:border-gray-300 hover:text-white': !$page.url.startsWith('/admin/users')}"
                            >
                                Utilisateurs
                            </Link>
                            <Link
                                :href="route('admin.cours')"
                                class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                                :class="{'border-blue-500 text-white': $page.url.startsWith('/admin/cours'), 'border-transparent text-gray-300 hover:border-gray-300 hover:text-white': !$page.url.startsWith('/admin/cours')}"
                            >
                                Cours
                            </Link>
                            <Link
                                :href="route('admin.cours-a-moderer')"
                                class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium relative"
                                :class="{'border-blue-500 text-white': $page.url.startsWith('/admin/cours-a-moderer'), 'border-transparent text-gray-300 hover:border-gray-300 hover:text-white': !$page.url.startsWith('/admin/cours-a-moderer')}"
                            >
                                Modération
                                <span v-if="coursAModererCount > 0"
                                      class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
                                    {{ coursAModererCount }}
                                </span>
                            </Link>
                        </div>
                    </div>

                    <!-- User menu -->
                    <div class="hidden sm:ml-6 sm:flex sm:items-center">
                        <div class="ml-3 relative flex items-center space-x-4">
                            <span class="text-gray-300 text-sm">{{ $page.props.auth.user.name }}</span>
                            <Link
                                :href="route('dashboard.redirect')"
                                class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium"
                            >
                                Retour au site
                            </Link>
                            <form @submit.prevent="logout">
                                <button type="submit"
                                        class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button type="button"
                                @click="mobileMenuOpen = !mobileMenuOpen"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                            <span class="sr-only">Open main menu</span>
                            <svg class="h-6 w-6" :class="{'hidden': mobileMenuOpen, 'block': !mobileMenuOpen}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                            <svg class="h-6 w-6" :class="{'hidden': !mobileMenuOpen, 'block': mobileMenuOpen}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div v-if="mobileMenuOpen" class="sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <Link
                        :href="route('admin.dashboard')"
                        class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
                        :class="{'bg-gray-900 border-blue-500 text-white': $page.url === '/admin/dashboard', 'border-transparent text-gray-300 hover:bg-gray-700 hover:border-gray-300 hover:text-white': $page.url !== '/admin/dashboard'}"
                        @click="mobileMenuOpen = false"
                    >
                        Dashboard
                    </Link>
                    <Link
                        :href="route('admin.users')"
                        class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
                        :class="{'bg-gray-900 border-blue-500 text-white': $page.url.startsWith('/admin/users'), 'border-transparent text-gray-300 hover:bg-gray-700 hover:border-gray-300 hover:text-white': !$page.url.startsWith('/admin/users')}"
                        @click="mobileMenuOpen = false"
                    >
                        Utilisateurs
                    </Link>
                    <Link
                        :href="route('admin.cours')"
                        class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
                        :class="{'bg-gray-900 border-blue-500 text-white': $page.url.startsWith('/admin/cours'), 'border-transparent text-gray-300 hover:bg-gray-700 hover:border-gray-300 hover:text-white': !$page.url.startsWith('/admin/cours')}"
                        @click="mobileMenuOpen = false"
                    >
                        Cours
                    </Link>
                    <Link
                        :href="route('admin.cours-a-moderer')"
                        class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
                        :class="{'bg-gray-900 border-blue-500 text-white': $page.url.startsWith('/admin/cours-a-moderer'), 'border-transparent text-gray-300 hover:bg-gray-700 hover:border-gray-300 hover:text-white': !$page.url.startsWith('/admin/cours-a-moderer')}"
                        @click="mobileMenuOpen = false"
                    >
                        Modération
                        <span v-if="coursAModererCount > 0"
                              class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
                            {{ coursAModererCount }}
                        </span>
                    </Link>
                </div>
                <div class="pt-4 pb-3 border-t border-gray-700">
                    <div class="flex items-center px-4">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="text-blue-600 font-medium">{{ $page.props.auth.user.name.charAt(0) }}</span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium text-white">{{ $page.props.auth.user.name }}</div>
                            <div class="text-sm font-medium text-gray-400">{{ $page.props.auth.user.email }}</div>
                        </div>
                    </div>
                    <div class="mt-3 space-y-1">
                        <Link
                            :href="route('dashboard.redirect')"
                            class="block px-4 py-2 text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700"
                            @click="mobileMenuOpen = false"
                        >
                            Retour au site
                        </Link>
                        <form @submit.prevent="logout">
                            <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700">
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Header -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-gray-900">
                    <slot name="header" />
                </h1>
            </div>
        </header>

        <!-- Main content -->
        <main>
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <!-- Flash messages -->
                <div v-if="$page.props.flash?.success"
                     class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-green-800">{{ $page.props.flash.success }}</p>
                </div>
                <div v-if="$page.props.flash?.error"
                     class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-red-800">{{ $page.props.flash.error }}</p>
                </div>

                <!-- Page content -->
                <slot />
            </div>
        </main>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
    coursAModererCount: {
        type: Number,
        default: 0
    }
})

const mobileMenuOpen = ref(false)
const form = useForm({})

const logout = () => {
    form.post(route('logout'))
}
</script>
