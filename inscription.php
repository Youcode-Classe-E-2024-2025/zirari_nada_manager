<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // Validation du mot de passe avec Regex (côté serveur)
    if (!preg_match("/^[a-zA-Z0-9]{6,}$/", $mot_de_passe)) {
        echo "<p class='text-red-500 text-center mt-4'>Le mot de passe doit contenir au moins 6 caractères alphanumériques.</p>";
        exit;
    }

    // Hachage du mot de passe
    $mot_de_passe_hache = password_hash($mot_de_passe, PASSWORD_BCRYPT);

    // Insertion dans la base de données
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe) VALUES (?, ?, ?)");
    $stmt->execute([$nom, $email, $mot_de_passe_hache]);

    // Redirection vers la page de connexion après inscription réussie
    header("Location: connexion.php");
    exit;  // Assure-toi que le script s'arrête après la redirection
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription à la Bibliothèque</title>
    <!-- Lien vers le CDN de Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <!-- Entête -->
    <div class="bg-gradient-to-r from-green-500 to-teal-600 py-16">
        <div class="max-w-4xl mx-auto text-center text-white">
            <h1 class="text-5xl font-bold mb-4">Inscription à la Bibliothèque</h1>
            <p class="text-xl mb-8">Créez votre compte pour accéder à nos services.</p>
        </div>
    </div>

    <!-- Formulaire d'inscription -->
    <div class="max-w-md mx-auto p-8 bg-white rounded-xl shadow-2xl mt-8">
        <form action="inscription.php" method="POST">
            <label for="nom" class="block text-sm font-medium text-gray-700">Nom :</label>
            <input type="text" id="nom" name="nom" required class="w-full p-4 mt-2 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all duration-300">

            <label for="email" class="block text-sm font-medium text-gray-700 mt-4">Email :</label>
            <input type="email" id="email" name="email" required class="w-full p-4 mt-2 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all duration-300">

            <label for="mot_de_passe" class="block text-sm font-medium text-gray-700 mt-4">Mot de passe :</label>
            <input type="password" id="mot_de_passe" name="mot_de_passe" required class="w-full p-4 mt-2 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition-all duration-300">

            <button type="submit" class="w-full bg-green-600 text-white p-4 mt-6 rounded-md shadow-lg hover:bg-green-700 transform transition-all duration-300 hover:scale-105">
                S'inscrire
            </button>

            <div class="mt-4 text-center">
                <a href="connexion.php" class="text-green-500 text-sm hover:underline">Vous avez déjà un compte ? Connectez-vous ici.</a>
            </div>
        </form>
    </div>

</body>
</html>
