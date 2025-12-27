<template>
    <div class="max-w-4xl mx-auto py-8">
        <h1 class="text-2xl font-bold mb-6">Modifier le cours</h1>

        <form @submit.prevent="submit">
            <!-- Formulaire similaire à Create.vue -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Titre</label>
                    <input v-model="form.titre" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <!-- ... autres champs ... -->
                <div class="flex justify-end space-x-3">
                    <Link :href="route('cours.show', cours.id)" class="px-4 py-2 border rounded-md">
                        Annuler
                    </Link>
                    <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-blue-600 text-white rounded-md">
                        Mettre à jour
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    cours: Object
})

const form = useForm({
    titre: props.cours.titre,
    matiere: props.cours.matiere,
    categorie: props.cours.categorie,
    description: props.cours.description,
    est_payant: props.cours.est_payant,
    prix: props.cours.prix,
    fichier: null,
})

const submit = () => {
    form.put(route('cours.update', props.cours.id))
}
</script>
