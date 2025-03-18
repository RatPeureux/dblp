<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/dbconnection.php';

$stmt = $db->prepare("SELECT * FROM _affiliation WHERE lat IS NOT NULL AND long IS NOT NULL");
if ($stmt->execute()) {
  $affiliations = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">

  <!-- CSS Leaflet -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

  <title>Carte</title>
</head>

<body>

  <!-- DIV DE LA MAP -->
  <div id="map" style="height: 500px"></div>

  <!-- Make sure you put this AFTER Leaflet's CSS -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

  <!-- Fichiers Javascript -->
<script>
var map = L.map('map').setView([48, 0], 8);

let irisa_co = [[48.72959,-3.4625469956446002], [48.11638175,-1.6396373314130999], [47.644607,-2.7489126544117584]];

// Ajouter les coordonnées de l'IRISA SUR LA MAP
for(co of irisa_co) {
  var redIcon = new L.Icon({
  iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
  iconSize: [25, 41],
  iconAnchor: [12, 41],
  popupAnchor: [1, -34],
  shadowSize: [41, 41]
});

L.marker(co, {icon: redIcon}).addTo(map).bindPopup('test');
}

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
maxZoom: 19,
  attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

<?php
foreach($affiliations as $affiliation) { ?>L.marker([<?php echo $affiliation['lat'] . ',' . $affiliation['long']; ?>]).addTo(map).bindPopup('<?php echo $affiliation['nom']; ?>');
<?php
}
?>

</script>
</body>

</html>
