<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/dbconnection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/functions.php';

$input_search = (isset($_GET['search']) && $_GET['search']) ? $_GET['search'] : '';
$limite = 10;

// Récupérer les articles et filtrer sur la rechercher
$stmt = $db->prepare("SELECT * FROM _article WHERE title LIKE :search LIMIT :limite");
if ($stmt->execute([':search' => '%' . $input_search . '%', ':limite' => $limite])) {
  $articles = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles</title>

    <link rel="stylesheet" href="/styles/style.css"> 
    
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="https://kit.fontawesome.com/fe61efeeb2.js" crossorigin="anonymous"></script>
</head>

<body>

<h1 class='text-3xl font-bold'>Toutes les publications</h1>

<form action="/" method="get" class='my-6'>
  <input class='border border-black' id="search" name="search" type="text">
  <input class='p-1 border border-black cursor-pointer' type="submit" value="Rechercher">
</form>

<!--- TABLE DES ARTICLES --->
<table class='text-center'>
  <thead>
    <tr>
      <th class='sticky top-0 px-6 py-3 border border-black bg-gray'>Type</th>
      <th class='sticky top-0 px-6 py-3 border border-black bg-gray'>Titre</th>
      <th class='sticky top-0 px-6 py-3 border border-black bg-gray'>Année</th>
      <th class='sticky top-0 px-6 py-3 border border-black bg-gray'>Auteurs</th>
      <th class='sticky top-0 px-6 py-3 border border-black bg-gray'>Lien DBLP</th>
      <th class='sticky top-0 px-6 py-3 border border-black bg-gray'>Lien DOI ou autre</th>
      <th class='sticky top-0 px-6 py-3 border border-black bg-gray'>Source</th>
      <th class='sticky top-0 px-6 py-3 border border-black bg-gray'>Pages</th>
    </tr>
  </thead>
  <tbody>
  <pre>
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
    
    <td>
      <ul class='text-left'>
<?php
  foreach($authors as $author) { ?>
    <li><?php echo $author['author_pid'] ?></li>
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

</body>
</html>

