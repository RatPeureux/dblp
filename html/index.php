<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/dbconnection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/functions.php';

// NOMBRE DE PUBLICATIONS PAR PAGE
const LIGNES = 13;

$page = $_GET["page"] ?? 0;

if ($page > 0) {
  // Récupérer les publications
  $stmt = $db->prepare("SELECT * FROM _publication OFFSET :offset LIMIT :limite");
  if ($stmt->execute([":offset" => ($page - 1) * LIGNES, ":limite" => LIGNES])) {
    $publications = $stmt->fetchAll();
  }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>R&L - Publications</title>

  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>    
  
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

  <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.1/dist/MarkerCluster.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.1/dist/MarkerCluster.Default.css" />
</head>

<body class='p-[2rem] mx-auto w-[80%]'>

  <?php if ($page == 0) { ?>
  <h1 class='text-3xl font-bold mb-8'>Cartes des affiliations</h1>

  <div id="map" class="h-[75vh]"></div>

  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

  <script src="https://unpkg.com/leaflet.markercluster@1.5.1/dist/leaflet.markercluster.js"></script>

  <script>
      var map = L.map('map').setView([48, 0], 8);

      var markers = L.markerClusterGroup();

      let irisa_co = [[48.72959, -3.4625469956446002], [48.11638175, -1.6396373314130999], [47.644607, -2.7489126544117584]];

      for (co of irisa_co) {
          var redIcon = new L.Icon({
              iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
              iconSize: [25, 41],
              iconAnchor: [12, 41],
              popupAnchor: [1, -34],
              shadowSize: [41, 41]
          });

          L.marker(co, { icon: redIcon }).addTo(map).bindPopup('IRISA');
      }

      L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
          maxZoom: 19,
          attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
      }).addTo(map);

      <?php
      $stmt = $db->prepare("SELECT * FROM _affiliation WHERE lat IS NOT NULL AND lon IS NOT NULL");
      if ($stmt->execute()) {
        $affiliations = $stmt->fetchAll();
      }

      foreach ($affiliations as $affiliation) { ?>
          var marker = L.marker([<?php echo $affiliation['lat'] . ',' . $affiliation['lon']; ?>])
              .bindPopup("<?php echo $affiliation['nom']; ?>");
          markers.addLayer(marker);
      <?php } ?>

      map.addLayer(markers);
  </script>
  <?php } elseif (empty($publications)) { ?>
  <p>
    <b>Aucun publication trouvée...</b>
  </p>
  <?php } else { ?>
  <h1 class='text-3xl font-bold mb-8'>Toutes les publications</h1>

  <table class="w-full">
    <thead>
      <tr>
        <th class='sticky top-0 px-6 py-3 border border-black bg-[#ddf]'>Type</th>
        <th class='sticky top-0 px-6 py-3 border border-black bg-[#ddf]'>Titre</th>
        <th class='sticky top-0 px-6 py-3 border border-black bg-[#ddf]'>Auteurs</th>
        <th class='sticky top-0 px-6 py-3 border border-black bg-[#ddf]'>Lien HAL</th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($publications as $publication) { ?>
      <?php
      // Connaitre les auteurs qui on travaillé sur cette publication
      $stmt = $db->prepare("SELECT aut_id FROM _publication_auteur WHERE pub_id = :pub_id");
      $stmt->bindParam(':pub_id', $publication['id']);
      if ($stmt->execute()) {
        $aut_ids = $stmt->fetchAll();
      }
      ?>
      <tr>
        <td class='p-1 border border-black'>
          <?php
          $types = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/../types.json'), true);
          echo $types[$publication['type']] ?? $publication['type'];
          ?>
        </td>

        <td class='p-1 border border-black' title="<?php echo htmlspecialchars($publication['titre']); ?>">
          <p class='line-clamp-2'>
            <?php echo $publication['titre'] ?>
          </p>
        </td>

        <td class='p-1 border border-black'>
          <ul>
            <?php
            foreach ($aut_ids as $aut_id) {
              $stmt = $db->prepare("SELECT nom, prénom FROM _auteur WHERE id = :id");
              $stmt->bindParam(':id', $aut_id['aut_id']);
              if ($stmt->execute()) {
                $identité = $stmt->fetchAll();
              }
              ?>
            <li>
              <!-- <a href='/profil?id=<?php echo $aut_id['aut_id'] ?>&retour=<?php echo $page ?>'> -->
                <?php echo $identité[0]['nom'] . ' ' . $identité[0]['prénom'] ?>
              <!-- </a> -->
            </li>
            <?php
            }
            ?>
          </ul>
        </td>

        <td class='underline p-1 border border-black text-nowrap text-blue-600'>
          <?php echo '<a href=' . $publication['url'] . ' target=_blank>' . $publication['url'] . '</a>' ?>
        </td>
      </tr>
      <?php
      }
      ?>
    </tbody>
  </table>
  <?php } ?>

  <?php
  // NOMBRE DE PAGES PRÉCEDENTES / SUIVANTES DIRECTEMENT ACCESSIBLES
  const P = 2;
  ?>

  <p class='text-center mt-[2rem]'>
    <?php if ($page > P) { ?>
      <a class='text-xl text-blue-600' href='http://localhost?page=0'>
        0
      </a>
      &nbsp;
      &nbsp;
      <a class='text-xl text-blue-600' href='http://localhost?page=<?php echo $page-1; ?>'>
        <
      </a>
      &nbsp;
      &nbsp;
      ...
      &nbsp;
    <?php }

    $total = ceil(($db->query("SELECT COUNT(*) FROM _publication")->fetchColumn() - 1) / LIGNES);

    $debut = max(0, $page - P);
    $fin = min($total, $page + P);

    for ($i = $debut; $i <= $fin; $i++) {
      if ($i == $page) { ?>
        <b class='text-xl'>
          <?php echo $i; ?>
        </b>
      <?php } else { ?>
        <a class='text-xl text-blue-600' href='http://localhost?page=<?php echo $i; ?>'>
          <?php echo $i; ?>
        </a>
      <?php } ?>
    <?php } ?>

    <?php if ($page < $total-P) { ?>
      &nbsp;
      ...
      &nbsp;
      &nbsp;
      <a class='text-xl text-blue-600' href='http://localhost?page=<?php echo $page+1; ?>'>
        >
      </a>
      &nbsp;
      &nbsp;
      <a class='text-xl text-blue-600' href='http://localhost?page=<?php echo $total; ?>'>
        <?php echo $total; ?>
      </a>
    <?php } ?>
  </p>

</body>

</html>
