<?php
session_start();

// Vérification que l'utilisateur est connecté et a le rôle 'user'
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: connexion.php");
    exit;
}

require_once 'config.php';

// Récupération des livres depuis la base de données
$stmt = $pdo->prepare("SELECT * FROM livres");
$stmt->execute();
$livres = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord de l'utilisateur</title>
    <!-- Lien vers le CDN de Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <!-- Entête -->
    <div class="bg-gradient-to-r from-teal-400 to-teal-600 py-16">
        <div class="max-w-4xl mx-auto text-center text-white">
            <h1 class="text-5xl font-bold mb-4">Tableau de bord de l'utilisateur</h1>
            <p class="text-xl mb-8">Bienvenue sur votre espace personnel</p>
            <!-- Lien de déconnexion -->
            <a href="logout.php" class="inline-block px-6 py-2 mt-6 text-sm font-semibold text-white bg-red-600 rounded-full shadow-md hover:bg-red-700 transition duration-300 ease-in-out transform hover:scale-105">
                Se déconnecter
            </a>
        </div>
    </div>

    <!-- Affichage des livres -->
    <div class="max-w-6xl mx-auto p-8 bg-white rounded-2xl shadow-2xl mt-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Liste des livres disponibles</h2>
        <table class="min-w-full table-auto border-collapse">
            <thead>
                <tr class="bg-gradient-to-r from-teal-400 to-teal-600 text-white">
                    <th class="px-6 py-3 text-left text-sm font-medium">Titre</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Auteur</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Année de publication</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Genre</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($livres as $livre): ?>
                <tr class="border-b hover:bg-teal-100 transition-all duration-300">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900"><?php echo htmlspecialchars($livre['titre']); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-600"><?php echo htmlspecialchars($livre['auteur']); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-600"><?php echo htmlspecialchars($livre['annee_publication']); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-600"><?php echo htmlspecialchars($livre['genre']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
