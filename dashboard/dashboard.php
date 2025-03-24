<?php
session_start();
require_once '../config.php';

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
    $response = [];

    switch ($action) {
        case "ventes_mensuelles":
            // Récupérer le nombre de ventes des 5 derniers mois
            $sql = "SELECT 
                    DATE_FORMAT(date, '%Y-%m') as mois,
                    COUNT(*) as nombre_ventes
                    FROM Vente 
                    WHERE date >= DATE_SUB(CURDATE(), INTERVAL 5 MONTH)
                    GROUP BY DATE_FORMAT(date, '%Y-%m')
                    ORDER BY mois ASC";
            $result = $conn->query($sql);
            $ventes = [];
            while ($row = $result->fetch_assoc()) {
                $ventes[] = $row;
            }
            $response['ventes'] = $ventes;
            break;

        case "produits_stock":
            // Récupérer les produits en stock faible (moins de 10 unités)
            $sql = "SELECT 
                    nom,
                    quantite_stock as quantite
                    FROM Produit
                    WHERE quantite_stock < 10 AND etat = 'En_stock'
                    ORDER BY quantite_stock ASC
                    LIMIT 5";
            $result = $conn->query($sql);
            $produits = [];
            while ($row = $result->fetch_assoc()) {
                $produits[] = $row;
            }
            $response['produits'] = $produits;
            break;

        case "woofers_statut":
            // Récupérer le nombre de Woofers actifs et inactifs
            $sql = "SELECT 
                    CASE 
                        WHEN statut = 'Actif' THEN 'Actif'
                        ELSE 'Inactif'
                    END as statut,
                    COUNT(*) as nombre
                    FROM Woofer
                    GROUP BY CASE 
                        WHEN statut = 'Actif' THEN 'Actif'
                        ELSE 'Inactif'
                    END";
            $result = $conn->query($sql);
            $woofers = [];
            while ($row = $result->fetch_assoc()) {
                $woofers[] = $row;
            }
            $response['woofers'] = $woofers;
            break;

        case "ateliers_venir":
            // Récupérer les 3 prochains ateliers avec leur taux de remplissage
            $sql = "SELECT 
                    nom,
                    date,
                    nombrePlaces,
                    (SELECT COUNT(*) FROM Inscription i WHERE i.idAtelier = a.id) as inscrits
                    FROM Atelier a
                    WHERE date >= CURDATE() AND statut = 'Planifié'
                    ORDER BY date ASC
                    LIMIT 3";
            $result = $conn->query($sql);
            $ateliers = [];
            while ($row = $result->fetch_assoc()) {
                $row['taux_remplissage'] = ($row['inscrits'] / $row['nombrePlaces']) * 100;
                $ateliers[] = $row;
            }
            $response['ateliers'] = $ateliers;
            break;

        case "statistiques_globales":
            // Statistiques globales
            $stats = [];
            
            // Total des ventes du mois
            $sql = "SELECT COUNT(*) as total FROM Vente WHERE MONTH(date) = MONTH(CURRENT_DATE())";
            $result = $conn->query($sql);
            $stats['ventes_mois'] = $result->fetch_assoc()['total'] ?? 0;
            
            // Nombre de produits en stock faible
            $sql = "SELECT COUNT(*) as total FROM Produit WHERE quantite_stock < 10 AND etat = 'En_stock'";
            $result = $conn->query($sql);
            $stats['produits_stock'] = $result->fetch_assoc()['total'] ?? 0;
            
            // Nombre de Woofers actifs
            $sql = "SELECT COUNT(*) as total FROM Woofer WHERE statut = 'Actif'";
            $result = $conn->query($sql);
            $stats['woofers_actifs'] = $result->fetch_assoc()['total'] ?? 0;
            
            // Nombre d'ateliers à venir
            $sql = "SELECT COUNT(*) as total FROM Atelier WHERE date >= CURDATE() AND statut = 'Planifié'";
            $result = $conn->query($sql);
            $stats['ateliers_venir'] = $result->fetch_assoc()['total'] ?? 0;
            
            $response['stats'] = $stats;
            break;
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

$conn->close();
?>