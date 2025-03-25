<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/dbconnection.php';

echo "VIDAGE DE LA BASE DE DONNÉES EN COURS...<br><br>";

$db->query("DELETE FROM _affiliation");
$db->query("DELETE FROM _auteur");
$db->query("DELETE FROM _publication");
$db->query("DELETE FROM _publication_affiliation");
$db->query("DELETE FROM _publication_auteur");

echo "SUPPRESSION TERMINÉE";