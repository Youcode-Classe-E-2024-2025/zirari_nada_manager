# zirari_nada_manager

 systeme_bibliotheque

## Installation

### 1. Clonage du Repository
```bash
git clone https://github.com/Youcode-Classe-E-2024-2025/zirari_nada_manager
cd zirari_nada-manager
```

### 2. Configuration de la Base de Données
1. Créez une base de données MySQL
2. Importez le script `database.sql`
3. Configurez les paramètres de connexion dans `index.php`

### 3. Installation des Dépendances
```bash
composer install
```

### 4. Démarrage du Serveur
- Utilisez XAMPP, WAMP ou le serveur PHP intégré
```bash
php -S localhost:8000
```

## Connexion Initiale
- **Admin** : zirari@admin.com
- **Mot de passe** : 123456

## Sécurité
- Authentification sécurisée
- Protection CSRF
- Validation des entrées
- Gestion des rôles
