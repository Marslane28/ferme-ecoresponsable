<?php
session_start();
require_once '../config.php';

// Fonction pour rediriger avec un message
function redirectWithMessage($type, $message) {
    $_SESSION['message'] = [
        'type' => $type,
        'text' => $message
    ];
    header('Location: ../index.php#ateliers');
		exit();
	}

// Gestion des requêtes GET
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (isset($_GET["action"])) {
        if ($_GET["action"] === "ateliers") {
            $res = $conn->query("SELECT * FROM Atelier");
            echo json_encode($res->fetch_all(MYSQLI_ASSOC));
            exit;
        }
        if ($_GET["action"] === "woofers") {
            $res = $conn->query("SELECT idWoofer FROM Woofer");
            echo json_encode($res->fetch_all(MYSQLI_ASSOC));
            exit;
        }
        if ($_GET["action"] === "inscriptions") {
            $id = $_GET["id"];
            $sql = "SELECT * FROM Inscription WHERE idAtelier = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            echo json_encode($result->fetch_all(MYSQLI_ASSOC));
            exit;
        }
    }
}

// Gestion des requêtes POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Validation des données
        $required_fields = ['nom', 'description', 'date', 'duree', 'places'];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Le champ $field est requis.");
            }
        }

        // Validation de la date
        $date = new DateTime($_POST['date']);
        if ($date < new DateTime()) {
            throw new Exception("La date de l'atelier doit être future.");
        }

        // Validation du nombre de places
        if (!is_numeric($_POST['places']) || $_POST['places'] <= 0) {
            throw new Exception("Le nombre de places doit être un nombre positif.");
        }

        // Validation de la durée
        if (!is_numeric($_POST['duree']) || $_POST['duree'] <= 0) {
            throw new Exception("La durée doit être un nombre positif.");
        }

        // Début de la transaction
        $conn->begin_transaction();

        // Insertion dans la table Atelier
        $stmt = $conn->prepare("INSERT INTO Atelier (nom, description, date, duree, places_disponibles) VALUES (?, ?, ?, ?, ?)");
        $dateStr = $date->format('Y-m-d H:i:s');
        $stmt->bind_param("sssis", $_POST['nom'], $_POST['description'], $dateStr, $_POST['duree'], $_POST['places']);
        $stmt->execute();

        // Validation de la transaction
        $conn->commit();
        redirectWithMessage('success', 'L\'atelier a été créé avec succès !');

    } catch (Exception $e) {
        // Annulation de la transaction en cas d'erreur
        if ($conn->connect_errno === 0) {
            $conn->rollback();
        }
        redirectWithMessage('error', 'Erreur : ' . $e->getMessage());
    }
}

$conn->close();
?>