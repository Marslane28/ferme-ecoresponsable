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
$conn=connect();


if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $action = $_GET["action"] ?? "";

    switch ($action) {
        case "produits_faibles":
            $res = $conn->query("SELECT nom, quantite_stock FROM Produit ORDER BY quantite_stock ASC LIMIT 5");
            echo json_encode($res->fetch_all(MYSQLI_ASSOC));
            break;

        case "dernieres_ventes":
            $res = $conn->query("SELECT date, SUM(montant) as total FROM Vente GROUP BY date ORDER BY date DESC LIMIT 5");
            echo json_encode($res->fetch_all(MYSQLI_ASSOC));
            break;

        case "woofers_actifs":
            $res = $conn->query("SELECT idWoofer, typeDeMission FROM Woofer WHERE statut = 'Actif' LIMIT 5");
            echo json_encode($res->fetch_all(MYSQLI_ASSOC));
            break;

        case "ateliers_venir":
            $res = $conn->query("SELECT nom, date FROM Atelier WHERE date >= CURDATE() ORDER BY date ASC LIMIT 5");
            echo json_encode($res->fetch_all(MYSQLI_ASSOC));
            break;
    }
    exit;
}


$conn->close();

?>