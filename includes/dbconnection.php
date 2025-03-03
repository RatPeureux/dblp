<?php
// Database configuration
$host	= getenv('DB_HOST');
$dbname = getenv('DB_NAME');
$user 	= getenv('DB_USER');
$pass 	= getenv('DB_PASSWORD');
$port 	= '5432'; // Default port for PostgreSQL

// Create a DSN (Data Source Name)
try {
    $db = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
    exit;
}

