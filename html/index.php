<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/dbconnection.php';

$data = json_decode("https://dblp.org/search/publ/api?q=test&h=10&format=json", true);
?>

<pre>
  <?php print_r($data); ?>
</pre>

<?php
$hits = $data['result']['hits']['hit'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertions</title>
</head>
<body>
  <?php foreach ($hits as $hit) {
      $id = $hit['@id'];
      $type = $hit['info']['type'];
      $doi = $hit['info']['doi'];
      $title = $hit['info']['title'];
      $venue = $hit['info']['venue'];
      $year = $hit['info']['year'];
      $pages = $hit['info']['pages'];
      $ee = $hit['info']['ee'];
      $url = $hit['info']['url'];
      $db->query("INSERT INTO _article (id, type, doi, title, venue, year, pages, ee, url) VALUES ('$id', '$type', '$doi', '$title', '$venue', '$year', '$pages', '$ee', '$url')");
      foreach ($hit['authors'] as $author) {
          $pid = $author['@pid'];
          $db->query("INSERT INTO _article_auteur (article_id, author_id) VALUES ('$id', '$pid')");
      }
  } ?>
</body>
</html>