
<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: connexion.php");
    exit;
}


require_once 'config.php';

// Récupération des statistiques
$stmt_total_utilisateurs = $pdo->prepare("SELECT COUNT(*) AS total FROM utilisateurs");
$stmt_total_utilisateurs->execute();
$total_utilisateurs = $stmt_total_utilisateurs->fetchColumn();

$stmt_utilisateurs_valides = $pdo->prepare("SELECT COUNT(*) AS total FROM utilisateurs WHERE valide = TRUE AND archive = FALSE");
$stmt_utilisateurs_valides->execute();
$total_valides = $stmt_utilisateurs_valides->fetchColumn();

$stmt_utilisateurs_non_valides = $pdo->prepare("SELECT COUNT(*) AS total FROM utilisateurs WHERE valide = FALSE AND archive = FALSE");
$stmt_utilisateurs_non_valides->execute();
$total_non_valides = $stmt_utilisateurs_non_valides->fetchColumn();

$stmt_utilisateurs_archives = $pdo->prepare("SELECT COUNT(*) AS total FROM utilisateurs WHERE archive = TRUE");
$stmt_utilisateurs_archives->execute();
$total_archives = $stmt_utilisateurs_archives->fetchColumn();

$stmt_total_livres = $pdo->prepare("SELECT COUNT(*) AS total FROM livres");
$stmt_total_livres->execute();
$total_livres = $stmt_total_livres->fetchColumn();

// Récupération des utilisateurs non validés
$stmt_non_valides = $pdo->prepare("SELECT * FROM utilisateurs WHERE valide = FALSE AND archive = FALSE");
$stmt_non_valides->execute();
$utilisateurs_non_valides = $stmt_non_valides->fetchAll();

// Récupération des utilisateurs validés
$stmt_valides = $pdo->prepare("SELECT * FROM utilisateurs WHERE valide = TRUE AND archive = FALSE");
$stmt_valides->execute();
$utilisateurs_valides = $stmt_valides->fetchAll();

// Récupération des livres
$stmt_livres = $pdo->prepare("SELECT * FROM livres");
$stmt_livres->execute();
$livres = $stmt_livres->fetchAll();

// Ajouter un livre
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_livre'])) {
    $titre = $_POST['titre'];
    $auteur = $_POST['auteur'];
    $annee_publication = $_POST['annee_publication'];
    $genre = $_POST['genre'];

    // Insertion du livre dans la base de données
    $stmt = $pdo->prepare("INSERT INTO livres (titre, auteur, annee_publication, genre) VALUES (?, ?, ?, ?)");
    $stmt->execute([$titre, $auteur, $annee_publication, $genre]);

    // Redirection après l'ajout
    header("Location: admin_dashboard.php");
    exit;
}

// Supprimer un livre
if (isset($_GET['supprimer_livre'])) {
    $id_livre = $_GET['supprimer_livre'];
    $stmt = $pdo->prepare("DELETE FROM livres WHERE id = ?");
    $stmt->execute([$id_livre]);

    // Redirection après la suppression
    header("Location: admin_dashboard.php");
    exit;
}

