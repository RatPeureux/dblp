<!DOCTYPE html> <html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>R&L - Profil</title>

    <link rel="stylesheet" href="/../html/styles/style.css">

    <!-- Nous chargeons les fichiers CDN de Leaflet. Le CSS AVANT le JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="https://kit.fontawesome.com/fe61efeeb2.js" crossorigin="anonymous"></script>
</head>
<body> 

<main class='flex flex-col items-start gap-5'>
<?php
// Vérifier si le paramètre 'pid' est présent dans l'URL
if (isset($_GET['pid']) && preg_match('/^.+\/.+$/', $_GET['pid'])) {
  $pid = $_GET['pid'];
  // Récupérer les infos du profil
  $infos_profil = require $_SERVER['DOCUMENT_ROOT'] . '/../includes/get_infos_profil.php';
?>

  <h1 class='text-3xl font-bold'><?php echo $infos_profil['first_name'] . ' ' . $infos_profil['last_name'] ?></h1>

  <a href="http://localhost?page=<?php echo (isset($_GET['retour'])) ? $_GET['retour'] : 1 ?>" class="text-xl">Retour à la liste des publications</a>

  <h2 class='text-xl font-bold'>Affiliations</h2>

  <h2 class='text-xl font-bold'>Travaux</h2>
  <ul class='list-disc!'>
    <?php foreach($infos_profil['publications'] as $publication) { ?>
    <li class='ml-10'><a href="<?php echo $publication['url'] ?>"><?php echo $publication['title'] ?></a></li>
    <?php } ?>
  </ul>

  <h2 class='text-xl font-bold'>Carte</h2>
  <div id="map" class='border border-black w-[90%] self-center h-[500px]'></div>

<?php
} else { ?>
  <p> PID invalide ou manquant </p>
  <br>
  <a href='/' class='underline'>Accueil</a>
<?php
}
?>
</main>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="/scripts/carte.js"></script>

</body>
</html>

