<?php
const BLOCS = 10;
const ROWS = 100;

require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/dbconnection.php';

function requete($url)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "cache-control: no-cache"
        ],
    ));

    // Obtenir la réponse de l'API
    $data = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        // Afficher l'erreur et retourner null
        print_r($err);
    }

    // Décoder la réponse JSON
    $data = json_decode($data, true);

    // Vérifier si la réponse JSON est valide et contient les données attendues
    if (json_last_error() === JSON_ERROR_NONE && isset($data['response']['docs'])) {
        return $data['response']['docs'];
    } else {
        // Afficher un message d'erreur si la réponse JSON est invalide
        print_r("Erreur de décodage JSON ou structure inattendue");
        return null;
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>R&L - ! Insertions en BDD !</title>
</head>

<body>

    <?php

    $db->query("DELETE FROM _affiliation");
    $db->query("DELETE FROM _auteur");
    $db->query("DELETE FROM _auteur_affiliation");
    $db->query("DELETE FROM _auteur_publication");
    $db->query("DELETE FROM _publication");

    for ($i = 0; $i < BLOCS; $i += ROWS) {
        foreach (requete("https://api.archives-ouvertes.fr/search/IRISA/?fl=docid,title_s,uri_s,authIdHal_i,authLastName_s,authFirstName_s,instStructName_s,structName_s&sort=docid+asc&rows=" . ROWS . "&start=$i") as $elt) {

            $pub_id = $elt["docid"];
            $titre = is_array($elt["title_s"]) ? implode(", ", $elt["title_s"]) : $elt["title_s"];
            $url = is_array($elt["uri_s"]) ? implode(", ", $elt["uri_s"]) : $elt["uri_s"];

            try {
                $stmt = $db->prepare("INSERT INTO _publication (id, titre, url) VALUES (:id, :titre, :url)");
                $stmt->bindParam(':id', $pub_id);
                $stmt->bindParam(':titre', $titre);
                $stmt->bindParam(':url', $url);
                $stmt->execute();
            } catch (PDOException $e) {
                print_r($e->getMessage());
            }

            $auteurs = $elt["authIdHal_i"] ?? [];
            $noms = $elt["authLastName_s"] ?? [];
            $prenoms = $elt["authFirstName_s"] ?? [];

            for ($j = 0; $j < count($auteurs); $j++) {

                $aut_id = $auteurs[$j];
                $nom = $noms[$j];
                $prenom = $prenoms[$j];

                $stmt = $db->prepare("INSERT INTO _auteur (id, nom, prénom) VALUES (:id, :nom, :prenom) ON CONFLICT DO NOTHING");
                $stmt->bindParam(':id', $aut_id);
                $stmt->bindParam(':nom', $nom);
                $stmt->bindParam(':prenom', $prenom);
                $stmt->execute();

                $stmt = $db->prepare("INSERT INTO _auteur_publication (aut_id, pub_id) VALUES (:aut_id, :pub_id)");
                $stmt->bindParam(':aut_id', $aut_id);
                $stmt->bindParam(':pub_id', $pub_id);
                $stmt->execute();
            }

        } ?>

        <strong><?php echo "BLOC " . $i + 1; ?></strong>

        <br><br>

        <?php
        // sleep(1);
    } ?>

    DONE

</body>

</html>