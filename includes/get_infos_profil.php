<?php
// POUR UTILISER CET INCLUDE, DÉFINIR LES VARIABLES SUIVANTES :
//  $pid : string, le pid de l'auteur récupéré sur dblp
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/dbconnection.php';

$infos_profil = -1;

$stmt = $db->prepare("SELECT * FROM _auteur WHERE pid = :pid");
$stmt->bindParam(":pid", $pid);
if ($stmt->execute()) {
  $infos_profil = $stmt->fetch();
}

$stmt = $db->prepare("
SELECT article_id, author_pid, title, url FROM _article_auteur
NATURAL JOIN _article
WHERE author_pid = :pid
");
$stmt->bindParam(":pid", $pid);
if ($stmt->execute()) {
  $publications = $stmt->fetchAll();
  $infos_profil['publications'] = [];
  foreach($publications as $p) {
    array_push($infos_profil['publications'], $p);
  }
}

return $infos_profil;

