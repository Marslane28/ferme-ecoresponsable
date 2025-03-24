<?php
session_start();
require_once '../config.php';

// Fonction pour rediriger avec un message
function redirectWithMessage($type, $message) {
    $_SESSION['message'] = [
        'type' => $type,
        'text' => $message
    ];
    header('Location: ../index.php#woofers');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Validation des données
        $required_fields = ['nom', 'prenom', 'email', 'telephone', 'dateDebut', 'mission'];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Le champ $field est requis.");
            }
        }

        // Validation de l'email
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("L'adresse email n'est pas valide.");
        }

        // Validation des dates
        $dateDebut = new DateTime($_POST['dateDebut']);
        $dateFin = !empty($_POST['dateFin']) ? new DateTime($_POST['dateFin']) : null;
        
        if ($dateFin && $dateFin < $dateDebut) {
            throw new Exception("La date de fin ne peut pas être antérieure à la date de début.");
        }

        // Échappement des données
        $nom = escape($conn, $_POST['nom']);
        $prenom = escape($conn, $_POST['prenom']);
        $email = escape($conn, $_POST['email']);
        $telephone = escape($conn, $_POST['telephone']);
        $dateDebutStr = $dateDebut->format('Y-m-d');
        $dateFinStr = $dateFin ? $dateFin->format('Y-m-d') : null;
        $mission = escape($conn, $_POST['mission']);
        $statut = 'Actif';

        // Début de la transaction
        $conn->begin_transaction();

        // Insertion dans la table Personne
        $stmt = $conn->prepare("INSERT INTO Personne (nom, prenom, email, telephone) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nom, $prenom, $email, $telephone);
        $stmt->execute();
        $idPersonne = $conn->insert_id;

        // Insertion dans la table Woofer
        $stmt = $conn->prepare("INSERT INTO Woofer (idWoofer, dateArrivee, dateFin, typeDeMission, statut) VALUES (?, ?, ?, ?, 'Actif')");
        $stmt->bind_param("isss", $idPersonne, $dateDebutStr, $dateFinStr, $mission);
        $stmt->execute();

        // Validation de la transaction
        $conn->commit();
        redirectWithMessage('success', 'Le Woofer a été ajouté avec succès !');

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