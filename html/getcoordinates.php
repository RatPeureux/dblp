<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/dbconnection.php';

$limit = 100;
$offset = 0;

do {
    $stmt = $db->prepare("SELECT * FROM _affiliation a JOIN _publication_affiliation pa ON a.id = pa.aff_id JOIN _publication p ON pa.pub_id = p.id WHERE a.lat IS NULL AND a.lon IS NULL ORDER BY p.annee DESC LIMIT :limit OFFSET :offset");
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $affiliations = $stmt->fetchAll();

    if (count($affiliations) > 0) {
        // Process the batch of records
        for ($i = 0; $i < count($affiliations); $i++) {
            // L'adresse à géocoder
            $address = $affiliations[$i]['nom']; // Remplace par l'adresse que tu veux géocoder

            // Encoder l'adresse pour l'URL
            $address = urlencode($address);

            // L'URL de l'API Nominatim
            $url = "https://nominatim.openstreetmap.org/search?q=$address&format=json";

            // Créer un contexte avec un User-Agent personnalisé
            $options = [
                "http" => [
                    "header" => "User-Agent: MyGeocodingApp/1.0 (contact@monemail.com)"
                ]
            ];
            $context = stream_context_create($options);

            // Envoi de la requête HTTP avec le contexte
            $response = file_get_contents($url, false, $context);

            // Décoder la réponse JSON
            $data = json_decode($response);

            // Vérifier s'il y a des résultats
            if (isset($data[0])) {
                // Récupérer la latitude et la lonitude
                $latitude = $data[0]->lat;
                $longitude = $data[0]->lon;

                $stmt = $db->prepare("UPDATE _affiliation SET lat = :lat, lon = :lon WHERE id = :id");
                $stmt->bindParam(":id", $affiliations[$i]['aff_id']);
                $stmt->bindParam(":lat", $latitude);
                $stmt->bindParam(":lon", $longitude);
                $stmt->execute();

                echo "{$i} donc {$latitude};{$longitude}<br>";
            } else {
                $stmt = $db->prepare("DELETE FROM _affiliation WHERE id = :id");
                $stmt->bindParam(":id", $affiliations[$i]['aff_id']);
                $stmt->execute();
            }
        }
    }

    $offset += $limit;
} while (count($affiliations) > 0);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>R&L - ! Récupération des coordonnées des affiliations !</title>
</head>

<body>
    <?php
    echo "RÉCUPÉRATION TERMINÉE";
    ?>
</body>
</html>