<?php
session_start();
require_once '../config.php';

// Fonction pour rediriger avec un message
function redirectWithMessage($type, $message) {
    $_SESSION['message'] = [
        'type' => $type,
        'text' => $message
    ];
    header('Location: ../index.php#stocks');
    exit();
}

//récup les données
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["action"]) && $_GET["action"] === "liste") {
    $stmt = $conn->query("SELECT * FROM Produit ORDER BY nom");
    echo json_encode($stmt->fetch_all(MYSQLI_ASSOC));
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Validation des données
        $required_fields = ['nom', 'categorie', 'date_peremption', 'quantite', 'unite', 'prix'];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Le champ $field est requis.");
            }
        }

        // Validation des valeurs numériques
        if (!is_numeric($_POST['quantite']) || $_POST['quantite'] < 0) {
            throw new Exception("La quantité doit être un nombre positif.");
        }
        if (!is_numeric($_POST['prix']) || $_POST['prix'] < 0) {
            throw new Exception("Le prix doit être un nombre positif.");
        }

        // Validation de la date
        if (!validateDate($_POST['date_peremption'])) {
            throw new Exception("La date de péremption n'est pas valide.");
        }

        // Échappement des données
        $nom = escape($conn, $_POST['nom']);
        $categorie = escape($conn, $_POST['categorie']);
        $date_peremption = $_POST['date_peremption'];
        $quantite = (int)$_POST['quantite'];
        $unite = escape($conn, $_POST['unite']);
        $prix = (float)$_POST['prix'];
        $etat = 'En_stock';

        // Début de la transaction
        $conn->begin_transaction();

        // Insertion dans la table Produit
        $stmt = $conn->prepare("INSERT INTO Produit (nom, date_peremption, categorie, quantite_stock, unite, prix_unitaire, etat) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssisss", $nom, $date_peremption, $categorie, $quantite, $unite, $prix, $etat);
        $stmt->execute();
        $id_produit = $conn->insert_id;

        // Insertion dans la table Stock
        $stmt2 = $conn->prepare("INSERT INTO Stock (quantite, idProduit) VALUES (?, ?)");
        $stmt2->bind_param("ii", $quantite, $id_produit);
        $stmt2->execute();

        // Validation de la transaction
        $conn->commit();
        redirectWithMessage('success', 'Le produit a été ajouté avec succès !');

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