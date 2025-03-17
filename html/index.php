<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <!-- Nous chargeons les fichiers CDN de Leaflet. Le CSS AVANT le JS -->
 <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>

        <title>Carte</title>
    </head>
    <body>
        <div id="map">
      <!-- Ici s'affichera la carte -->
  </div>

        <!-- Fichiers Javascript -->
 <!-- Make sure you put this AFTER Leaflet's CSS -->
 <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>

<script>
var map = L.map('map').setView([51.505, -0.09], 13);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
maxZoom: 19,
  attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

var marker = L.marker([51.5, -0.09]).addTo(map);
marker.bindPopup("<b>Hello world!</b><br>I am a popup.").openPopup();

<<<<<<< HEAD
</script>
    </body>
=======
<!--- TABLE DES ARTICLES --->
<?php if (empty($articles)) { ?>
  <p><b>Aucun publication trouvée pour la recherche "<?php echo htmlspecialchars($input_search); ?>"</b></p>
<?php } else { ?>
<table class='text-center'>
  <thead>
    <tr>
      <th class='sticky top-0 px-6 py-3 border border-black bg-gray'>Type</th>
      <th class='sticky top-0 px-6 py-3 border border-black bg-gray'>Titre</th>
      <th class='sticky top-0 px-6 py-3 border border-black bg-gray'>Année</th>
      <th class='sticky top-0 px-6 py-3 border border-black bg-gray'>Auteurs</th>
      <th class='sticky top-0 px-6 py-3 border border-black bg-gray'>Lien DBLP</th>
      <th class='sticky top-0 px-6 py-3 border border-black bg-gray' title="Digital Object on Internet">Lien DOI ou autre</th>
      <th class='sticky top-0 px-6 py-3 border border-black bg-gray'>Source</th>
      <th class='sticky top-0 px-6 py-3 border border-black bg-gray'>Pages</th>
    </tr>
  </thead>
  <tbody>
<?php
foreach($articles as $article) { ?>
<?php
  // Connaitre les auteurs qui on travaillé sur cette publication
  $stmt = $db->prepare("SELECT author_pid FROM _article_auteur WHERE article_id = :article_id");
  $stmt->bindParam(':article_id', $article['iddblp']);
  if ($stmt->execute()) {
  $authors = $stmt->fetchAll();
  }
?>
  <tr>
  <td class='text-xl' title="<?php echo type_shorten($article['type']) ?>"><?php echo type_to_logo($article['type']) ?></td>
  <td title="<?php echo $article['title'] ?>">
    <p class='line-clamp-2'>
    <?php echo $article['title']?>
    </p>
  </td>
  <td><?php echo $article['year'] ?></td>
  
  <td class='text-nowrap'>
    <ul class='text-left'>
<?php
  foreach($authors as $author) {
  $stmt = $db->prepare("SELECT first_name, last_name FROM _auteur WHERE pid = :pid");
  $stmt->bindParam(':pid', $author['author_pid']);
  if ($stmt->execute()) {
    $author_name = $stmt->fetchAll();
  }

  if (empty($author_name)) {
    $author_name = 'Inconnu';
  } else {
    $author_name = $author_name[0]['first_name'] . ' ' . $author_name[0]['last_name'];
  }
?>
    <li><a href="/profil?pid=<?php echo $author['author_pid'] ?>"><?php echo $author_name ?></a></li>
<?php
  }
?>
    </ul>
  </td>

  <td><?php echo '<a href=' . $article['url'] . ' target=_blank>' . $article['url'] . '</a>'?></td>
  <td><?php if(isset($article['ee']) && $article['ee']) { echo '<a href=' . $article['ee'] . ' target=_blank>' . $article['ee'] . '</a>'; } else { echo 'Non spécifié'; } ?></td>
  <td><?php echo (isset($article['venue']) && $article['venue']) ? $article['venue'] : 'Non spécifiée' ?></td>
  <td><?php echo (isset($article['pages']) && $article['pages']) ? $article['pages'] : 'Non spécifiées' ?></td>
  </tr>
<?php
  }
?>
  </tbody>
</table>
<?php } ?>

<p class="text-center mt-[2rem]">
  <?php if ($page > 1) { ?>
    <a class="text-xl" href="http://localhost?page=1"><<&nbsp;&nbsp;</a>
    <a class="text-xl" href="http://localhost?page=<?php echo $page-1;?>"><&nbsp;</a> 
  <?php } ?>
  &nbsp;
  <?php
  $total_pages = ceil($db->query("SELECT COUNT(*) FROM _article WHERE title LIKE '%$input_search%'")->fetchColumn() / SIZE);
  $start = max(1, $page - 2);
  $end = min($total_pages, $page + 2);

  for ($i = $start; $i <= $end; $i++) { 
  if ($i == $page) { ?>
    <strong class="text-xl"><?php echo $i;?></strong>
  <?php } else { ?>
    <a class="text-xl" href="http://localhost?page=<?php echo $i;?>"><?php echo $i;?></a>
  <?php } ?>
  &nbsp;
  <?php } 
  if ($page < $total_pages) { ?>
  <a class="text-xl" href="http://localhost?page=<?php echo $page+1;?>">&nbsp;></a>
  <a class="text-xl" href="http://localhost?page=<?php echo $total_pages;?>">&nbsp;&nbsp;>></a>
  <?php } ?>
</p>

</body>
>>>>>>> 3f9f325 (merge)
</html>
