<?php
require('./vendor/autoload.php');

use Abraham\TwitterOAuth\TwitterOAuth;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

# APIの秘密鍵
$CK = $_ENV["CK"]; # コンシューマーキー
$CKS = $_ENV["CKS"]; # コンシューマーシークレット
$AT = $_ENV["AT"]; # アクセストークン
$ATS = $_ENV["ATS"]; # アクセストークンシークレット

$connection = new TwitterOAuth($CK, $CKS, $AT, $ATS);

$result = $connection->get('search/tweets', array("q" => "(#SHIBUYAMELTDOWN)"));
print_r($result);
?>