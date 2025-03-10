<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/dbconnection.php';

$stmt = $db->prepare("SELECT * FROM _article");
if ($stmt->execute()) {
  $articles = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles</title>

    <script src="https://kit.fontawesome.com/fe61efeeb2.js" crossorigin="anonymous"></script>

</head>

<body>

<table>
  <thead>
    <tr>
      <th>Type</th>
      <th>Titre</th>
      <th>Année</th>
      <th>Lien DBLP</th>
      <th>Lien DOI ou autre</th>
      <th>Source</th>
      <th>Pages</th>
    </tr>
  </thead>
  <tbody>
<?php
foreach($articles as $article) { ?>
  <tr>
    <td><?php echo $article['type'] ?></td>
    <td><?php echo $article['title'] ?></td>
    <td><?php echo $article['year'] ?></td>
    <td><?php echo '<a href=' . $article['url'] . ' target=_blank>' . $article['url'] . '</a>' ?></td>
    <td><?php echo '<a href=' . $article['ee'] . ' target=_blank>' . $article['ee'] . '</a>' ?></td>
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

