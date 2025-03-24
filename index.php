<?php
require_once 'config.php';
session_start();

// Fonction pour afficher les messages
function showMessage($type, $message) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['message'] = [
        'type' => $type,
        'text' => $message
    ];
}

// Vérifier s'il y a un message à afficher au chargement de la page
$message = isset($_SESSION['message']) ? $_SESSION['message'] : null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de la Ferme</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #84fab0, #8fd3f4);
            color: #333;
            transition: background 0.5s;
            text-align: center;
        }
        nav {
            display: flex;
            justify-content: center;
            background: rgba(255, 255, 255, 0.9);
            padding: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }
        nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
            padding: 0;
        }
        nav ul li a {
            text-decoration: none;
            font-weight: bold;
            color: #333;
            padding: 10px 15px;
            transition: 0.3s;
            cursor: pointer;
        }
        nav ul li a:hover {
            background: #269d38;
            color: white;
            border-radius: 5px;
            transform: scale(1.1);
        }
        .content {
            max-width: 900px;
            margin: 120px auto 50px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            opacity: 0;
            transform: translateY(20px);
            animation: fadeIn 0.5s forwards;
        }
        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .hidden { display: none; }
        .notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #2fad0d;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            display: none;
        }
        button {
            background: linear-gradient(45deg, #386932a2, #4b9d26);
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
        }
        button:hover {
            background: linear-gradient(45deg, #347242, #abc2ae);
            transform: scale(1.05);
        }
        button:active {
            transform: scale(0.95);
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
            font-size: 16px;
        }
        h1 {
            font-size: 24px;
            margin-top: 80px;
        }
        .dashboard-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr); /* 2 colonnes */
    gap: 20px;
    margin-top: 20px;
}

.dashboard-item {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 20px;
    text-align: center;
}

.dashboard-item h3 {
    margin-bottom: 10px;
    font-size: 18px;
    color: #333;
}

