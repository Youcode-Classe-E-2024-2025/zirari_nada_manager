<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

require_once 'config.php';

if (isset($_GET['id'])) {
    $id_utilisateur = $_GET['id'];

    // Supprimer l'utilisateur de la base de données ou l'archiver
    $stmt = $pdo->prepare("UPDATE utilisateurs SET archive = TRUE WHERE id = ?");
    $stmt->execute([$id_utilisateur]);

    // Redirection vers la page des nouvelles inscriptions après rejet
    header("Location: admin_dashboard.php");
    exit;
}
?>
