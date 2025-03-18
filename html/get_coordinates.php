<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/dbconnection.php';

$stmt = $db->prepare("SELECT * FROM _affiliation WHERE lat IS NULL AND lon IS NULL");
if ($stmt->execute()) {
    $affiliations = $stmt->fetchAll();
}

for ($i = 0; $i < 1000; $i++) {
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
        $stmt->bindParam(":id", $affiliations[$i]['id']);
        $stmt->bindParam(":lat", $latitude);
        $stmt->bindParam(":lon", $longitude);
        $stmt->execute();

        echo $i . ' donc ' . $latitude . ';' . $lonitude . '<br>';
    } else {
        $stmt = $db->prepare("DELETE FROM _affiliation WHERE id = :id");
        $stmt->bindParam(":id", $affiliations[$i]['id']);
        $stmt->execute();
    }
}