canvas {
    max-width: 100%;
    height: auto;
}

        /* Styles améliorés pour les messages d'alerte */
        .alert {
            padding: 15px 20px;
            margin: 20px auto;
            border: 1px solid transparent;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            text-align: center;
            width: 80%;
            max-width: 600px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            opacity: 1;
            transition: opacity 0.5s ease-in-out;
        }

        .alert-success {
            background-color: #4CAF50;
            color: white;
            border-color: #45a049;
        }

        .alert-error {
            background-color: #f44336;
            color: white;
            border-color: #da190b;
        }

        .fade-out {
            opacity: 0;
        }

        /* Style pour le calcul total des ventes */
        #totalDiv {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            font-size: 18px;
            font-weight: bold;
            text-align: right;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        #totalDiv.updated {
            background-color: #e8f5e9;
            box-shadow: 0 4px 8px rgba(76, 175, 80, 0.2);
        }

        #totalSpan {
            color: #2e7d32;
            font-size: 20px;
        }

        /* Style pour les informations de stock */
        .stock-info {
            font-size: 14px;
            color: #666;
            margin-left: 10px;
        }

        /* Style pour les champs de formulaire */
        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .form-group .input-group {
            display: flex;
            align-items: center;
        }

        .form-group .input-group input {
            flex: 1;
            margin-right: 10px;
        }

        .form-group .input-group .unit {
            color: #666;
            font-weight: bold;
        }

        /* Styles pour la gestion des ateliers */
        .atelier-controls {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .search-box {
            flex: 0 0 300px;
        }

        .search-box input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.9em;
            font-weight: bold;
        }

        .status-planifié { background-color: #e3f2fd; color: #1976d2; }
        .status-en_cours { background-color: #e8f5e9; color: #2e7d32; }
        .status-terminé { background-color: #efebe9; color: #5d4037; }
        .status-annulé { background-color: #ffebee; color: #c62828; }

        .actions {
            display: flex;
            gap: 5px;
        }

        .btn-edit, .btn-delete, .btn-view {
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 0.9em;
        }

        .btn-edit { background-color: #2196f3; color: white; }
        .btn-delete { background-color: #f44336; color: white; }
        .btn-view { background-color: #4caf50; color: white; }

        /* Styles pour les modals */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
        }

        .modal-content {
            position: relative;
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            width: 80%;
            max-width: 600px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .close {
            position: absolute;
            right: 20px;
            top: 10px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        #inscriptionsList {
            margin-bottom: 20px;
            max-height: 300px;
            overflow-y: auto;
        }

    </style>
</head>
<body>

    <nav>
        <ul>
            <li><a onclick="showPage('dashboard')">Tableau de Bord</a></li>
            <li><a onclick="showPage('stocks')">Gestion des Stocks</a></li>
            <li><a onclick="showPage('ventes')">Gestion des Ventes</a></li>
            <li><a onclick="showPage('woofers')">Gestion des Woofers</a></li>
            <li><a onclick="showPage('ateliers')">Gestion des Ateliers</a></li>
        </ul>
    </nav>
    
    <div class="notification" id="notification">Nouvelle mise à jour !</div>
    
    <main>
        <div id="dashboard" class="content">
            <h2>Tableau de Bord</h2>
            <div class="dashboard-grid">
                <div class="dashboard-item">
                    <h3>Ventes des 5 derniers mois</h3>
                    <canvas id="salesChart"></canvas>
                </div>
                <div class="dashboard-item">
                    <h3>Produits en stock faible</h3>
                    <canvas id="productsChart"></canvas>
                </div>
                <div class="dashboard-item">
                    <h3>État des Woofers</h3>
                    <canvas id="woofersChart"></canvas>
                </div>
                <div class="dashboard-item">
                    <h3>Ateliers à venir</h3>
                    <canvas id="ateliersChart"></canvas>
                </div>
            </div>
        </div>
        
        
        <div id="stocks" class="content hidden">
            <h2>Gestion des Stocks</h2>
            <div class="message-container" id="messageContainer">
                <?php
                if (isset($_SESSION['message'])) {
                    $messageType = $_SESSION['message']['type'];
                    $messageText = $_SESSION['message']['text'];
                    echo "<div class='alert alert-$messageType'>$messageText</div>";
                    unset($_SESSION['message']);
                }
                ?>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>Date Péremption</th>
                        <th>Quantité</th>
                        <th>Unité</th>
                        <th>Prix</th>
                        <th>État</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT p.*, s.quantite as stock_quantite 
                            FROM Produit p 
                            LEFT JOIN Stock s ON p.id_produit = s.idProduit 
                            ORDER BY p.id_produit DESC";
                    $result = $conn->query($sql);
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>".$row['id_produit']."</td>";
                        echo "<td>".$row['nom']."</td>";
                        echo "<td>".$row['categorie']."</td>";
                        echo "<td>".$row['date_peremption']."</td>";
                        echo "<td>".$row['quantite_stock']."</td>";
                        echo "<td>".$row['unite']."</td>";
                        echo "<td>".number_format($row['prix_unitaire'], 2)." €</td>";
                        echo "<td>".$row['etat']."</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
            <form action="GestionStock/gestionStock.php" method="POST" onsubmit="return validateStockForm(event);">
                <label>Nom du produit :</label>
                <input type="text" name="nom" required>
                
                <label>Catégorie :</label>
                <select name="categorie" required>
                    <option value="Produits d'élevage">Produits d'élevage</option>
                    <option value="Produits laitiers">Produits laitiers</option>
                    <option value="Produits transformés">Produits transformés</option>
                    <option value="Produits dérivés">Produits dérivés</option>
                </select>
                
                <label>Date de péremption :</label>
                <input type="date" name="date_peremption" required>
                
                <label>Quantité :</label>
                <input type="number" name="quantite" min="0" required>
                
                <label>Unité :</label>
                <select name="unite" required>
                    <option value="Unité">Unité</option>
                    <option value="Kilogramme">Kilogramme</option>
                    <option value="Litre">Litre</option>
                    <option value="Douzaine">Douzaine</option>
                </select>
                
                <label>Prix unitaire (€) :</label>
                <input type="number" name="prix" min="0" step="0.01" required>
                
                <button type="submit">Ajouter au stock</button>
            </form>
        </div>
        
        
        <div id="ventes" class="content hidden">
            <h2>Gestion des Ventes</h2>
            <div class="message-container" id="messageContainer">
                <?php
                if (isset($_SESSION['message'])) {
                    $messageType = $_SESSION['message']['type'];
                    $messageText = $_SESSION['message']['text'];
                    echo "<div class='alert alert-$messageType'>$messageText</div>";
                    unset($_SESSION['message']);
                }
                ?>
            </div>
            
            <form action="GestionVente/gestionVente.php" method="POST" onsubmit="return validateVenteForm(event);" class="vente-form">
                <div class="form-group">
                    <label>Produit :</label>
                    <select name="idProduit" id="selectProduit" required onchange="updatePrixUnitaire()">
                        <option value="">Sélectionnez un produit</option>
                        <?php
                        $sql = "SELECT id_produit, nom, quantite_stock, prix_unitaire, unite 
                                FROM Produit 
                                WHERE etat = 'En_stock' 
                                ORDER BY nom";
                        $result = $conn->query($sql);
                        while($row = $result->fetch_assoc()) {
                            echo "<option value='".$row['id_produit']."' 
                                        data-prix='".$row['prix_unitaire']."'
                                        data-stock='".$row['quantite_stock']."'
                                        data-unite='".$row['unite']."'>";
                            echo $row['nom']." (Stock: ".$row['quantite_stock']." ".$row['unite'].")";
                            echo "</option>";
                        }
                        ?>
                    </select>
                    <span class="stock-info" id="stockInfo"></span>
                </div>
                
                <div class="form-group">
                    <label>Quantité :</label>
                    <div class="input-group">
                        <input type="number" name="quantite" id="quantiteVente" min="1" required onchange="calculerTotal()" onkeyup="calculerTotal()">
                        <span class="unit" id="uniteSpan"></span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Prix unitaire (€) :</label>
                    <input type="number" name="prix" id="prixVente" step="0.01" required onchange="calculerTotal()" onkeyup="calculerTotal()">
                </div>
                
                <div id="totalDiv">
                    Total : <span id="totalSpan">0.00</span> €
                </div>
                
                <button type="submit" class="btn-submit">Enregistrer la vente</button>
            </form>

            <h3>Historique des ventes</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Prix unitaire</th>
                        <th>Montant total</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT v.*, p.nom as nom_produit 
                            FROM Vente v 
                            JOIN Produit p ON v.idProduit = p.id_produit 
                            ORDER BY v.date DESC";
                    $result = $conn->query($sql);
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>".$row['id_vente']."</td>";
                        echo "<td>".$row['nom_produit']."</td>";
                        echo "<td>".$row['quantite_vendu']."</td>";
                        echo "<td>".number_format($row['montant']/$row['quantite_vendu'], 2)." €</td>";
                        echo "<td>".number_format($row['montant'], 2)." €</td>";
                        echo "<td>".$row['date']."</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        
        
        <div id="woofers" class="content hidden">
            <h2>Gestion des Woofers</h2>
            <div class="message-container" id="messageContainer">
                <?php
                if (isset($_SESSION['message'])) {
                    $messageType = $_SESSION['message']['type'];
                    $messageText = $_SESSION['message']['text'];
                    echo "<div class='alert alert-$messageType'>$messageText</div>";
                    unset($_SESSION['message']);
                }
                ?>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Date Début</th>
                        <th>Date Fin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT p.id_personne, p.nom, p.email, w.dateArrivee, w.dateFin 
                            FROM Personne p 
                            JOIN Woofer w ON p.id_personne = w.idWoofer";
                    $result = $conn->query($sql);
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>".$row['id_personne']."</td>";
                        echo "<td>".$row['nom']."</td>";
                        echo "<td>".$row['email']."</td>";
                        echo "<td>".$row['dateArrivee']."</td>";
                        echo "<td>".$row['dateFin']."</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
            <form action="GestionWoofer/gestionWoofer.php" method="POST" onsubmit="return validateWooferForm(event);">
                <input type="hidden" name="action" value="ajouter">
                <label>Nom :</label>
                <input type="text" name="nom" required>
                <label>Prénom :</label>
                <input type="text" name="prenom" required>
                <label>Email :</label>
                <input type="email" name="email" required>
                <label>Téléphone :</label>
                <input type="tel" name="telephone" required>
                <label>Date Début :</label>
                <input type="date" name="dateDebut" required>
                <label>Date Fin :</label>
                <input type="date" name="dateFin">
                <label>Type de Mission :</label>
                <select name="mission" required>
                    <option value="Jardinage">Jardinage</option>
                    <option value="Elevage">Élevage</option>
                    <option value="Vente">Vente</option>
                    <option value="Entretien">Entretien</option>
                </select>
                <button type="submit">Ajouter un Woofer</button>
            </form>
        </div>
        
        
        
        <div id="ateliers" class="content hidden">
            <h2>Gestion des Ateliers</h2>
            <div class="message-container" id="messageContainer">
                <?php
                if (isset($_SESSION['message'])) {
                    $messageType = $_SESSION['message']['type'];
                    $messageText = $_SESSION['message']['text'];
                    echo "<div class='alert alert-$messageType'>$messageText</div>";
                    unset($_SESSION['message']);
                }
                ?>
            </div>

            <div class="atelier-controls">
                <button onclick="showAtelierForm('create')" class="btn-primary">Créer un Atelier</button>
                <div class="search-box">
                    <input type="text" id="searchAtelier" placeholder="Rechercher un atelier..." onkeyup="filterAteliers()">
                </div>
            </div>

            <table id="atelierTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>Date</th>
                        <th>Places</th>
                        <th>Statut</th>
                        <th>Animateur</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                    <tbody>
                    <?php
                    $sql = "SELECT a.*, w.typeDeMission as mission, 
                            (SELECT COUNT(*) FROM Inscription i WHERE i.idAtelier = a.id) as inscrits 
                            FROM Atelier a 
                            LEFT JOIN Woofer w ON a.idWoofer = w.idWoofer 
                            ORDER BY a.date DESC";
                    $result = $conn->query($sql);
                    while($row = $result->fetch_assoc()) {
                        $placesRestantes = $row['nombrePlaces'] - $row['inscrits'];
                        $statutClass = strtolower($row['statut']);
                        echo "<tr>";
                        echo "<td>".$row['id']."</td>";
                        echo "<td>".$row['nom']."</td>";
                        echo "<td>".$row['categorie']."</td>";
                        echo "<td>".date('d/m/Y', strtotime($row['date']))."</td>";
                        echo "<td>".$placesRestantes." / ".$row['nombrePlaces']."</td>";
                        echo "<td><span class='status-badge status-".$statutClass."'>".$row['statut']."</span></td>";
                        echo "<td>".$row['mission']."</td>";
                        echo "<td class='actions'>";
                        echo "<button onclick='showAtelierForm(\"edit\", ".json_encode($row).")' class='btn-edit'>Modifier</button>";
                        echo "<button onclick='deleteAtelier(".$row['id'].")' class='btn-delete'>Supprimer</button>";
                        echo "<button onclick='showInscriptions(".$row['id'].")' class='btn-view'>Inscriptions</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                    </tbody>
            </table>

            <!-- Formulaire Modal pour Atelier -->
            <div id="atelierModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h3 id="modalTitle">Nouvel Atelier</h3>
                    <form id="atelierForm" action="GestionAtelier/gestionAtelier.php" method="POST" onsubmit="return validateAtelierForm(event);">
                        <input type="hidden" name="action" id="formAction" value="ajouter">
                        <input type="hidden" name="id" id="atelierId">
                        
                        <div class="form-group">
                            <label>Nom de l'atelier :</label>
                            <input type="text" name="nom" id="atelierNom" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Catégorie :</label>
                            <select name="categorie" id="atelierCategorie" required>
                                <option value="Fromages">Fromages</option>
                                <option value="Légumes">Légumes</option>
                                <option value="Œufs et Lait">Œufs et Lait</option>
                                <option value="Jardinage">Jardinage</option>
                                <option value="Élevage">Élevage</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                <label>Date :</label>
                            <input type="date" name="date" id="atelierDate" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Nombre de places :</label>
                            <input type="number" name="places" id="atelierPlaces" min="1" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Animateur (Woofer) :</label>
                            <select name="idWoofer" id="atelierWoofer" required>
                                <?php
                                $sql = "SELECT w.idWoofer, p.nom, p.prenom 
                                        FROM Woofer w 
                                        JOIN Personne p ON w.idWoofer = p.id_personne 
                                        WHERE w.statut = 'Actif'";
                                $result = $conn->query($sql);
                                while($row = $result->fetch_assoc()) {
                                    echo "<option value='".$row['idWoofer']."'>";
                                    echo $row['nom']." ".$row['prenom'];
                                    echo "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn-submit">Enregistrer</button>
                            <button type="button" class="btn-cancel" onclick="closeModal()">Annuler</button>
                        </div>
            </form>
                </div>
            </div>

            <!-- Modal pour les inscriptions -->
            <div id="inscriptionsModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h3>Inscriptions à l'atelier</h3>
                    <div id="inscriptionsList"></div>
                    <form id="inscriptionForm">
                        <div class="form-group">
                            <label>Nom du participant :</label>
                            <input type="text" id="participantNom" required>
                        </div>
                        <div class="form-group">
                            <label>Email :</label>
                            <input type="email" id="participantEmail" required>
                        </div>
                        <div class="form-group">
                            <label>Téléphone :</label>
                            <input type="tel" id="participantTel">
                        </div>
                        <button type="submit" class="btn-submit">Ajouter participant</button>
                    </form>
                </div>
            </div>
        </div>
        
        
    </main>

    <script>
         function addRow(page) {
        let row;

        if (page === 'woofers') {
            const tableBody = document.querySelector('#woofers table');
            const id = Date.now(); 
            const nom = document.getElementById('wooferNom').value;
            const email = document.getElementById('wooferEmail').value;
            const dateDebut = document.getElementById('wooferDateDebut').value;
            const dateFin = document.getElementById('wooferDateFin').value;

            row = tableBody.insertRow();
            row.insertCell(0).textContent = id;
            row.insertCell(1).textContent = nom;
            row.insertCell(2).textContent = email;
            row.insertCell(3).textContent = dateDebut;
            row.insertCell(4).textContent = dateFin;

            document.getElementById('wooferNom').value = '';
            document.getElementById('wooferEmail').value = '';
            document.getElementById('wooferDateDebut').value = '';
            document.getElementById('wooferDateFin').value = '';
        }
        else if (page === 'stocks') {
    const tableBody = document.querySelector('#stocks table tbody');
    const id = Date.now();
    const nom = document.getElementById('stockNom').value;
    const categorie = document.getElementById('stockCategorie').value;
    const date = document.getElementById('stockDate').value;
    const quantite = document.getElementById('stockQuantite').value;
    const prix = parseFloat(document.getElementById('stockPrix').value).toFixed(2);
    const total = (quantite * prix).toFixed(2);

    row = tableBody.insertRow();
    row.insertCell(0).textContent = id;
    row.insertCell(1).textContent = nom;
    row.insertCell(2).textContent = categorie;
    row.insertCell(3).textContent = date;
    row.insertCell(4).textContent = quantite;
    row.insertCell(5).textContent = prix;
    row.insertCell(6).textContent = total;

    document.getElementById('stockNom').value = '';
    document.getElementById('stockCategorie').value = '';
    document.getElementById('stockDate').value = '';
    document.getElementById('stockQuantite').value = '';
    document.getElementById('stockPrix').value = '';
}

else if (page === 'ventes') {
    const tableBody = document.querySelector('#ventes table tbody');
    const id = Date.now();
    const produit = document.getElementById('venteProduit').value;
    const quantite = document.getElementById('venteQuantite').value;
    const prix = parseFloat(document.getElementById('ventePrix').value).toFixed(2);
    const total = (quantite * prix).toFixed(2);

    row = tableBody.insertRow();
    row.insertCell(0).textContent = id;
    row.insertCell(1).textContent = produit;
    row.insertCell(2).textContent = quantite;
    row.insertCell(3).textContent = prix;
    row.insertCell(4).textContent = total;

    document.getElementById('venteProduit').value = '';
    document.getElementById('venteQuantite').value = '';
    document.getElementById('ventePrix').value = '';
}

        else if (page === 'ateliers') {
            const tableBody = document.querySelector('#ateliers table');
            const id = Date.now(); // Génère un ID unique
            const nomAtelier = document.getElementById('atelierNom').value;
            const date = document.getElementById('atelierDate').value;
            const animateur = document.getElementById('atelierAnimateur').value;

            row = tableBody.insertRow();
            row.insertCell(0).textContent = id;
            row.insertCell(1).textContent = nomAtelier;
            row.insertCell(2).textContent = date;
            row.insertCell(3).textContent = animateur;

            document.getElementById('atelierNom').value = '';
            document.getElementById('atelierDate').value = '';
            document.getElementById('atelierAnimateur').value = '';
        }

        return false;
    }
        document.addEventListener('DOMContentLoaded', function () {
            const ctx1 = document.getElementById('salesChart').getContext('2d');
            new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai'],
                    datasets: [{
                        label: 'Ventes',
                        data: [12, 19, 3, 5, 2],
                        borderColor: 'red',
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: { title: { display: true, text: 'Mois' } },
                        y: { title: { display: true, text: 'Nombre de ventes' } }
                    }
                }
            });
    
            const ctx2 = document.getElementById('productsChart').getContext('2d');
            new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: ['Produit A', 'Produit B', 'Produit C', 'Produit D', 'Produit E'],
                    datasets: [{
                        label: 'Produits finis',
                        data: [20, 15, 30, 10, 25],
                        backgroundColor: 'blue',
                        borderColor: 'blue',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: { title: { display: true, text: 'Produits' } },
                        y: { title: { display: true, text: 'Quantité' } }
                    }
                }
            });
    
            const ctx3 = document.getElementById('woofersChart').getContext('2d');
            new Chart(ctx3, {
                type: 'pie',
                data: {
                    labels: ['Woofers Actifs', 'Woofers Inactifs'],
                    datasets: [{
                        data: [15, 5],
                        backgroundColor: ['green', 'red']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'top' } }
                }
            });
    
            const ctx4 = document.getElementById('ateliersChart').getContext('2d');
            new Chart(ctx4, {
                type: 'doughnut',
                data: {
                    labels: ['Atelier 1', 'Atelier 2', 'Atelier 3'],
                    datasets: [{
                        data: [40, 35, 25],
                        backgroundColor: ['orange', 'blue', 'purple']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'top' } }
                }
            });
        });
    
        // Fonction pour gérer l'affichage des messages
        function handleMessages() {
            const alerts = document.querySelectorAll('.alert');
            if (alerts.length > 0) {
                alerts.forEach(alert => {
                    // Rendre le message visible
                    alert.style.display = 'block';
                    alert.style.opacity = '1';
                    
                    // Faire disparaître le message après 5 secondes
                    setTimeout(() => {
                        alert.classList.add('fade-out');
                        setTimeout(() => {
                            alert.remove();
                        }, 500);
                    }, 5000);
                });
            }
        }

        // Appeler handleMessages au chargement de la page
        document.addEventListener('DOMContentLoaded', handleMessages);

        // Appeler handleMessages après un changement de hash (navigation)
        window.addEventListener('hashchange', handleMessages);

        // Fonction pour afficher la page demandée
        function showPage(pageId) {
            // Cache toutes les pages
            document.querySelectorAll('.content').forEach(page => {
                page.classList.add('hidden');
            });
            
            // Affiche la page demandée
            const page = document.getElementById(pageId);
            if (page) {
                page.classList.remove('hidden');
                // Appeler handleMessages après avoir affiché la page
                handleMessages();
            }
            
            // Met à jour l'URL avec le fragment
            window.location.hash = pageId;
        }

        // Affiche la bonne page au chargement si un fragment est présent dans l'URL
        window.addEventListener('load', () => {
            const hash = window.location.hash.substring(1);
            if (hash) {
                showPage(hash);
            }
        });

        // Fonction pour valider le formulaire de stock
        function validateStockForm(event) {
            const datePeremption = document.querySelector('input[name="date_peremption"]').value;
            const quantite = document.querySelector('input[name="quantite"]').value;
            const prix = document.querySelector('input[name="prix"]').value;
            
            // Validation de la date de péremption
            if (new Date(datePeremption) < new Date()) {
                alert("La date de péremption ne peut pas être dans le passé !");
                event.preventDefault();
                return false;
            }
            
            // Validation de la quantité
            if (quantite <= 0) {
                alert("La quantité doit être supérieure à 0 !");
                event.preventDefault();
                return false;
            }
            
            // Validation du prix
            if (prix <= 0) {
                alert("Le prix doit être supérieur à 0 !");
                event.preventDefault();
                return false;
            }
            
            return true;
        }

        // Fonction pour calculer et afficher le total en temps réel
        function calculerTotal() {
            const quantite = parseFloat(document.getElementById('quantiteVente').value) || 0;
            const prix = parseFloat(document.getElementById('prixVente').value) || 0;
            const total = quantite * prix;
            const totalDiv = document.getElementById('totalDiv');
            
            document.getElementById('totalSpan').textContent = total.toFixed(2);
            
            // Ajoute un effet visuel lors de la mise à jour
            totalDiv.classList.add('updated');
            setTimeout(() => {
                totalDiv.classList.remove('updated');
            }, 300);
        }

        function updatePrixUnitaire() {
            const selectProduit = document.getElementById('selectProduit');
            const option = selectProduit.options[selectProduit.selectedIndex];
            
            if (option.value === "") {
                document.getElementById('prixVente').value = "";
                document.getElementById('uniteSpan').textContent = "";
                document.getElementById('stockInfo').textContent = "";
                document.getElementById('quantiteVente').max = "";
                calculerTotal();
                return;
            }
            
            const prix = option.getAttribute('data-prix');
            const stock = option.getAttribute('data-stock');
            const unite = option.getAttribute('data-unite');
            
            document.getElementById('prixVente').value = prix;
            document.getElementById('uniteSpan').textContent = unite;
            document.getElementById('stockInfo').textContent = `Stock disponible : ${stock} ${unite}`;
            document.getElementById('quantiteVente').max = stock;
            
            calculerTotal();
        }

        function validateVenteForm(event) {
            const selectProduit = document.getElementById('selectProduit');
            const quantite = parseInt(document.getElementById('quantiteVente').value);
            const stockDisponible = parseInt(selectProduit.options[selectProduit.selectedIndex].getAttribute('data-stock'));
            
            if (selectProduit.value === "") {
                alert("Veuillez sélectionner un produit !");
                event.preventDefault();
                return false;
            }
            
            if (quantite > stockDisponible) {
                alert(`Stock insuffisant ! Stock disponible : ${stockDisponible}`);
                event.preventDefault();
                return false;
            }
            
            if (quantite <= 0) {
                alert("La quantité doit être supérieure à 0 !");
                event.preventDefault();
                return false;
            }
            
            const prix = parseFloat(document.getElementById('prixVente').value);
            if (prix <= 0) {
                alert("Le prix doit être supérieur à 0 !");
                event.preventDefault();
                return false;
            }
            
            // Confirmation de la vente
            return confirm(`Confirmez-vous la vente pour un montant total de ${(quantite * prix).toFixed(2)} € ?`);
        }

        function showAtelierForm(mode, data = null) {
            const modal = document.getElementById('atelierModal');
            const form = document.getElementById('atelierForm');
            const title = document.getElementById('modalTitle');
            
            if (mode === 'edit' && data) {
                title.textContent = 'Modifier l\'atelier';
                document.getElementById('formAction').value = 'modifier';
                document.getElementById('atelierId').value = data.id;
                document.getElementById('atelierNom').value = data.nom;
                document.getElementById('atelierCategorie').value = data.categorie;
                document.getElementById('atelierDate').value = data.date;
                document.getElementById('atelierPlaces').value = data.nombrePlaces;
                document.getElementById('atelierWoofer').value = data.idWoofer;
            } else {
                title.textContent = 'Nouvel atelier';
                form.reset();
                document.getElementById('formAction').value = 'ajouter';
                document.getElementById('atelierId').value = '';
            }
            
            modal.style.display = 'block';
        }

        function closeModal() {
            document.getElementById('atelierModal').style.display = 'none';
            document.getElementById('inscriptionsModal').style.display = 'none';
        }

        function validateAtelierForm(event) {
            const date = new Date(document.getElementById('atelierDate').value);
            const places = parseInt(document.getElementById('atelierPlaces').value);
            
            if (date < new Date()) {
                alert('La date de l\'atelier ne peut pas être dans le passé !');
                event.preventDefault();
                return false;
            }
            
            if (places <= 0) {
                alert('Le nombre de places doit être supérieur à 0 !');
                event.preventDefault();
                return false;
            }
            
            return true;
        }

        function deleteAtelier(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cet atelier ?')) {
                fetch('GestionAtelier/gestionAtelier.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'supprimer',
                        id: id
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'supprimé') {
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Une erreur est survenue lors de la suppression');
                });
            }
        }

        function showInscriptions(atelierId) {
            const modal = document.getElementById('inscriptionsModal');
            const list = document.getElementById('inscriptionsList');
            
            fetch(`GestionAtelier/gestionAtelier.php?action=inscriptions&id=${atelierId}`)
                .then(response => response.json())
                .then(data => {
                    list.innerHTML = '';
                    data.forEach(inscription => {
                        const div = document.createElement('div');
                        div.className = 'inscription-item';
                        div.innerHTML = `
                            <strong>${inscription.nom_participant}</strong>
                            <span>${inscription.email_participant}</span>
                            <span>${inscription.telephone_participant || 'Pas de téléphone'}</span>
                        `;
                        list.appendChild(div);
                    });
                });
            
            modal.style.display = 'block';
            
            document.getElementById('inscriptionForm').onsubmit = function(e) {
                e.preventDefault();
                const formData = {
                    action: 'ajouter_inscription',
                    idAtelier: atelierId,
                    nom: document.getElementById('participantNom').value,
                    email: document.getElementById('participantEmail').value,
                    telephone: document.getElementById('participantTel').value
                };
                
                fetch('GestionAtelier/gestionAtelier.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'ajouté') {
                        showInscriptions(atelierId);
                        this.reset();
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Une erreur est survenue lors de l\'inscription');
                });
            };
        }

        function filterAteliers() {
            const input = document.getElementById('searchAtelier');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('atelierTable');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName('td');
                let found = false;
                
                for (let j = 0; j < cells.length - 1; j++) {
                    const cell = cells[j];
                    if (cell) {
                        const text = cell.textContent || cell.innerText;
                        if (text.toLowerCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                }
                
                rows[i].style.display = found ? '' : 'none';
            }
        }

        // Fermer les modals quand on clique en dehors
        window.onclick = function(event) {
            const modals = document.getElementsByClassName('modal');
            for (let modal of modals) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            }
        }

        // Fermer les modals avec le bouton X
        document.querySelectorAll('.close').forEach(close => {
            close.onclick = function() {
                this.parentElement.parentElement.style.display = 'none';
            }
        });

        // Fonction pour formater les dates en français
        function formatDateFR(date) {
            const mois = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
            const d = new Date(date);
            return `${mois[d.getMonth()]}`;
        }

        // Supprimer tout le code des graphiques existant et le remplacer par :
        document.addEventListener('DOMContentLoaded', function() {
            // Graphique des ventes (courbe)
            fetch('dashboard/dashboard.php?action=ventes_mensuelles')
                .then(response => response.json())
                .then(data => {
                    const ctx1 = document.getElementById('salesChart').getContext('2d');
                    if (window.salesChart) window.salesChart.destroy();
                    window.salesChart = new Chart(ctx1, {
                        type: 'line',
                        data: {
                            labels: data.ventes.map(v => {
                                const date = new Date(v.mois);
                                return date.toLocaleDateString('fr-FR', { month: 'short' });
                            }),
                            datasets: [{
                                label: 'Nombre de ventes',
                                data: data.ventes.map(v => v.nombre_ventes),
                                borderColor: '#FF6384',
                                tension: 0.4,
                                fill: false
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Évolution des ventes'
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    });
                });

            // Graphique des produits en stock faible (barres)
            fetch('dashboard/dashboard.php?action=produits_stock')
                .then(response => response.json())
                .then(data => {
                    const ctx2 = document.getElementById('productsChart').getContext('2d');
                    if (window.productsChart) window.productsChart.destroy();
                    window.productsChart = new Chart(ctx2, {
                        type: 'bar',
                        data: {
                            labels: data.produits.map(p => p.nom),
                            datasets: [{
                                label: 'Quantité en stock',
                                data: data.produits.map(p => p.quantite),
                                backgroundColor: '#36A2EB',
                                borderColor: '#36A2EB',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Produits en stock faible'
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    });
                });

            // Graphique des Woofers (camembert)
            fetch('dashboard/dashboard.php?action=woofers_statut')
                .then(response => response.json())
                .then(data => {
                    const ctx3 = document.getElementById('woofersChart').getContext('2d');
                    if (window.woofersChart) window.woofersChart.destroy();
                    window.woofersChart = new Chart(ctx3, {
                        type: 'pie',
                        data: {
                            labels: data.woofers.map(w => `${w.statut} (${w.nombre})`),
                            datasets: [{
                                data: data.woofers.map(w => w.nombre),
                                backgroundColor: ['#4CAF50', '#F44336']
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Répartition des Woofers'
                                }
                            }
                        }
                    });
                });

            // Graphique des ateliers (donut)
            fetch('dashboard/dashboard.php?action=ateliers_venir')
                .then(response => response.json())
                .then(data => {
                    const ctx4 = document.getElementById('ateliersChart').getContext('2d');
                    if (window.ateliersChart) window.ateliersChart.destroy();
                    window.ateliersChart = new Chart(ctx4, {
                        type: 'doughnut',
                        data: {
                            labels: data.ateliers.map(a => `${a.nom} (${Math.round(a.taux_remplissage)}%)`),
                            datasets: [{
                                data: data.ateliers.map(a => a.taux_remplissage),
                                backgroundColor: ['#FF9800', '#2196F3', '#9C27B0']
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Taux de remplissage des ateliers'
                                }
                            }
                        }
                    });
                });
        });

        // Mettre à jour les graphiques toutes les 5 minutes
        setInterval(() => {
            document.dispatchEvent(new Event('DOMContentLoaded'));
        }, 300000);
    </script>
    
</body>
</html>
