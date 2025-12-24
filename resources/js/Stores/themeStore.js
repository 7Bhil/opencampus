import { defineStore } from 'pinia'
import { ref, computed, watch } from 'vue'

export const useThemeStore = defineStore('theme', () => {
    // Couleurs d'accent par défaut
    const accentColors = {
        blue: {
            primary: '#5b7ce6',
            darker: '#4a6bcf',
            darkest: '#395bb8',
            light: '#e0e8ff'
        },
        purple: {
            primary: '#9b6ec9',
            darker: '#8a5db8',
            darkest: '#794ca7',
            light: '#f0e6ff'
        },
        pink: {
            primary: '#b567b8',
            darker: '#a456a7',
            darkest: '#934596',
            light: '#fae6fb'
        },
        green: {
            primary: '#10b981',
            darker: '#0ea271',
            darkest: '#0c8b61',
            light: '#d1fae5'
        },
        orange: {
            primary: '#f59e0b',
            darker: '#e48d0a',
            darkest: '#d37c09',
            light: '#fef3c7'
        },
        red: {
            primary: '#ef4444',
            darker: '#dc2626',
            darkest: '#b91c1c',
            light: '#fee2e2'
        }
    }

    // Récupérer le thème initial
    const getInitialTheme = () => {
        const savedTheme = localStorage.getItem('app-theme')
        if (savedTheme) return savedTheme

        // Vérifier les préférences système
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            return 'dark'
        }
        return 'light'
    }

    const getInitialFontSize = () => {
        return localStorage.getItem('app-font-size') || 'medium'
    }

    const getInitialAccentColor = () => {
        return localStorage.getItem('app-accent-color') || 'blue'
    }

    // États réactifs
    const theme = ref(getInitialTheme())
    const fontSize = ref(getInitialFontSize())
    const accentColor = ref(getInitialAccentColor())

    // Getters
    const isDarkMode = computed(() => theme.value === 'dark')

    const fontSizeClass = computed(() => {
        const sizes = {
            small: 'text-sm',
            medium: 'text-base',
            large: 'text-lg'
        }
        return sizes[fontSize.value] || 'text-base'
    })

    const currentAccent = computed(() => accentColors[accentColor.value] || accentColors.blue)

    // Actions
    const setTheme = (newTheme) => {
        if (!['light', 'dark', 'auto'].includes(newTheme)) return
        theme.value = newTheme
        localStorage.setItem('app-theme', newTheme)
        applyTheme()
    }

    const setFontSize = (newSize) => {
        if (!['small', 'medium', 'large'].includes(newSize)) return
        fontSize.value = newSize
        localStorage.setItem('app-font-size', newSize)
        applyFontSize()
    }

    const setAccentColor = (newColor) => {
        if (!Object.keys(accentColors).includes(newColor)) return
        accentColor.value = newColor
        localStorage.setItem('app-accent-color', newColor)
        applyAccentColor()
    }

    const toggleTheme = () => {
        setTheme(theme.value === 'light' ? 'dark' : 'light')
    }

    // Appliquer les styles
    const applyTheme = () => {
        if (theme.value === 'dark') {
            document.documentElement.classList.add('dark')
        } else if (theme.value === 'light') {
            document.documentElement.classList.remove('dark')
        } else {
            // Mode auto - suivre les préférences système
            if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.documentElement.classList.add('dark')
            } else {
                document.documentElement.classList.remove('dark')
            }
        }
    }

    const applyFontSize = () => {
        // Supprimer les anciennes classes
        document.documentElement.classList.remove('text-sm', 'text-base', 'text-lg')
        // Ajouter la nouvelle classe
        document.documentElement.classList.add(fontSizeClass.value)
    }

    const applyAccentColor = () => {
        const color = currentAccent.value

        // Mettre à jour les variables CSS
        document.documentElement.style.setProperty('--color-primary', color.primary)
        document.documentElement.style.setProperty('--color-primary-darker', color.darker)
        document.documentElement.style.setProperty('--color-primary-darkest', color.darkest)
        document.documentElement.style.setProperty('--color-primary-light', color.light)
    }

    // Initialiser
    const initialize = () => {
        // Appliquer les styles initiaux
        applyTheme()
        applyFontSize()
        applyAccentColor()

        // Écouter les changements de préférences système (pour le mode auto)
        if (theme.value === 'auto') {
            const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)')
            mediaQuery.addEventListener('change', (e) => {
                if (theme.value === 'auto') {
                    applyTheme()
                }
            })
        }
    }

    // Exporter
    return {
        // États
        theme,
        fontSize,
        accentColor,

        // Getters
        isDarkMode,
        fontSizeClass,
        currentAccent,
        accentColors: Object.keys(accentColors).map(key => ({
            name: key,
            ...accentColors[key]
        })),

        // Actions
        setTheme,
        setFontSize,
        setAccentColor,
        toggleTheme,
        initialize
    }
})