// Changer le rôle d'un utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['changer_role'])) {
    $id_utilisateur = $_POST['id'];
    $nouveau_role = $_POST['role'];

    // Vérification si le rôle est valide
    if (!in_array($nouveau_role, ['user', 'admin'])) {
        die("Rôle invalide.");
    }

    // Mise à jour du rôle de l'utilisateur dans la base de données
    $stmt = $pdo->prepare("UPDATE utilisateurs SET role = ? WHERE id = ?");
    $stmt->execute([$nouveau_role, $id_utilisateur]);

    // Redirection après la mise à jour
    header("Location: admin_dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Administrateur</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <!-- Menu de navigation -->
    <div class="bg-teal-600 text-white py-4">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            <h1 class="text-3xl font-extrabold">Tableau de bord</h1>
            <div class="space-x-6">
                <a href="admin_dashboard.php" class="hover:text-teal-200">Utilisateurs actifs</a>
                <a href="utilisateurs_archives.php" class="hover:text-teal-200">Comptes archivés</a>
                <a href="nouvelles_inscriptions.php" class="hover:text-teal-200">Nouvelles inscriptions</a>
                <a href="logout.php" class="hover:text-teal-200">Se déconnecter</a>
            </div>
        </div>
    </div>
<!-- Section des statistiques -->
<div class="max-w-4xl mx-auto p-8 bg-white rounded-2xl shadow-2xl mt-8">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Statistiques</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-teal-100 p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-bold text-teal-700">Total Utilisateurs</h3>
            <p class="text-4xl font-extrabold text-teal-900 mt-2"><?php echo $total_utilisateurs; ?></p>
        </div>
        <div class="bg-green-100 p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-bold text-green-700">Utilisateurs Validés</h3>
            <p class="text-4xl font-extrabold text-green-900 mt-2"><?php echo $total_valides; ?></p>
        </div>
        <div class="bg-yellow-100 p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-bold text-yellow-700">Utilisateurs Non Validés</h3>
            <p class="text-4xl font-extrabold text-yellow-900 mt-2"><?php echo $total_non_valides; ?></p>
        </div>
        <div class="bg-gray-100 p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-bold text-gray-700">Utilisateurs Archivés</h3>
            <p class="text-4xl font-extrabold text-gray-900 mt-2"><?php echo $total_archives; ?></p>
        </div>
        <div class="bg-blue-100 p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-bold text-blue-700">Total Livres</h3>
            <p class="text-4xl font-extrabold text-blue-900 mt-2"><?php echo $total_livres; ?></p>
        </div>
    </div>
</div>

    <!-- Section des nouvelles inscriptions -->
    <div class="max-w-4xl mx-auto p-8 bg-white rounded-2xl shadow-2xl mt-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Validation des nouvelles inscriptions</h2>
        <table class="min-w-full table-auto border-collapse">
            <thead>
                <tr class="bg-teal-100 text-teal-700">
                    <th class="px-6 py-3 text-left text-sm font-medium">Nom</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Email</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($utilisateurs_non_valides as $utilisateur): ?>
                <tr class="border-b hover:bg-teal-50 transition-all duration-300">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900"><?php echo htmlspecialchars($utilisateur['nom']); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-600"><?php echo htmlspecialchars($utilisateur['email']); ?></td>
                    <td class="px-6 py-4 text-sm">
                        <a href="valider_utilisateur.php?id=<?php echo $utilisateur['id']; ?>" class="text-teal-500 hover:text-teal-700 font-semibold transition-all duration-300">
                            Approuver
                        </a>
                        <a href="rejeter_utilisateur.php?id=<?php echo $utilisateur['id']; ?>" class="text-red-500 hover:text-red-700 font-semibold transition-all duration-300 ml-4">
                            Rejeter
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Section des utilisateurs validés avec option de changement de rôle et archivage -->
    <div class="max-w-4xl mx-auto p-8 bg-white rounded-2xl shadow-2xl mt-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Utilisateurs validés</h2>
        <table class="min-w-full table-auto border-collapse">
            <thead>
                <tr class="bg-teal-100 text-teal-700">
                    <th class="px-6 py-3 text-left text-sm font-medium">Nom</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Email</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Rôle</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($utilisateurs_valides as $utilisateur): ?>
                <tr class="border-b hover:bg-teal-50 transition-all duration-300">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900"><?php echo htmlspecialchars($utilisateur['nom']); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-600"><?php echo htmlspecialchars($utilisateur['email']); ?></td>
                    <td class="px-6 py-4 text-sm">
                        <form action="admin_dashboard.php" method="POST" class="inline-block">
                            <select name="role" class="p-2 border-2 border-gray-300 rounded-md">
                                <option value="user" <?php echo $utilisateur['role'] == 'user' ? 'selected' : ''; ?>>Utilisateur</option>
                                <option value="admin" <?php echo $utilisateur['role'] == 'admin' ? 'selected' : ''; ?>>Administrateur</option>
                            </select>
                            <input type="hidden" name="id" value="<?php echo $utilisateur['id']; ?>">
                            <button type="submit" name="changer_role" class="ml-2 text-blue-500 hover:text-blue-700 font-semibold transition-all duration-300">
                                Modifier
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <a href="archiver_utilisateur.php?id=<?php echo $utilisateur['id']; ?>" class="text-blue-500 hover:text-blue-700 font-semibold transition-all duration-300">
                            Archiver
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Section des livres -->
    <div class="max-w-4xl mx-auto p-8 bg-white rounded-2xl shadow-2xl mt-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Gestion des livres</h2>
        
        <!-- Formulaire d'ajout de livre -->
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Ajouter un nouveau livre</h3>
        <form action="admin_dashboard.php" method="POST">
            <div class="mb-4">
                <label for="titre" class="block text-sm font-medium text-gray-700">Titre</label>
                <input type="text" id="titre" name="titre" required class="w-full p-4 mt-2 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent">
            </div>
            <div class="mb-4">
                <label for="auteur" class="block text-sm font-medium text-gray-700">Auteur</label>
                <input type="text" id="auteur" name="auteur" required class="w-full p-4 mt-2 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent">
            </div>
            <div class="mb-4">
                <label for="annee_publication" class="block text-sm font-medium text-gray-700">Année de publication</label>
                <input type="number" id="annee_publication" name="annee_publication" required class="w-full p-4 mt-2 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent">
            </div>
            <div class="mb-4">
                <label for="genre" class="block text-sm font-medium text-gray-700">Genre</label>
                <input type="text" id="genre" name="genre" required class="w-full p-4 mt-2 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent">
            </div>
            <button type="submit" name="ajouter_livre" class="w-full bg-teal-600 text-white p-4 mt-6 rounded-md shadow-lg hover:bg-teal-700 transform transition-all duration-300 hover:scale-105">
                Ajouter le livre
            </button>
        </form>
        
        <!-- Liste des livres -->
        <h3 class="text-xl font-semibold text-gray-800 mt-8 mb-4">Liste des livres</h3>
        <table class="min-w-full table-auto border-collapse">
            <thead>
                <tr class="bg-teal-100 text-teal-700">
                    <th class="px-6 py-3 text-left text-sm font-medium">Titre</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Auteur</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Année</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Genre</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($livres as $livre): ?>
                <tr class="border-b hover:bg-teal-50 transition-all duration-300">
                    <td class="px-6 py-4 text-sm"><?php echo htmlspecialchars($livre['titre']); ?></td>
                    <td class="px-6 py-4 text-sm"><?php echo htmlspecialchars($livre['auteur']); ?></td>
                    <td class="px-6 py-4 text-sm"><?php echo htmlspecialchars($livre['annee_publication']); ?></td>
                    <td class="px-6 py-4 text-sm"><?php echo htmlspecialchars($livre['genre']); ?></td>
                    <td class="px-6 py-4 text-sm">
                        <a href="admin_dashboard.php?supprimer_livre=<?php echo $livre['id']; ?>" class="text-red-500 hover:text-red-700 font-semibold transition-all duration-300">
                            Supprimer
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
