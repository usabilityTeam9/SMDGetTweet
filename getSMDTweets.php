<?php
require('./vendor/autoload.php');

use Abraham\TwitterOAuth\TwitterOAuth;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// APIの秘密鍵
$CK = $_ENV["CK"]; // twitterコンシューマーキー
$CKS = $_ENV["CKS"]; //twitterコンシューマーシークレット
$AT = $_ENV["AT"]; // twitterアクセストークン
$ATS = $_ENV["ATS"]; // twitterアクセストークンシークレット
$MAPCK = $_ENV["MAPCK"]; // Google Mapコンシューマーキー

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script src="./index.js"></script>
    <link rel="stylesheet" type="text/css" href="./style.css" />
    <script src="https://maps.googleapis.com/maps/api/js?key=<?php print $MAPCK ?>&callback=initMap" async defer></script>
    <title>SHIBUYAMELTDOWNピンたて</title>
</head>
<body>
    <?php

    $connection = new TwitterOAuth($CK, $CKS, $AT, $ATS);

    $result = $connection->get('search/tweets', array("q" => "(#AITMELTDOWN)", "count" => 50))->statuses;
    $tweets = json_decode(json_encode($result), true);

    ?>
    <div id="map"></div>
    <script>
        var latStr = [];
        var lngStr = [];
    </script>
    <?php
    foreach ($tweets as $value) {
        if($value['place'] != null){
            print $value['text'].'<br>';
            ?>
            <script>
                latStr.push(<?php print $value['place']['bounding_box']['coordinates']['0']['0']['1']?>);
                lngStr.push(<?php print $value['place']['bounding_box']['coordinates']['0']['0']['0']?>);
            </script>
            <?php
            // print_r($value['place']['bounding_box']['coordinates']['0']['0']['1']).'<br>';
        }

    }
    ?>
</body>
</html>