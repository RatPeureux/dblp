<?php
$csv_town_path = $_SERVER['DOCUMENT_ROOT'] . '/../files/worldcities.csv';
$towns = fgetcsv($csv_town_path, null, ',');

print_r($towns);

