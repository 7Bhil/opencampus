# 📚 OpenCampus - Plateforme Éducative

![OpenCampus](https://img.shields.io/badge/OpenCampus-Laravel%2012-blueviolet)
![Version](https://img.shields.io/badge/version-1.0.0-green)
![License](https://img.shields.io/badge/license-MIT-blue)

**OpenCampus** est une plateforme web innovante qui permet aux étudiants de publier et vendre leurs livres, et aux professeurs de gérer des devoirs avec un système de correction collaboratif.

## ✨ Fonctionnalités Principales

### 👨‍🎓 Pour les Étudiants
- 📖 Publication et vente de livres/ebooks
- 📝 Rendu de devoirs en ligne
- 👀 Consultation des cours
- ❤️ Système de "likes" pour les cours
- 📱 Interface responsive

### 👨‍🏫 Pour les Professeurs
- 📚 Création et gestion de cours
- 📋 Publication de devoirs avec échéances
- 📄 Correction des travaux étudiants
- ⭐ Attribution de notes et commentaires
- 📊 Statistiques de performance

### 👨‍💼 Pour les Admins
- 👥 Gestion des utilisateurs
- 📈 Tableaux de bord analytiques
- ⚙️ Configuration de la plateforme

## 🛠️ Technologies Utilisées

- **Backend**: Laravel 12, PHP 8.3
- **Frontend**: Vue.js 3, Inertia.js, Tailwind CSS
- **Base de données**: SQLite / MySQL
- **Authentification**: Laravel Breeze
- **Gestion de fichiers**: Laravel Storage
- **Interface**: Design responsive avec Tailwind CSS

## 📁 Structure du Projet

```
opencampus/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/           # Contrôleurs administration
│   │   ├── Etudiant/        # Contrôleurs étudiants
│   │   ├── Professeur/      # Contrôleurs professeurs
│   │   └── Auth/           # Authentification
│   ├── Models/             # Modèles Eloquent
│   │   ├── User.php
│   │   ├── Cours.php
│   │   ├── Devoir.php
│   │   └── Soumission.php
│   └── View/Components/    # Composants Blade
├── database/
│   ├── migrations/         # Migrations
│   └── seeders/           # Seeders de données
├── public/                # Assets publics
├── resources/
│   ├── js/
│   │   ├── Pages/         # Pages Inertia/Vue
│   │   │   ├── Admin/
│   │   │   ├── Etudiants/
│   │   │   ├── Professeurs/
│   │   │   └── Auth/
│   │   └── Layouts/       # Layouts partagés
│   └── views/             # Vues Blade
└── storage/               # Fichiers uploadés
    ├── app/public/
    │   ├── cours/         # Fichiers de cours
    │   ├── devoirs/       # Énoncés de devoirs
    │   └── soumissions/   # Soumissions étudiants
    └── framework/
```

## 🚀 Installation

### Prérequis
- PHP 8.2+
- Composer
- Node.js 18+
- npm ou yarn
- SQLite ou MySQL

### Étapes d'installation

1. **Cloner le projet**
```bash
git clone https://github.com/votre-username/opencampus.git
cd opencampus
```

2. **Installer les dépendances PHP**
```bash
composer install
```

3. **Installer les dépendances JavaScript**
```bash
npm install
```

4. **Copier le fichier d'environnement**
```bash
cp .env.example .env
```

5. **Générer la clé d'application**
```bash
php artisan key:generate
```

6. **Configurer la base de données**
```bash
# Pour SQLite (recommandé pour le développement)
touch database/database.sqlite

# Pour MySQL, configurer les variables DB_* dans .env
```

7. **Exécuter les migrations et seeders**
```bash
php artisan migrate --seed
```

8. **Compiler les assets**
```bash
npm run build
# ou pour le développement
npm run dev
```

9. **Démarrer le serveur**
```bash
php artisan serve
```

## 👥 Utilisateurs par Défaut

Après le seeding, ces utilisateurs sont créés :

### Admin
- **Email**: Admin@gmail.com
- **Mot de passe**: password
- **Type de compte**: Admin

### Professeur
- **Email**: professeur@gmail.com
- **Mot de passe**: password
- **Type de compte**: Professeur

### Étudiant
- **Email**: etudiant@gmail.com
- **Mot de passe**: password
- **Type de compte**: Étudiant

## 📚 Fonctionnalités Détaillées

### 📖 Publication de Livres (Étudiants)
- Interface drag & drop pour upload
- Options de prix et de visibilité
- Prévisualisation avant publication
- Gestion des droits d'auteur

### 📝 Système de Devoirs
- **Création de devoirs** par les professeurs
- **Upload de fichiers** (PDF, DOC, TXT)
- **Dates limites** avec notifications
- **Correction en ligne** avec annotations
- **Feedback détaillé** avec notes

### 🎓 Cours en Ligne
- Organisation par matières
- Support multi-format (PDF, vidéos, liens)
- Système de notation par les étudiants
- Statistiques d'engagement

## 🔒 Sécurité

- **Authentification** multi-role (Admin, Professeur, Étudiant)
- **Middleware** de vérification d'email
- **Protection CSRF**
- **Validation des fichiers uploadés**
- **Permissions basées sur les rôles**

## 🧪 Tests

```bash
# Exécuter les tests PHPUnit
php artisan test

# Exécuter les tests avec coverage
php artisan test --coverage
```

## 📈 Déploiement

### Pour la production

1. **Configurer .env**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-domaine.com

# Configurer la base de données
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=opencampus
DB_USERNAME=username
DB_PASSWORD=password

# Configuration storage
FILESYSTEM_DISK=public
```

2. **Optimiser l'application**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

3. **Configurer le serveur web**
- Nginx/Apache avec PHP-FPM
- SSL/TLS recommandé
- Configuration des permissions storage

## 🐛 Dépannage

### Problèmes courants

1. **Permissions storage**
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

2. **Erreur SQLite**
```bash
# Vérifier que SQLite est installé
php -m | grep sqlite3

# Créer le fichier de base de données
touch database/database.sqlite
```

3. **Erreurs Inertia.js**
```bash
npm run build
# ou
php artisan inertia:start-ssr
```

## 🤝 Contribution

1. Fork le projet
2. Créer une branche (`git checkout -b feature/AmazingFeature`)
3. Commiter les changements (`git commit -m 'Add AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## 👏 Remerciements

- [BIAOU Hésed](https://github.com/AnisOp4z5Y5z)
- [MINAWADE Aubierge](https://github.com/Aubie-46)
- [VIDAGBANDJI Clarisse](https://github.com/cl-2-mich)
- [GNIKPO Jean-Paul](https://github.com/JeanPaulGnikpo)
- [YEKEDO Marielle](https://github.com/)
- [Bhil .CHITOU](https://github.com/7Bhil)
- Tous les contributeurs et testeurs

## 📞 Support

Pour le support, veuillez :
1. Vérifier la [documentation](#)
2. Ouvrir un [issue](https://github.com/votre-username/opencampus/issues)
3. Contacter l'équipe à support@opencampus.com

---

**Développé avec ❤️ par l'équipe OpenCampus**  
*Révolutionnant l'éducation, un étudiant à la fois*
