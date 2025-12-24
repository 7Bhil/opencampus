<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-purple-50 flex items-center justify-center p-4">
        <div class="max-w-md w-full">
            <!-- Logo et titre -->
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-500 rounded-3xl shadow-2xl mb-6">
                    <span class="text-3xl text-white">🎓</span>
                </div>
                <h1 class="text-4xl font-black bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-3">
                    OpenCampus
                </h1>
                <p class="text-gray-600">
                    Connectez-vous à votre espace étudiant
                </p>
            </div>

            <!-- Carte du formulaire -->
            <div class="bg-white rounded-3xl shadow-2xl p-8 border border-gray-100">
                <div v-if="status" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 font-medium">
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="space-y-6">
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
                                autofocus
                                autocomplete="email"
                                placeholder="exemple@etudiant.com"
                            />
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="bi bi-envelope text-lg"></i>
                            </div>
                        </div>
                        <p v-if="form.errors.email" class="mt-2 text-sm text-red-600">
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <!-- Mot de passe -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Mot de passe
                        </label>
                        <div class="relative">
                            <input
                                id="password"
                                type="password"
                                class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                v-model="form.password"
                                required
                                autocomplete="current-password"
                                placeholder="Votre mot de passe"
                            />
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="bi bi-lock text-lg"></i>
                            </div>
                        </div>
                        <p v-if="form.errors.password" class="mt-2 text-sm text-red-600">
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <!-- Remember me et mot de passe oublié -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    v-model="form.remember"
                                    class="hidden"
                                />
                                <div class="w-5 h-5 border-2 border-gray-300 rounded-md group-hover:border-blue-400 transition-colors flex items-center justify-center">
                                    <i v-if="form.remember" class="bi bi-check text-blue-500 text-sm"></i>
                                </div>
                            </div>
                            <span class="text-sm text-gray-600 group-hover:text-gray-700 transition-colors">
                                Se souvenir de moi
                            </span>
                        </label>

                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-sm text-blue-500 hover:text-blue-600 font-medium transition-colors"
                        >
                            Mot de passe oublié ?
                        </Link>
                    </div>

                    <!-- Bouton de connexion -->
                    <button
                        type="submit"
                        class="w-full py-3 bg-gradient-to-r from-blue-500 to-purple-500 text-white font-semibold rounded-xl hover:scale-105 hover:shadow-xl transition-all transform disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                        :disabled="form.processing"
                    >
                        <span v-if="form.processing" class="flex items-center justify-center gap-2">
                            <i class="bi bi-arrow-clockwise animate-spin"></i>
                            Connexion en cours...
                        </span>
                        <span v-else class="flex items-center justify-center gap-2">
                            <i class="bi bi-box-arrow-in-right"></i>
                            Se connecter
                        </span>
                    </button>
                </form>

                <!-- Lien vers l'inscription -->
                <div class="mt-8 pt-6 border-t border-gray-200 text-center">
                    <p class="text-gray-600 text-sm">
                        Pas encore de compte ?
                        <Link
                            :href="route('register')"
                            class="text-blue-500 hover:text-blue-600 font-semibold transition-colors"
                        >
                            S'inscrire maintenant
                        </Link>
                    </p>
                </div>
            </div>

            <!-- Message de bienvenue -->
            <div class="mt-8 text-center text-gray-500 text-sm">
                <p>Rejoignez la communauté étudiante d'OpenCampus</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<style>
@import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css');
</style>
