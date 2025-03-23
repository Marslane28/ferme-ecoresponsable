<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord</title>
    <style>
        .dashboard-block { width: 45%; float: left; margin: 2%; border: 1px solid #ccc; padding: 10px; border-radius: 10px; }
        .title { font-weight: bold; text-align: center; margin-bottom: 10px; }
        canvas { width: 100%; height: 150px; }
    </style>
</head>
<body>
    <div class="dashboard-block">
        <div class="title">Produits en faible stock</div>
        <ul id="produitsFaibles"></ul>
    </div>

    <div class="dashboard-block">
        <div class="title">Dernières ventes</div>
        <ul id="dernieresVentes"></ul>
    </div>

    <div class="dashboard-block">
        <div class="title">Woofers en activité</div>
        <ul id="woofersActifs"></ul>
    </div>

    <div class="dashboard-block">
        <div class="title">Ateliers à venir</div>
        <ul id="ateliersAVenir"></ul>
    </div>

    <script src=""></script>/*mettre le dashboard javascript*/
</body>
</html>
