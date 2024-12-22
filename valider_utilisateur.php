<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

require_once 'config.php';

if (isset($_GET['id'])) {
    $id_utilisateur = $_GET['id'];

    // Mettre à jour l'utilisateur pour le valider
    $stmt = $pdo->prepare("UPDATE utilisateurs SET valide = TRUE WHERE id = ?");
    $stmt->execute([$id_utilisateur]);

    // Redirection vers la page des nouvelles inscriptions après validation
    header("Location: admin_dashboard.php");
    exit;
}
?>
