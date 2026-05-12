<?php
require_once __DIR__ . '/vendor/autoload.php';

$config = new \Config\Database();
$db = $config->connect();

$users = $db->table('user')->get()->getResultArray();

echo "=== Utilisateurs en base de données ===\n";
foreach ($users as $user) {
    echo sprintf("ID: %d | Nom: %s | Email: %s | Rôle: %s\n", 
        $user['id'], 
        $user['name'], 
        $user['email'], 
        $user['role']
    );
}
