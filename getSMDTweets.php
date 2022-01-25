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

    $connection = new TwitterOAuth($CK, $CKS, $AT, $ATS);   //TwitterAPI接続のためのインスタンス生成

    $result = $connection->get('search/tweets', array("q" => "(#AITMELTDOWN)", "count" => 150))->statuses;  //#AITMELTDOWNタグのついたツイートを150件取得
    $tweets = json_decode(json_encode($result), true);  //tweetを一度jsonにする
    $windowTextHTML = ''; //吹き出しに書くHTML用の変数

    ?>
    <div id="map"></div> <!-- このタグの場所でマップが表示される -->
    <script>
        var latStr = []; //緯度情報を入れる配列
        var lngStr = []; //経度情報を入れる配列
        var infoWindow = []; //吹き出し情報を入れる配列
        var windowText = []; //吹き出しに書くHTMLの情報を入れる配列
    </script>
    <?php
    foreach ($tweets as $value) {
        if($value['place'] != null){    //場所情報が書かれていればマップに表示
            $windowTextHTML .= '<h2>'.$value["text"].'<br>"';   //h2タグでツイート本文を表示
            ?>
            <script>
                latStr.push(<?php print $value['place']['bounding_box']['coordinates']['0']['0']['1']?>);   //ツイートした緯度を配列に追加
                lngStr.push(<?php print $value['place']['bounding_box']['coordinates']['0']['0']['0']?>);   //ツイートした経度を配列に追加
            </script>
            <?php
            if(isset($value['entities']['media'])){ //画像が添付されていれば画像も表示
                //print_r($value['entities']['media']); //json確認用
                foreach($value['entities']['media'] as $media){ //複数画像がある場合もあるのでそれぞれforを回す
                    $windowTextHTML .= '<img style="width: 200px" src="'.$media["media_url"].'">'; //imgタグで画像を表示
                }
            }
        }
        ?>
        <script>
            windowText.push(<?php print json_encode($windowTextHTML); ?>); //吹き出しに表示するHTMLをまとめて配列に追加
        </script>
        <?php
        $windowTextHTML = '';   //HTMLをリセットして次のツイートに回す
    }
    ?>
</body>
</html>