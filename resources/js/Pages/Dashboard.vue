<template>
    <div class="min-h-screen bg-gradient-to-br from-[#5b7ce6] via-[#9b6ec9] to-[#b567b8]">
        <!-- Navbar Simple -->
        <header class="fixed top-0 left-0 right-0 z-50 bg-white/10 backdrop-blur-md border-b border-white/20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center gap-3 text-white text-2xl font-bold">
                        <span class="text-3xl">🎓</span>
                        OpenCampus
                    </div>

                    <div class="flex gap-4">
                        <Link
                            href="/login"
                            class="text-white font-medium hover:opacity-85 transition-opacity"
                        >
                            Connexion
                        </Link>
                        <Link
                            href="/register"
                            class="bg-white text-[#5b7ce6] px-4 py-2 rounded-lg font-semibold hover:bg-gray-100 transition-colors"
                        >
                            Inscription
                        </Link>
                    </div>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <div class="pt-16 min-h-screen flex items-center">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-5xl md:text-6xl font-bold text-white mb-6">
                    Bienvenue sur
                    <span class="block bg-gradient-to-r from-white to-gray-200 bg-clip-text text-transparent">
                        OpenCampus
                    </span>
                </h1>
                <p class="text-xl text-white/90 mb-8 max-w-3xl mx-auto">
                    La plateforme d'apprentissage qui révolutionne votre éducation.
                    Accédez à des cours de qualité, suivez votre progression et excellez dans vos études.
                </p>

                <div class="flex gap-4 justify-center">
                    <Link
                        href="/register"
                        class="bg-white text-[#5b7ce6] px-8 py-4 rounded-xl font-bold text-lg hover:bg-gray-100 transition-all transform hover:scale-105"
                    >
                        Commencer gratuitement
                    </Link>
                    <Link
                        href="/login"
                        class="border-2 border-white text-white px-8 py-4 rounded-xl font-bold text-lg hover:bg-white/10 transition-all"
                    >
                        Se connecter
                    </Link>
                </div>

                <!-- Statistiques -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-16 max-w-4xl mx-auto">
                    <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 border border-white/20">
                        <div class="text-3xl font-bold text-white mb-2">500+</div>
                        <div class="text-white/80">Cours disponibles</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 border border-white/20">
                        <div class="text-3xl font-bold text-white mb-2">10K+</div>
                        <div class="text-white/80">Étudiants actifs</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 border border-white/20">
                        <div class="text-3xl font-bold text-white mb-2">98%</div>
                        <div class="text-white/80">Taux de satisfaction</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>
