<?php
session_start();
require_once '../config.php';

// Fonction pour rediriger avec un message
function redirectWithMessage($type, $message) {
    $_SESSION['message'] = [
        'type' => $type,
        'text' => $message
    ];
    header('Location: ../index.php#ventes');
		exit();
	}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (isset($_GET["action"])) {
        if ($_GET["action"] === "produits") {
            $res = $conn->query("SELECT id_produit, nom, prix_unitaire FROM Produit");
            echo json_encode($res->fetch_all(MYSQLI_ASSOC));
            exit;
        }
        if ($_GET["action"] === "woofers") {
            $res = $conn->query("SELECT idWoofer FROM Woofer");
            echo json_encode($res->fetch_all(MYSQLI_ASSOC));
            exit;
        }
        if ($_GET["action"] === "ventes") {
            $res = $conn->query("SELECT V.id_vente, P.nom, P.categorie, V.idWoofer, V.date, V.quantite_vendu, P.prix_unitaire, V.montant FROM Vente V JOIN Produit P ON V.idProduit = P.id_produit ORDER BY V.date DESC");
            echo json_encode($res->fetch_all(MYSQLI_ASSOC));
            exit;
        }
        if ($_GET["action"] === "liste_produits") {
            $sql = "SELECT id_produit, nom, quantite_stock, prix_unitaire, unite FROM Produit WHERE etat = 'En_stock' ORDER BY nom";
            $result = $conn->query($sql);
            echo json_encode($result->fetch_all(MYSQLI_ASSOC));
            exit;
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Validation des données
        $required_fields = ['idProduit', 'quantite', 'prix'];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Le champ $field est requis.");
            }
        }

        // Validation des valeurs numériques
        if (!is_numeric($_POST['quantite']) || $_POST['quantite'] <= 0) {
            throw new Exception("La quantité doit être un nombre positif.");
        }
        if (!is_numeric($_POST['prix']) || $_POST['prix'] <= 0) {
            throw new Exception("Le prix doit être un nombre positif.");
        }

        // Échappement et conversion des données
        $idProduit = (int)$_POST['idProduit'];
        $quantite = (int)$_POST['quantite'];
        $prix = (float)$_POST['prix'];
        $date = date('Y-m-d');
        $montant = $quantite * $prix;

        // Début de la transaction
        $conn->begin_transaction();

        // Vérification du stock disponible
        $stmt = $conn->prepare("SELECT quantite_stock, etat FROM Produit WHERE id_produit = ?");
        $stmt->bind_param("i", $idProduit);
        $stmt->execute();
        $result = $stmt->get_result();
        $produit = $result->fetch_assoc();

        if (!$produit) {
            throw new Exception("Produit non trouvé.");
        }

        if ($produit['etat'] !== 'En_stock') {
            throw new Exception("Ce produit n'est pas disponible à la vente.");
        }

        if ($produit['quantite_stock'] < $quantite) {
            throw new Exception("Stock insuffisant. Quantité disponible : " . $produit['quantite_stock']);
        }

        // Insertion de la vente
        $stmt = $conn->prepare("INSERT INTO Vente (quantite_vendu, date, montant, idProduit) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isdi", $quantite, $date, $montant, $idProduit);
        $stmt->execute();

        // Mise à jour du stock
        $nouvelle_quantite = $produit['quantite_stock'] - $quantite;
        $etat = $nouvelle_quantite > 0 ? 'En_stock' : 'En_rupture';
        
        $stmt = $conn->prepare("UPDATE Produit SET quantite_stock = ?, etat = ? WHERE id_produit = ?");
        $stmt->bind_param("isi", $nouvelle_quantite, $etat, $idProduit);
        $stmt->execute();

        // Mise à jour de la table Stock
        $stmt = $conn->prepare("UPDATE Stock SET quantite = ? WHERE idProduit = ?");
        $stmt->bind_param("ii", $nouvelle_quantite, $idProduit);
        $stmt->execute();

        // Validation de la transaction
        $conn->commit();
        redirectWithMessage('success', 'La vente a été enregistrée avec succès !');

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