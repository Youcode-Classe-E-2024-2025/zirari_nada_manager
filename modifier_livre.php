<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

require_once 'config.php';

// Récupérer l'ID du livre à modifier
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Récupérer les informations du livre à partir de la base de données
    $stmt = $pdo->prepare("SELECT * FROM livres WHERE id = ?");
    $stmt->execute([$id]);
    $livre = $stmt->fetch();

    if (!$livre) {
        echo "Livre introuvable.";
        exit;
    }
}

// Mettre à jour les informations du livre
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier_livre'])) {
    $titre = $_POST['titre'];
    $auteur = $_POST['auteur'];
    $annee_publication = $_POST['annee_publication'];
    $genre = $_POST['genre'];

    // Mettre à jour le livre dans la base de données
    $stmt = $pdo->prepare("UPDATE livres SET titre = ?, auteur = ?, annee_publication = ?, genre = ? WHERE id = ?");
    $stmt->execute([$titre, $auteur, $annee_publication, $genre, $id]);

    // Redirection après la modification
    header("Location: admin_dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un livre</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <!-- Menu de navigation -->
    <div class="bg-teal-600 text-white py-4">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            <h1 class="text-3xl font-extrabold">Modifier un livre</h1>
            <div class="space-x-6">
                <a href="admin_dashboard.php" class="hover:text-teal-200">Retour au tableau de bord</a>
            </div>
        </div>
    </div>

    <!-- Formulaire de modification du livre -->
    <div class="max-w-4xl mx-auto p-8 bg-white rounded-2xl shadow-2xl mt-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Modifier les informations du livre</h2>
        <form action="modifier_livre.php?id=<?php echo $livre['id']; ?>" method="POST">
            <div class="mb-4">
                <label for="titre" class="block text-sm font-medium text-gray-700">Titre</label>
                <input type="text" id="titre" name="titre" value="<?php echo htmlspecialchars($livre['titre']); ?>" required class="w-full p-4 mt-2 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent">
            </div>
            <div class="mb-4">
                <label for="auteur" class="block text-sm font-medium text-gray-700">Auteur</label>
                <input type="text" id="auteur" name="auteur" value="<?php echo htmlspecialchars($livre['auteur']); ?>" required class="w-full p-4 mt-2 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent">
            </div>
            <div class="mb-4">
                <label for="annee_publication" class="block text-sm font-medium text-gray-700">Année de publication</label>
                <input type="number" id="annee_publication" name="annee_publication" value="<?php echo htmlspecialchars($livre['annee_publication']); ?>" required class="w-full p-4 mt-2 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent">
            </div>
            <div class="mb-4">
                <label for="genre" class="block text-sm font-medium text-gray-700">Genre</label>
                <input type="text" id="genre" name="genre" value="<?php echo htmlspecialchars($livre['genre']); ?>" required class="w-full p-4 mt-2 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent">
            </div>
            <button type="submit" name="modifier_livre" class="w-full bg-teal-600 text-white p-4 mt-6 rounded-md shadow-lg hover:bg-teal-700 transform transition-all duration-300 hover:scale-105">
                Modifier le livre
            </button>
        </form>
    </div>

</body>
</html>
