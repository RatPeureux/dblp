<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>

    <link rel="stylesheet" href="/styles/style.css">

    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="https://kit.fontawesome.com/fe61efeeb2.js" crossorigin="anonymous"></script>
</head>
<body> 

<?php
// Vérifier si le paramètre 'pid' est présent dans l'URL
if (isset($_GET['pid']) && preg_match('/^.+\/.+$/', $_GET['pid'])) {
  $pid = $_GET['pid'];
  require_once $_SERVER['DOCUMENT_ROOT'] . '/../includes/dbconnection.php';


} else { ?>
  <p> PID invalide ou manquant </p>
  <br>
  <a href='/' class='underline'>Accueil</a>
<?php
}
?>

</body>
</html>

