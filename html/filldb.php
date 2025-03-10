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

// Obtenir la réponse de L'API
$data = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
$data = json_decode($data, true);

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
$db->query("DELETE FROM _auteur");
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

        $xml = getXmlFromUrl("https://dblp.org/pid/$pid.xml");
        if ($xml === false) {
            echo "Erreur lors du chargement de $pid.xml\n";
            die();
        }

        sleep(2);
    
        $pid = (string) $xml['pid'];
        $name = (string) $xml['name'];
        $mdate = (string) $xml->person['mdate'];
        
        preg_match('/^([\p{L}-]+(?:\s[\p{L}]\.)*)(?:\s+([\p{L}-]+(?:\s[\p{L}-]+)*))?(?:\s+\d+)?$/u', $name, $matches);

        if (!empty($matches[2])) {
            // Cas où il y a un nom de famille
            $firstn = $matches[1]; // Gère les prénoms avec initiales et traits d’union
            $lastn = $matches[2];  // Nom de famille
        } elseif (!empty($matches[1])) {
            // Cas où on a un seul mot, mais il peut être prénom ou nom
            $firstn = $matches[1];
            $lastn = "";  // Peut être complété si trouvé ailleurs (ex. autre colonne)
        } else {
            $firstn = $name;
            $lastn = "";
        }
        
        $db->query("INSERT INTO _auteur (pid, first_name, last_name, mdate) VALUES ('$pid', '$firstn', '$lastn', '$mdate') ON CONFLICT (pid) DO NOTHING");

    } else {

        foreach ($authors as $author) {

            $pid = $author['@pid'];

            $db->query("INSERT INTO _article_auteur (article_id, author_pid) VALUES ('$id', '$pid')");

            $xml = getXmlFromUrl("https://dblp.org/pid/$pid.xml");
            if ($xml === false) {
                echo "Erreur lors du chargement de $pid.xml\n";
                die();
            }

            sleep(2);
        
            $pid = (string) $xml['pid'];
            $name = (string) $xml['name'];
            $mdate = (string) $xml->person['mdate'];

            preg_match('/^([\p{L}-]+(?:\s[\p{L}]\.)*)(?:\s+([\p{L}-]+(?:\s[\p{L}-]+)*))?(?:\s+\d+)?$/u', $name, $matches);

            if (!empty($matches[2])) {
                // Cas où il y a un nom de famille
                $firstn = $matches[1]; // Gère les prénoms avec initiales et traits d’union
                $lastn = $matches[2];  // Nom de famille
            } elseif (!empty($matches[1])) {
                // Cas où on a un seul mot, mais il peut être prénom ou nom
                $firstn = $matches[1];
                $lastn = "";  // Peut être complété si trouvé ailleurs (ex. autre colonne)
            } else {
                $firstn = $name;
                $lastn = "";
            }

            $db->query("INSERT INTO _auteur (pid, first_name, last_name, mdate) VALUES ('$pid', '$firstn', '$lastn', '$mdate') ON CONFLICT (pid) DO NOTHING");

        }

    }
} ?>

</body>
</html>

<?php

function getXmlFromUrl($url) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0");

  $data = curl_exec($ch);
  $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  if ($http_code != 200) {
      return false;
  }

  return simplexml_load_string($data);
}
