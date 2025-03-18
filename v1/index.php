<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/dbconnection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/functions.php';

const SIZE = 10;

if (isset($_GET["page"])) {
  $page = $_GET["page"];
} else {
  $page = 1;
}

$input_search = (isset($_GET['search']) && $_GET['search']) ? $_GET['search'] : '';

// Récupérer les articles et filtrer sur la rechercher
$stmt = $db->prepare("SELECT * FROM _article WHERE title LIKE :search OFFSET :offset LIMIT :limite");
if ($stmt->execute([':search' => '%' . $input_search . '%', ':offset' => ($page - 1) * SIZE, ':limite' => SIZE])) {
  $articles = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>R&L - Toutes les publications</title>

  <link rel="stylesheet" href="/../html/styles/style.css">

  <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
  <script src="https://kit.fontawesome.com/fe61efeeb2.js" crossorigin="anonymous"></script>
</head>

<body class='p-[2rem]'>

  <h1 class='text-3xl font-bold'>Toutes les publications</h1>

  <form action="/" method="get" class='my-6'>
    <input class='border border-black' id="search" name="search" type="text">
    <input class='p-1 border border-black cursor-pointer' type="submit" value="Rechercher par titre">
  </form>

  <?php
  if (isset($_GET['search'])) {
    echo '<a href="http://localhost" class="text-xl">Retour à la liste complète</a>';
  }
  ?>

  <!--- TABLE DES ARTICLES --->
  <?php if (empty($articles)) { ?>
  <p><b>Aucun publication trouvée pour la recherche "
      <?php echo htmlspecialchars($input_search); ?>"
    </b></p>
  <?php } else { ?>
  <table class='text-center'>
    <thead>
      <tr>
        <th class='sticky top-0 px-6 py-3 border border-black bg-gray'>Type</th>
        <th class='sticky top-0 px-6 py-3 border border-black bg-gray'>Titre</th>
        <th class='sticky top-0 px-6 py-3 border border-black bg-gray'>Année</th>
        <th class='sticky top-0 px-6 py-3 border border-black bg-gray'>Auteurs</th>
        <th class='sticky top-0 px-6 py-3 border border-black bg-gray'>Lien DBLP</th>
        <th class='sticky top-0 px-6 py-3 border border-black bg-gray' title="Digital Object on Internet">Lien DOI ou
          autre</th>
        <th class='sticky top-0 px-6 py-3 border border-black bg-gray'>Source</th>
        <th class='sticky top-0 px-6 py-3 border border-black bg-gray'>Pages</th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($articles as $article) { ?>
      <?php
      // Connaitre les auteurs qui on travaillé sur cette publication
      $stmt = $db->prepare("SELECT author_pid FROM _article_auteur WHERE article_id = :article_id");
      $stmt->bindParam(':article_id', $article['iddblp']);
      if ($stmt->execute()) {
        $authors = $stmt->fetchAll();
      }
      ?>
      <tr>
        <td class='text-xl' title="<?php echo type_shorten($article['type']) ?>">
          <?php echo type_to_logo($article['type']) ?>
        </td>
        <td title="<?php echo $article['title'] ?>">
          <p class='line-clamp-2'>
            <?php echo $article['title'] ?>
          </p>
        </td>
        <td>
          <?php echo $article['year'] ?>
        </td>

        <td class='text-nowrap'>
          <ul class='text-left'>
            <?php
            foreach ($authors as $author) {
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
            <li><a href="/profil?pid=<?php echo $author['author_pid'] ?>&retour=<?php echo $page ?>">
                <?php echo $author_name ?>
              </a></li>
            <?php
            }
            ?>
          </ul>
        </td>

        <td>
          <?php echo '<a href=' . $article['url'] . ' target=_blank>' . $article['url'] . '</a>' ?>
        </td>
        <td>
          <?php if (isset($article['ee']) && $article['ee']) {
            echo '<a href=' . $article['ee'] . ' target=_blank>' . $article['ee'] . '</a>';
          } else {
            echo 'Non spécifié';
          } ?>
        </td>
        <td>
          <?php echo (isset($article['venue']) && $article['venue']) ? $article['venue'] : 'Non spécifiée' ?>
        </td>
        <td>
          <?php echo (isset($article['pages']) && $article['pages']) ? $article['pages'] : 'Non spécifiées' ?>
        </td>
      </tr>
      <?php
      }
      ?>
    </tbody>
  </table>
  <?php } ?>

  <p class="text-center mt-[2rem]">
    <?php if ($page > 1) { ?>
    <a class="text-xl" href="http://localhost?page=1">
      <<< /a>
        &nbsp;
        &nbsp;
        <a class="text-xl" href="http://localhost?page=<?php echo $page - 1; ?>">
          << /a>
            &nbsp;
            &nbsp;
            <?php }

    $total_pages = ceil($db->query("SELECT COUNT(*) FROM _article WHERE title LIKE '%$input_search%'")->fetchColumn() / SIZE);
    $start = max(1, $page - 2);
    $end = min($total_pages, $page + 2);

    for ($i = $start; $i <= $end; $i++) {

      if ($i == $page) { ?>
            <strong class="text-xl">
              <?php echo $i; ?>
            </strong>
            <?php } else { ?>
            <a class="text-xl" href="http://localhost?page=<?php echo $i; ?>">
              <?php echo $i; ?>
            </a>
            <?php } ?>

            &nbsp;
            &nbsp;
            <?php }

    if ($page < $total_pages) { ?>
            <a class="text-xl" href="http://localhost?page=<?php echo $page + 1; ?>">></a>
            &nbsp;
            &nbsp;
            <a class="text-xl" href="http://localhost?page=<?php echo $total_pages; ?>">>></a>
            <?php } ?>
  </p>

</body>

</html>