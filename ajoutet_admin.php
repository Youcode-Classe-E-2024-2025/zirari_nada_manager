<?php
require_once 'config.php';

// Données de l'admin
$nom = 'Admin';
$email = 'zirari@admin.com';
$mot_de_passe = '123456'; // Mot de passe en clair

// Hachage du mot de passe
$mot_de_passe_hache = password_hash($mot_de_passe, PASSWORD_BCRYPT);

// Insertion dans la base de données
$stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES (?, ?, ?, ?)");
$stmt->execute([$nom, $email, $mot_de_passe_hache, 'admin']);

echo "Admin inséré avec succès !";

?>
