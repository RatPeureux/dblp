<?php
$csv_town_path = $_SERVER['DOCUMENT_ROOT'] . '/../files/worldcities.csv';

$row = 1;

if (($handle = fopen($csv_town_path, "r")) !== FALSE) {
  while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    $num = count($data);
    echo "<p> $num fields in line $row: <br /></p>\n";
    $row++;
    for ($c=0; $c < $num; $c++) {
      echo $data[$c] . "<br />\n";
    }
  }
}

