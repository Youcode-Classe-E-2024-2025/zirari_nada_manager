<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: connexion.php");
    exit;
}

require_once 'config.php';

// Récupération des utilisateurs archivés
$stmt_archives = $pdo->prepare("SELECT * FROM utilisateurs WHERE archive = TRUE");
$stmt_archives->execute();
$utilisateurs_archives = $stmt_archives->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utilisateurs Archivés</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Menu de navigation -->
    <div class="bg-teal-600 text-white py-4">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            <h1 class="text-3xl font-extrabold">Comptes Archivés</h1>
            <div class="space-x-6">
                <a href="admin_dashboard.php" class="hover:text-teal-200">Tableau de bord</a>
                <a href="logout.php" class="hover:text-teal-200">Se déconnecter</a>
            </div>
        </div>
    </div>

    <!-- Liste des utilisateurs archivés -->
    <?php include 'section_desarchiver.php'; ?>
</body>
</html>
