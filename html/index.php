<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/dbconnection.php';

$stmt = $db->prepare("SELECT * FROM _ville");
if ($stmt->execute()) {
  $villes = $stmt->fetchAll();
  print_r($villes);
}


