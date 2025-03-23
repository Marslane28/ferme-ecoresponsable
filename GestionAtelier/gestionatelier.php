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
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if ($data["action"] === "ajouter") {
        $stmt = $conn->prepare("INSERT INTO Atelier (id, nom, categorie, date, idWoofer, nombrePlaces, statut) VALUES (?, ?, ?, ?, ?, ?, 'Planifié')");
        $stmt->bind_param("isssii", $data["id"], $data["nom"], $data["categorie"], $data["date"], $data["idWoofer"], $data["places"]);
        $stmt->execute();
        echo json_encode(["status" => "ajouté"]);
        exit;
    }

    if ($data["action"] === "modifier") {
        $stmt = $conn->prepare("UPDATE Atelier SET nom=?, categorie=?, date=?, idWoofer=?, nombrePlaces=? WHERE id=?");
        $stmt->bind_param("sssiii", $data["nom"], $data["categorie"], $data["date"], $data["idWoofer"], $data["places"], $data["id"]);
        $stmt->execute();
        echo json_encode(["status" => "modifié"]);
        exit;
    }

    if ($data["action"] === "supprimer") {
        $stmt = $conn->prepare("DELETE FROM Atelier WHERE id=?");
        $stmt->bind_param("i", $data["id"]);
        $stmt->execute();
        echo json_encode(["status" => "supprimé"]);
        exit;
    }
}


$conn->close();
?>