<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // Recherche de l'utilisateur dans la base de données
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $utilisateur = $stmt->fetch();

    if ($utilisateur && password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
        session_start();
        $_SESSION['utilisateur_id'] = $utilisateur['id'];
        $_SESSION['role'] = $utilisateur['role'];

        // Débogage : vérifier la session
        // var_dump($_SESSION); // Décommentez cette ligne si vous voulez voir la session

        // Redirection en fonction du rôle
        if ($utilisateur['role'] === 'admin') {
            header("Location: admin_dashboard.php");
        } elseif ($utilisateur['role'] === 'user') { // Assurez-vous que le rôle est 'user'
            header("Location: utilisateur_dashboard.php");
        } else {
            echo "<p class='text-red-500 text-center mt-4'>Rôle inconnu.</p>";
        }
        exit; // Toujours appeler exit après une redirection
    } else {
        echo "<p class='text-red-500 text-center mt-4'>Identifiants incorrects.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion à la Bibliothèque</title>
    <!-- Lien vers le CDN de Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <!-- Entête -->
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 py-16">
        <div class="max-w-4xl mx-auto text-center text-white">
            <h1 class="text-5xl font-bold mb-4">Connexion à votre compte</h1>
            <p class="text-xl mb-8">Veuillez entrer vos identifiants pour accéder à votre espace personnel.</p>
        </div>
    </div>

    <!-- Formulaire de connexion -->
    <div class="max-w-md mx-auto p-8 bg-white rounded-xl shadow-2xl mt-8">
        <form action="index.php" method="POST">
            <label for="email" class="block text-sm font-medium text-gray-700">Email :</label>
            <input type="email" id="email" name="email" required class="w-full p-4 mt-2 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition-all duration-300">

            <label for="mot_de_passe" class="block text-sm font-medium text-gray-700 mt-4">Mot de passe :</label>
            <input type="password" id="mot_de_passe" name="mot_de_passe" required class="w-full p-4 mt-2 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition-all duration-300">

            <button type="submit" class="w-full bg-blue-600 text-white p-4 mt-6 rounded-md shadow-lg hover:bg-blue-700 transform transition-all duration-300 hover:scale-105">
                Se connecter
            </button>

            <div class="mt-4 text-center">
                <a href="mot_de_passe_oublie.php" class="text-blue-500 text-sm hover:underline">Mot de passe oublié ?</a>
            </div>
        </form>
    </div>

</body>
</html>
