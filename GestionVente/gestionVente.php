<?php
function connect(){
    $db_host = 'localhost';
    $db_user = 'root';
    $db_password = 'root';
    $db_db='CsiProjet'; 

    $conn = new mysqli($db_host, $db_user, $db_password, $db_db);

	if ($conn->connect_error) {
		echo 'Errno: '.$conn->connect_errno;
		echo '<br>';
		echo 'Error: '.$conn->connect_error;
		exit();
	}

	return $conn;
}

$conn = connect();

f ($_SERVER["REQUEST_METHOD"] === "GET") {
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
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if ($data["action"] === "ajouter") {
        $stmt = $conn->prepare("INSERT INTO Vente (quantite_vendu, date, montant, idProduit, idWoofer) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isdii", $data["quantite"], $data["date"], $data["montant"], $data["idProduit"], $data["idWoofer"]);
        $stmt->execute();

        // MAJ stock produit
        $update = $conn->prepare("UPDATE Produit SET quantite_stock = quantite_stock - ? WHERE id_produit = ?");
        $update->bind_param("ii", $data["quantite"], $data["idProduit"]);
        $update->execute();

        echo json_encode(["status" => "ok"]);
        exit;
    }
}




$conn -> close();
?>