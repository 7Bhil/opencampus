# 📚 OpenCampus - Plateforme Éducative & Marketplace de Savoir

![OpenCampus](https://img.shields.io/badge/OpenCampus-Laravel%2012-blueviolet)
![Version](https://img.shields.io/badge/version-1.1.0-green)
![License](https://img.shields.io/badge/license-MIT-blue)

**OpenCampus** est une plateforme web moderne conçue pour révolutionner l'apprentissage collaboratif. Elle permet aux étudiants de devenir des créateurs de contenu en publiant et vendant leurs cours, tout en offrant aux professeurs un outil puissant pour la gestion et la correction des devoirs.

---

## ✨ Fonctionnalités Clés

### 👨‍🎓 Pour les Étudiants
- **Marketplace de Cours** : Accédez à une vaste bibliothèque de cours gratuits et payants.
- **Système Premium** : Abonnez-vous pour débloquer la possibilité de publier vos propres cours et générer des revenus.
- **Gestion du Solde** : Portefeuille intégré pour acheter des cours et recevoir des gains.
- **Espace Devoirs** : Consultez vos devoirs, téléchargez les énoncés et soumettez vos rendus directement en ligne.
- **Tableau de Bord** : Suivez votre progression, vos notes et vos dernières activités.

### 👨‍🏫 Pour les Professeurs
- **Gestion de Cours** : Créez, organisez et publiez du contenu pédagogique riche.
- **Système de Devoirs** : Publiez des exercices avec des dates limites précises.
- **Correction Collaborative** : Interface dédiée pour consulter les soumissions, attribuer des notes et laisser des commentaires constructifs.
- **Analytiques** : Visualisez les performances des étudiants sur vos cours et devoirs.

### 🛡️ Pour les Administrateurs
- **Modération** : Validation obligatoire des cours soumis par les étudiants premium avant publication.
- **Gestion Utilisateurs** : Contrôle total sur les comptes et les rôles (Étudiant, Professeur, Admin).
- **Statistiques Globales** : Suivi de l'engagement global, des revenus de la marketplace et de la santé du site.

---

## �️ Stack Technique

- **Backend**: [Laravel 12](https://laravel.com/) (PHP 8.3+)
- **Frontend**: [Vue.js 3](https://vuejs.org/) avec [Inertia.js](https://inertiajs.com/)
- **Styling**: [Tailwind CSS](https://tailwindcss.com/)
- **Base de données**: PostgreSQL (Production) / SQLite (Développement local)
- **Authentification**: Laravel Breeze
- **Déploiement**: [Railway](https://railway.app/)

---

## 🚀 Installation Locale

### Prérequis
- PHP 8.2+ & Composer
- Node.js 18+ & npm
- PostgreSQL ou SQLite

### Étapes
1. **Cloner & Installer**
   ```bash
   git clone https://github.com/7Bhil/opencampus.git
   cd opencampus
   composer install
   npm install
   ```
2. **Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
3. **Base de données**
   ```bash
   touch database/database.sqlite # Si SQLite
   php artisan migrate --seed
   ```
4. **Lancement**
   ```bash
   npm run dev
   # Dans un autre terminal
   php artisan serve
   ```

---

## 👏 Remerciements & Contributeurs

Ce projet ne serait pas ce qu'il est sans l'effort collectif de cette équipe passionnée :

- **[Bhil .CHITOU](https://github.com/7Bhil)** (Chef de Projet & Développeur Principal)
- **[BIAOU Hésed](https://github.com/AnisOp4z5Y5z)**
- **[MINAWADE Aubierge](https://github.com/Aubie-46)**
- **[VIDAGBANDJI Clarisse](https://github.com/cl-2-mich) **
- **[GNIKPO Jean-Paul](https://github.com/Exauce13)**
- **[YEKEDO Marielle](https://github.com/)**
- **[HOUNGUE Jolidon](https://github.com/joboy05)**
- Tous les contributeurs et testeurs qui ont aidé à améliorer la plateforme.

---

## 📄 Licence
Ce projet est sous licence MIT.

**Développé avec ❤️ par l'équipe OpenCampus**
