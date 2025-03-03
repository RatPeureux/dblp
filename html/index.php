<?php
// Connexion à la base de données
$pdo = new PDO("postgresql:host=localhost;dbname=monde", "root", "");

// Récupération des pays
$query = $pdo->query("SELECT continent, country FROM _pays_continent");
$pays = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte Interactive</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <style>
        #map { height: 600px; }
    </style>
</head>
<body>
    <h1>Carte du Monde Interactive</h1>
    <div id="map"></div>

    <script>
        // Initialisation de la carte
        var map = L.map('map').setView([20, 0], 2);

        // Ajout d'un fond de carte OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
    </script>
</body>
</html>