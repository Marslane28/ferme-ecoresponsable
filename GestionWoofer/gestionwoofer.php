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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if ($data["action"] === "ajouter") {
        $stmt1 = $conn->prepare("INSERT INTO Personne (id_personne, nom, prenom, email, telephone, mot_de_passe, role) VALUES (?, ?, ?, ?, ?, '', 'woofer')");
        $stmt1->bind_param("issss", $data["id"], $data["nom"], $data["prenom"], $data["email"], $data["telephone"]);
        $stmt1->execute();

        $stmt2 = $conn->prepare("INSERT INTO Woofer (idWoofer, dateArrivee, dateFin, typeDeMission, statut) VALUES (?, ?, ?, ?, ?)");
        $stmt2->bind_param("issss", $data["id"], $data["dateDebut"], $data["dateFin"], $data["mission"], $data["statut"]);
        $stmt2->execute();

        echo json_encode(["status" => "ajouté"]);
        exit;
    }

    if ($data["action"] === "modifier") {
        $stmt1 = $conn->prepare("UPDATE Personne SET nom=?, prenom=?, email=?, telephone=? WHERE id_personne=?");
        $stmt1->bind_param("ssssi", $data["nom"], $data["prenom"], $data["email"], $data["telephone"], $data["id"]);
        $stmt1->execute();

        $stmt2 = $conn->prepare("UPDATE Woofer SET dateArrivee=?, dateFin=?, typeDeMission=?, statut=? WHERE idWoofer=?");
        $stmt2->bind_param("ssssi", $data["dateDebut"], $data["dateFin"], $data["mission"], $data["statut"], $data["id"]);
        $stmt2->execute();

        echo json_encode(["status" => "modifié"]);
        exit;
    }

    if ($data["action"] === "supprimer") {
        $stmt2 = $conn->prepare("DELETE FROM Woofer WHERE idWoofer=?");
        $stmt2->bind_param("i", $data["id"]);
        $stmt2->execute();

        $stmt1 = $conn->prepare("DELETE FROM Personne WHERE id_personne=?");
        $stmt1->bind_param("i", $data["id"]);
        $stmt1->execute();

        echo json_encode(["status" => "supprimé"]);
        exit;
    }
}


$conn->close();


?>