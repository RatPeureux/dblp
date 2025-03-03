<?php

$json = json_decode(file_get_contents('https://dblp.org/search/publ/api?q=laurent%20d%20orazio&h=10&format=json'), true);

$query = $json['result']['query'];
$hits = $json['result']['hits'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DBLP - Laurent d'Orazio</title>
</head>
<body>
    <h2>Parameters</h2>

    <h3>query : <?php echo $query ?></h3>
    <h3><?php echo $hits['@sent'] ?> requested results</h3>

    <h2>Results</h2>

    <ul>
        <?php foreach ($hits['hit'] as $hit) { ?>
            <li>
                <b><?php echo $hit['info']['title'] ?></b>
                <?php foreach ($hit['info']['authors']['author'] as $author) {
                    $json = json_decode(file_get_contents('https://dblp.org/search/publ/api?q=' . urlencode($author['text'] . '&format=json')), true); ?>
                    <p><?php echo $author['text'] ?></p>
                <?php } ?>
                <p><?php echo $hit['info']['venue'] ?></p>
                <p><?php echo $hit['info']['year'] ?></p>
            </li>
        <?php } ?>
    </ul>
</body>
</html>

<pre>
    <?php print_r($json); ?>https://dblp.org/search/publ/api?q=laurent%20d%20orazio&format=json
</pre>