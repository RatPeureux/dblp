<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/dbconnection.php';

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://dblp.org/search/publ/api?q=test&h=100&format=json",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_TIMEOUT => 10,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache"
  ),
));

# Obtenir la réponse de L'API
$data = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
$data = json_decode($data, true); //because of true, it's in an array

if ($err) {
  print_r($err);
}
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

<?php
$db->query("DELETE FROM _article");
$db->query("DELETE FROM _article_auteur");

foreach ($hits as $hit) {
  $id = $hit['@id'];
  $type = $hit['info']['type'];
  $doi = (isset($hit['info']['doi'])) ? (is_array($hit['info']['doi']) ? implode(", ", $hit['info']['doi']) : $hit['info']['doi']) : null;
  $title = $hit['info']['title'];
  $venue = (isset($hit['info']['venue'])) ? (is_array($hit['info']['venue']) ? implode(", ", $hit['info']['venue']) : $hit['info']['venue']) : null;
  $year = $hit['info']['year'];
  $pages = (isset($hit['info']['pages'])) ? (is_array($hit['info']['pages']) ? implode(", ", $hit['info']['pages']) : $hit['info']['pages']) : null;
  $ee = (isset($hit['info']['ee'])) ? (is_array($hit['info']['ee']) ? implode(", ", $hit['info']['ee']) : $hit['info']['ee']) : null;
  $url = $hit['info']['url']; 
  
  try {
    $db->query("INSERT INTO _article (iddblp, type, doi, title, venue, year, pages, ee, url) VALUES ('$id', '$type', '$doi', '$title', '$venue', '$year', '$pages', '$ee', '$url')");
  } catch (PDOException $e) {
    print_r($e->getMessage());
  }
  $authors = $hit['info']['authors']['author'];
  if (isset($authors['@pid'])) {
    $pid = $authors['@pid'];
    $db->query("INSERT INTO _article_auteur (article_id, author_pid) VALUES ('$id', '$pid')");
  } else {
    foreach ($authors as $author) {
      $pid = $author['@pid'];
      $db->query("INSERT INTO _article_auteur (article_id, author_pid) VALUES ('$id', '$pid')");
    }
  }
} ?>

</body>
</html>
