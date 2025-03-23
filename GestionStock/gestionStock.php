<?php

function connect(){
    $db_host = 'localhost';
    $db_user = 'root';
    $db_password = 'root';
    $db_db=''; // Need to be modified to match the db

    $conn = new mysqli($db_host, $db_user, $db_password, $db_db);

	if ($conn->connect_error) {
		echo 'Errno: '.$conn->connect_errno;
		echo '<br>';
		echo 'Error: '.$conn->connect_error;
		exit();
	}

	return $conn;
}


$conn= connect();

//récup les données
if ($_SERVER["REQUEST_METHOD"] === "GET" && $_GET["action"] === "liste") {
    $stmt = $conn->query("SELECT * FROM Produit ORDER BY nom");
    echo json_encode($stmt->fetch_all(MYSQLI_ASSOC));
    exit;
}

//Changement de données par POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if ($data["action"] === "enregistrer") {
        if (!empty($data["id_produit"])) {
            $stmt = $conn->prepare("UPDATE Produit SET nom=?, date_peremption=?, categorie=?, quantite_stock=?, unite=?, prix_unitaire=?, etat=? WHERE id_produit=?");
            $stmt->bind_param("sssisssi",
                $data["nom"],
                $data["date_peremption"],
                $data["categorie"],
                $data["quantite_stock"],
                $data["unite"],
                $data["prix_unitaire"],
                $data["etat"],
                $data["id_produit"]
            );
            $stmt->execute();
        } else {
            $stmt = $conn->prepare("INSERT INTO Produit (nom, date_peremption, categorie, quantite_stock, unite, prix_unitaire, etat) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssisss",
                $data["nom"],
                $data["date_peremption"],
                $data["categorie"],
                $data["quantite_stock"],
                $data["unite"],
                $data["prix_unitaire"],
                $data["etat"]
            );
            $stmt->execute();
        }
        echo json_encode(["status" => "ok"]);
        exit;
    }

    if ($data["action"] === "supprimer") {
        $stmt = $conn->prepare("DELETE FROM Produit WHERE id_produit = ?");
        $stmt->bind_param("i", $data["id_produit"]);
        $stmt->execute();
        echo json_encode(["status" => "supprimé"]);
        exit;
    }
}

$conn->close();

?>