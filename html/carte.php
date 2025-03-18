<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/dbconnection.php';

$stmt = $db->prepare("SELECT * FROM _affiliation WHERE lat IS NOT NULL AND lon IS NOT NULL");
if ($stmt->execute()) {
    $affiliations = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <!-- TAILWIND -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- CSS Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <!-- Leaflet Marker Cluster CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.1/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.1/dist/MarkerCluster.Default.css" />

    <title>Carte</title>
</head>

<body>

    <!-- DIV DE LA MAP -->
    <div id="map" class="h-[500px]"></div>

    <!-- LEAFLET -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- Leaflet Marker Cluster JS -->
    <script src="https://unpkg.com/leaflet.markercluster@1.5.1/dist/leaflet.markercluster.js"></script>

    <!-- Javascript -->
    <script>
        var map = L.map('map').setView([48, 0], 8);

        // Create a marker cluster group
        var markers = L.markerClusterGroup();

        // 
        let irisa_co = [
            ['Lannion', [48.72959, -3.4625469956446002]],
            ['Rennes', [48.11638175, -1.6396373314130999]],
            ['Vannes', [47.644607, -2.7489126544117584]]
        ];

        // Ajouter les coordonnées de l'IRISA SUR LA MAP
        var redIcon = new L.Icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        for (co of irisa_co) {
            L.marker(co[1], { icon: redIcon }).addTo(map).bindPopup("IRISA : " + co[0]);
        }

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        <?php
        foreach ($affiliations as $affiliation) { ?>
            // Add affiliation markers to the cluster group
            var marker = L.marker([<?php echo $affiliation['lat'] . ',' . $affiliation['long']; ?>])
                .bindPopup("<?php echo $affiliation['nom']; ?>");
            markers.addLayer(marker);
            <?php
        }
        ?>

        // Add the markers cluster group to the map
        map.addLayer(markers);
    </script>
</body>

</html>