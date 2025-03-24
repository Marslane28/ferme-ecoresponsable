<?php
// Paramètres de connexion à la base de données
$db_host = 'localhost';
$db_user = 'root';
$db_password = '';  // Mot de passe vide par défaut pour XAMPP
$db_name = 'CsiProjet';

// Création de la connexion
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Vérification de la connexion
if ($conn->connect_error) {
    die('Erreur de connexion : ' . $conn->connect_error);
}

// Configuration de l'encodage des caractères
$conn->set_charset("utf8mb4");

// Fonction pour échapper les données
function escape($conn, $value) {
    return $conn->real_escape_string(htmlspecialchars(trim($value)));
}

// Fonction pour valider une date
function validateDate($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}
?> 