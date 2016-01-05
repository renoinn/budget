<?php
require dirname(__FILE__)."/app/common/config.php";
require dirname(__FILE__)."/app/common/auth.php";
require dirname(__FILE__)."/app/vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

session_start();
$request_token_secret = $_SESSION['request_token_secret'];

// アクセストークン取得
$twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_GET['oauth_token'], $request_token_secret);
$token = $twitter->oauth('oauth/access_token', array('oauth_verifier' => $_GET['oauth_verifier']));

// アクセストークンをセッションに保存
$auth = new Auth();
$auth->setLoginInfo($token);

header('Location: '.BASE_URL.'/');
exit;
