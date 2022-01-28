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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/destyle.css@1.0.15/destyle.css"/> <!-- 標準のスタイルをリセット -->
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet"> 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Kosugi+Maru&display=swap" rel="stylesheet"> 
    <script src="https://maps.googleapis.com/maps/api/js?key=<?php print $MAPCK ?>&callback=initMap" async defer></script>
    <title>AITMELTDOWNピンたて</title>
</head>
<body>
    <header><h1 id="title">AITMELTDOWN</h1></header>

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
            $windowTextHTML .= '<div style="display: flex;" id=userInfo>';
            $windowTextHTML .=  '<div id="icon"><a href="https://twitter.com/'.$value["user"]["screen_name"].'"><img src="'.$value["user"]["profile_image_url"].'"></a></div>';
            $windowTextHTML .=  '<div id="names">';
            $windowTextHTML .=      '<h2 id="name">'.$value["user"]["name"].'</h2>';
            $windowTextHTML .=      '<div id="screenName">@'.$value["user"]["screen_name"].'</div>';
            $windowTextHTML .=  '</div>';
            $windowTextHTML .= '</div>';
            $windowTextHTML .= '<div id="text">'.$value["text"].'</div><br>';   //ツイート本文を表示
            $windowTextHTML .= '<u><a id="link" href="https://twitter.com/'.$value["user"]["screen_name"].'/status/'.$value["id"].'" target="_blank" rel="noopener noreferrer">https://twitter.com/'.$value["user"]["screen_name"].'/status/'.$value["id"].'</a></u><br>';
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
        $windowTextHTML = '';   //吹き出しの内容をリセットして次のツイートに回す
    }
    ?>
    <div id="description">
        <p>AITMELTDOWNは#AITMELTDOWNというハッシュタグと位置情報がついたツイートを自動で取得して位置をマップ上に表示するアプリケーションです。</p><br>
        <h2 id="sub"> 使い方 </h2>
        <ol>
            <li><u><a href="https://twitter.com">Twitter</a></u>にて#AITMELTDOWNと位置情報をつけて投稿</li>
            <li>このページでピンが立てられていることを確認（ピンをクリックでツイート情報を確認することもできます）</li>
        </ol>
    </div>
    <footer>
        <ul id="footer-nav">
            <li><a href="#"><i class="fab fa-youtube"></i></a></li>
            <li><a href="#"><i class="fab fa-twitter"></i></a></li>
            <li><a href="https://github.com/usabilityTeam9/SMDGetTweet"><i class="fab fa-github"></i></a></li>
        </ul>
        <p><small>&copy; 2022 Usability Team9</small></p>
    </footer>
</body>
</html>