<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/dbconnection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/functions.php';

// NOMBRE DE PUBLICATIONS PAR PAGE
const SIZE = 50;

if (isset($_GET["page"])) {
  $page = $_GET["page"];
} else {
  $page = 1;
}

$input_search = (isset($_GET['search']) && $_GET['search']) ? $_GET['search'] : '';

// Récupérer les publications et filtrer sur la rechercher
$stmt = $db->prepare("SELECT * FROM _publication WHERE titre LIKE :search OFFSET :offset LIMIT :limite");
if ($stmt->execute([':search' => '%' . $input_search . '%', ':offset' => ($page - 1) * SIZE, ':limite' => SIZE])) {
  $publications = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>R&L - Publications</title>

  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class='p-[2rem] mx-auto w-[80%]'>

  <h1 class='text-3xl font-bold'>Toutes les publications</h1>

  <form action="/" method="get" class='my-6'>
    <input class='border border-black' id="search" name="search" type="text">
    <input class='p-1 border border-black cursor-pointer bg-[#ddf]' type="submit" value="Rechercher par titre">
  </form>

  <?php
  if (isset($_GET['search'])) {
    echo '<a href="http://localhost" class="text-xl">Retour à la liste complète</a>';
  }
  ?>

  <!--- TABLEAU DES PUBLICATIONS --->
  <?php if (empty($publications)) { ?>
  <p>
    <b>Aucun publication trouvée pour la recherche "<?php echo htmlspecialchars($input_search); ?>"
    </b>
  </p>
  <?php } else { ?>
  <table class="w-full">
    <thead class="border border-black">
      <tr>
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
        <td class='p-1 border border-black" title="<?php echo htmlspecialchars($publication['titre']); ?>'>
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

  <p class='text-center mt-[2rem]'>
    <?php if ($page > 1) { ?>
      <a class='text-xl text-blue-600' href='http://localhost?page=1'>
        0
      </a>
      &nbsp;
      &nbsp;
      <a class='text-xl text-blue-600' href='http://localhost?page=<?php echo $page - 1; ?>'>
        <
      </a>
      &nbsp;
      &nbsp;
      ...
      &nbsp;
    <?php }

    $total = ceil($db->query("SELECT COUNT(*) FROM _publication WHERE titre LIKE '%$input_search%'")->fetchColumn() / SIZE);

    $debut = max(1, $page - 2);
    $fin = min($total, $page + 2);

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


    <?php if ($page < $total) { ?>
      &nbsp;
      ...
      &nbsp;
      &nbsp;
      <a class='text-xl text-blue-600' href='http://localhost?page=<?php echo $page + 1; ?>'>
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
