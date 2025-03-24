<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .charts-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            padding: 20px;
        }
        .chart-wrapper {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        canvas {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="charts-container">
        <div class="chart-wrapper">
            <canvas id="salesChart"></canvas>
        </div>
        <div class="chart-wrapper">
            <canvas id="productsChart"></canvas>
        </div>
        <div class="chart-wrapper">
            <canvas id="woofersChart"></canvas>
        </div>
        <div class="chart-wrapper">
            <canvas id="ateliersChart"></canvas>
        </div>
    </div>
    <script src="dashboard.js"></script>
</body>
</html> 