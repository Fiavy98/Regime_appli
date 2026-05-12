<?php
// Simuler une requête avec une session d'admin
require_once __DIR__ . '/public/index.php';

// Créer une session simulée
session_start();
$_SESSION['id_user'] = 3;  // Admin Jean
$_SESSION['role'] = 'admin';
$_SESSION['nom'] = 'Admin Jean';
$_SESSION['email'] = 'admin@gmail.com';

// Sauvegarder la session
session_write_close();

echo "Session créée pour l'utilisateur admin.\n";
echo "ID: " . $_SESSION['id_user'] . "\n";
echo "Rôle: " . $_SESSION['role'] . "\n";
