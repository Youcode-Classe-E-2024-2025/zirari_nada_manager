<?php
require_once 'config.php';

// Désarchiver un utilisateur
if (isset($_GET['desarchiver_id'])) {
    $id_utilisateur = $_GET['desarchiver_id'];

    // Mettre à jour la base de données pour désarchiver
    $stmt = $pdo->prepare("UPDATE utilisateurs SET archive = FALSE WHERE id = ?");
    $stmt->execute([$id_utilisateur]);

    // Redirection après désarchivage
    header("Location: utilisateurs_archives.php");
    exit;
}

// Récupérer les utilisateurs archivés
$stmt_archives = $pdo->prepare("SELECT * FROM utilisateurs WHERE archive = TRUE");
$stmt_archives->execute();
$utilisateurs_archives = $stmt_archives->fetchAll();
?>

<div class="max-w-4xl mx-auto p-8 bg-white rounded-2xl shadow-2xl mt-8">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Utilisateurs archivés</h2>
    <table class="min-w-full table-auto border-collapse">
        <thead>
            <tr class="bg-gray-100 text-gray-700">
                <th class="px-6 py-3 text-left text-sm font-medium">Nom</th>
                <th class="px-6 py-3 text-left text-sm font-medium">Email</th>
                <th class="px-6 py-3 text-left text-sm font-medium">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($utilisateurs_archives as $utilisateur): ?>
            <tr class="border-b hover:bg-gray-50 transition-all duration-300">
                <td class="px-6 py-4 text-sm font-medium text-gray-900"><?php echo htmlspecialchars($utilisateur['nom']); ?></td>
                <td class="px-6 py-4 text-sm text-gray-600"><?php echo htmlspecialchars($utilisateur['email']); ?></td>
                <td class="px-6 py-4 text-sm">
                    <a href="utilisateurs_archives.php?desarchiver_id=<?php echo $utilisateur['id']; ?>" class="text-teal-500 hover:text-teal-700 font-semibold transition-all duration-300">
                        Désarchiver
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
