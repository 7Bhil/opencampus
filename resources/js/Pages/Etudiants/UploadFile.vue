<template>
<NavbarUser>
  <div class="min-h-screen bg-gradient-to-br py-12 px-4 relative overflow-hidden">

    <div class="max-w-7xl mx-auto relative z-10">
      <!-- En-tête avec correction du texte -->
      <div class="text-center mb-12">
        <div class="inline-flex items-center justify-center w-24 h-24 bg-white rounded-3xl shadow-2xl mb-6 transform hover:scale-110 hover:rotate-6 transition-all duration-500">
          <span class="text-5xl">📚</span>
        </div>
        <h1 class="text-5xl md:text-6xl font-black text-black mb-4 drop-shadow-2xl">
          Partagez vos connaissances avec la communauté
        </h1>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        <!-- Colonne gauche - Upload Zone -->
        <div class="lg:col-span-3 space-y-6">
          <!-- Zone de glisser-déposer -->
          <div class="bg-white rounded-3xl shadow-2xl p-8 hover:shadow-[0_20px_60px_rgba(91,124,230,0.4)] transition-all duration-300">
            <label
              for="file-upload"
              class="flex flex-col items-center justify-center cursor-pointer group"
            >
              <div class="w-28 h-28 bg-gradient-to-br from-[#5b7ce6] to-[#b567b8] rounded-3xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-12 transition-all duration-500 shadow-xl">
                <span class="text-5xl text-white">📁</span>
              </div>
              <p class="text-2xl font-bold text-gray-800 mb-2">
                Glissez vos fichiers ici
              </p>
              <p class="text-gray-500 mb-6 text-lg">
                ou <span class="text-[#5b7ce6] font-bold underline">parcourez vos fichiers</span>
              </p>
              <div class="flex flex-wrap gap-3 justify-center">
                <span class="inline-block px-6 py-2 rounded-full font-semibold text-white transition-all duration-300 hover:scale-105 hover:shadow-glow-purple shadow-lg"
                style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                  PDF
                </span>
                <span class="inline-block px-6 py-2 rounded-full font-semibold text-white transition-all duration-300 hover:scale-105 hover:shadow-glow-blue shadow-lg"
                  style="background: linear-gradient(135deg, #8b5cf6, #a855f7);">
                  DOCX
                </span>
                <span class="inline-block px-6 py-2 rounded-full font-semibold text-white transition-all duration-300 hover:scale-105 hover:shadow-glow-pink shadow-lg"
                    style="background: linear-gradient(135deg, #ec4899, #f472b6);">
                    PPT
                </span>
                <span class="inline-block px-6 py-2 rounded-full font-semibold text-white transition-all duration-300 hover:scale-105 hover:shadow-glow-green shadow-lg"
                style="background: linear-gradient(135deg, #10b981, #059669);">
                ZIP
                </span>
              </div>
              <input
                id="file-upload"
                type="file"
                multiple
                @change="handleFileChange"
                class="hidden"
              />
            </label>
          </div>

          <!-- Liste des fichiers -->
          <div v-if="files.length > 0" class="bg-white rounded-3xl shadow-2xl p-6">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                <span class="text-3xl">📎</span>
                Fichiers sélectionnés
                <span class="bg-gradient-to-r from-[#5b7ce6] to-[#9b6ec9] text-white text-lg px-4 py-1 rounded-full shadow-lg">
                  {{ files.length }}
                </span>
              </h2>
              <button
                @click="files = []"
                class="bg-gradient-to-r from-red-500 to-pink-500 text-white px-6 py-2 rounded-full font-bold hover:scale-110 transition-all shadow-lg flex items-center gap-2"
              >
                <span>🗑️</span>
                Tout supprimer
              </button>
            </div>

            <div class="space-y-3">
              <div
                v-for="(file, index) in files"
                :key="index"
                class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-blue-50 rounded-2xl border-2 border-blue-100 group hover:shadow-xl hover:border-[#5b7ce6] transition-all"
              >
                <div class="flex items-center gap-4">
                  <div class="w-14 h-14 bg-gradient-to-br from-[#5b7ce6] to-[#9b6ec9] rounded-2xl flex items-center justify-center shadow-lg">
                    <span class="text-3xl text-white">📄</span>
                  </div>
                  <div>
                    <p class="font-bold text-gray-800 text-lg">{{ file.name }}</p>
                    <p class="text-gray-500">
                      {{ (file.size / (1024 * 1024)).toFixed(2) }} Mo
                    </p>
                  </div>
                </div>
                <button
                  @click="handleRemoveFile(index)"
                  class="w-10 h-10 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-full flex items-center justify-center font-bold opacity-0 group-hover:opacity-100 transition-all hover:scale-125 shadow-lg"
                >
                  ✕
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Colonne droite - Formulaire simplifié -->
        <div class="lg:col-span-2">
          <div class="sticky top-8">
            <div class="bg-white rounded-3xl shadow-2xl p-8 hover:shadow-[0_20px_60px_rgba(91,124,230,0.4)] transition-all duration-300">
              <h2 class="text-2xl font-bold text-gray-800 mb-8 flex items-center gap-3 border-b border-gray-200 pb-4">
                <span class="text-3xl">📋</span>
                Informations du contenu
              </h2>

              <form @submit.prevent="handleSubmit" class="space-y-8">
                <!-- Titre -->
                <div>
                  <h3 class="text-lg font-bold text-gray-700 mb-4 uppercase tracking-wider">
                    TITRE DU CONTENU
                  </h3>
                  <input
                    v-model="formData.title"
                    type="text"
                    placeholder="Ex: Introduction au Machine Learning"
                    class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:border-[#5b7ce6] outline-none transition-all font-medium bg-white"
                  />
                </div>

                <!-- Type et Matière -->
                <div class="space-y-6">
                  <div>
                    <h3 class="text-lg font-bold text-gray-700 mb-4 uppercase tracking-wider">
                      TYPE
                    </h3>
                    <select
                      v-model="formData.contentType"
                      class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:border-[#5b7ce6] outline-none transition-all font-medium bg-white"
                    >
                      <option value="Cours">Cours</option>
                      <option value="TD">TD</option>
                      <option value="TP">TP</option>
                      <option value="Projet">Projet</option>
                    </select>
                  </div>

                  <div>
                    <h3 class="text-lg font-bold text-gray-700 mb-4 uppercase tracking-wider">
                      MATIÈRE
                    </h3>
                    <input
                      v-model="formData.subject"
                      type="text"
                      placeholder="Ex: IA"
                      class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:border-[#5b7ce6] outline-none transition-all font-medium bg-white"
                    />
                  </div>
                </div>

                <!-- Niveau -->
                <!-- Remplace la section NIVEAU actuelle par ce code : -->
<!-- Niveau et Filière -->
<div class="grid grid-cols-2 gap-4">
  <div>
    <h3 class="text-lg font-bold text-gray-700 mb-4 uppercase tracking-wider">
      NIVEAU
    </h3>
    <select
      v-model="formData.level"
      class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:border-[#5b7ce6] outline-none transition-all font-medium bg-white"
    >
      <option value="L1">L1</option>
      <option value="L2">L2</option>
      <option value="L3">L3</option>
      <option value="M1">M1</option>
      <option value="M2">M2</option>
    </select>
  </div>

  <div>
    <h3 class="text-lg font-bold text-gray-700 mb-4 uppercase tracking-wider">
      FILIÈRE
    </h3>
    <select
      v-model="formData.filiere"
      class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:border-[#5b7ce6] outline-none transition-all font-medium bg-white h-32"
    >
      <option value="tous">Tous</option>
      <option value="IG">IG</option>
      <option value="GB">GB</option>
      <option value="GC">GC</option>
      <option value="GE">GE</option>
      <option value="GTL">GTL</option>
      <option value="GRH">GRH</option>
    </select>
  </div>
</div>

                <!-- Description -->
                <div>
                  <h3 class="text-lg font-bold text-gray-700 mb-4 uppercase tracking-wider">
                    DESCRIPTION
                  </h3>
                  <textarea
                    v-model="formData.description"
                    placeholder="Décrivez le contenu de vos documents..."
                    rows="3"
                    class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:border-[#5b7ce6] outline-none transition-all resize-none font-medium bg-white"
                  ></textarea>
                </div>

                <!-- Tags -->
                <div>
                  <h3 class="text-lg font-bold text-gray-700 mb-4 uppercase tracking-wider">
                    TAGS
                  </h3>
                  <input
                    v-model="formData.tags"
                    type="text"
                    placeholder="IA, Python, Machine Learning..."
                    class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:border-[#5b7ce6] outline-none transition-all font-medium bg-white"
                  />
                </div>

                <!-- Option Payant -->
                <div style="margin-top: 2%" class="space-y-4">
                  <div class="flex items-center gap-3 p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border-2 border-green-300 cursor-pointer hover:shadow-lg transition-all"
                       @click="formData.isPaid = !formData.isPaid">
                    <input
                      type="checkbox"
                      v-model="formData.isPaid"
                      class="w-5 h-5 text-green-500 rounded focus:ring-green-400"
                    />
                    <div class="flex items-center gap-2">
                      <span class="text-2xl">💰</span>
                      <span class="font-bold text-green-700">Contenu Payant</span>
                    </div>
                  </div>

                  <!-- Input pour le montant en XOF (apparaît seulement si payant est coché) -->
                  <div v-if="formData.isPaid" class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border-2 border-blue-300 transition-all duration-300">
                    <h3 class="text-lg font-bold text-gray-700 mb-3 uppercase tracking-wider">
                      MONTANT (XOF)
                    </h3>
                    <div class="relative">
                      <input
                        v-model="formData.price"
                        type="number"
                        min="0"
                        step="100"
                        placeholder="Ex: 5000"
                        @input="validateInteger"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-[#5b7ce6] outline-none transition-all font-medium bg-white pr-16"
                      />
                      <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-semibold">
                        XOF
                      </span>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Entrez un montant entier sans décimales</p>
                  </div>
                </div>

                <!-- Bouton de soumission -->
                <button
                  type="submit"
                  :disabled="files.length === 0 || !formData.title || (formData.isPaid && (!formData.price || formData.price <= 0))"
                  class="w-full bg-gradient-to-r  from-[#5b7ce6] via-[#9b6ec9] to-[#b567b8] text-white py-5 rounded-xl font-black text-lg hover:scale-105 hover:shadow-2xl transition-all transform disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none flex items-center justify-center gap-3 shadow-xl mt-8"
                >
                  <span class="text-2xl">🚀</span>
                  PUBLIER LE CONTENU
                </button>
              </form>

              <!-- Indicateur de progression -->
              <div v-if="files.length > 0" class="mt-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border-2 border-green-300 shadow-lg">
                <div class="flex items-center gap-3">
                  <span class="text-3xl text-green-500">✅</span>
                  <div>
                    <p class="font-bold text-green-800 text-lg">Prêt à publier !</p>
                    <p class="text-sm text-green-600">{{ files.length }} fichier(s) sélectionné(s)</p>
                  </div>
                </div>
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
import { ref, reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import NavbarUser from '@/Layouts/NavbarEtudiants.vue'

const files = ref([])

const formData = reactive({
  title: '',
  contentType: 'Cours',
  subject: '',
  level: 'L1',
  filiere: [], // ← Ajouter cette ligne (tableau pour sélection multiple)
  description: '',
  tags: '',
  isPaid: false,
  price: null
})

// Validation pour s'assurer que c'est un entier
const validateInteger = (event) => {
  let value = event.target.value
  // Supprimer les décimales
  if (value.includes('.')) {
    value = value.split('.')[0]
    formData.price = parseInt(value) || 0
  }
  // S'assurer que c'est un nombre positif
  if (value < 0) {
    formData.price = 0
  }
}

const handleFileChange = (event) => {
  const selectedFiles = Array.from(event.target.files)
  files.value = [...files.value, ...selectedFiles]
}

const handleRemoveFile = (index) => {
  files.value = files.value.filter((_, i) => i !== index)
}

const handleSubmit = () => {
  if (files.value.length === 0 || !formData.title) {
    return
  }

  // Validation supplémentaire pour le contenu payant
  if (formData.isPaid && (!formData.price || formData.price <= 0)) {
    return
  }

  const form = new FormData()

  Object.keys(formData).forEach(key => {
    if (formData[key] !== null) {
      form.append(key, formData[key])
    }
  })

  files.value.forEach(file => {
    form.append('files[]', file)
  })

  router.post('/contents', form, {
    forceFormData: true,
    onSuccess: () => {
      resetForm()
    }
  })
}

const resetForm = () => {
  formData.title = ''
  formData.contentType = 'Cours'
  formData.subject = ''
  formData.level = 'L1'
  formData.filiere = [] // ← Ajouter cette ligne
  formData.description = ''
  formData.tags = ''
  formData.isPaid = false
  formData.price = null
  files.value = []
}
</script>

<style scoped>
@keyframes blob {
  0%, 100% {
    transform: translate(0, 0) scale(1);
  }
  33% {
    transform: translate(30px, -50px) scale(1.1);
  }
  66% {
    transform: translate(-20px, 20px) scale(0.9);
  }
}

.animate-blob {
  animation: blob 7s infinite;
}

.animation-delay-2000 {
  animation-delay: 2s;
}

.animation-delay-4000 {
  animation-delay: 4s;
}
</style>
