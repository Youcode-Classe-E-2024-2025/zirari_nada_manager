<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: connexion.php");
    exit;
}

require_once 'config.php';

if (isset($_GET['id'])) {
    $id_utilisateur = $_GET['id'];

    // Archiver l'utilisateur
    $stmt = $pdo->prepare("UPDATE utilisateurs SET archive = TRUE WHERE id = ?");
    $stmt->execute([$id_utilisateur]);

    // Redirection vers la page des utilisateurs validés après archivage
    header("Location: admin_dashboard.php");
    exit;
}
?>
