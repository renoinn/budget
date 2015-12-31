<?php
require "common/config.php";
require "vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

class Login {

	public $info = null;

	public function checkLogin() {
		$this->info = $this->getLoginInfo();
		if(!$this->info) {
			return false;
		}
		return true;
	}

	public function goToLoginPage() {
		$twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);

		// リクエストトークン取得
		$token = $twitter->oauth('oauth/request_token', array('oauth_callback' => CALLBACK_URL));
		if(!isset($token['oauth_token'])){
			echo "エラー発生";
			exit;
		}
		$request_token = $token['oauth_token'];

		session_start();
		$_SESSION['request_token_secret'] = $token['oauth_token_secret'];

		// ログインURLを取得してリダイレクト
		$url = $twitter->url("oauth/authenticate", array("oauth_token" => $request_token));
		header('Location: '.$url);
		exit;
	}

	public function setLoginInfo($info) {
		session_start();
		$_SESSION['screen_name'] = $info['screen_name'];
		$_SESSION['oauth_token'] = $info['oauth_token'];
		$_SESSION['oauth_token_secret'] = $info['oauth_token_secret'];
	}

	public function getLoginInfo() {
		session_start();

		if (!isset($_SESSION['screen_name']))
		{
			return false;
		}
		$info['screen_name'] = $_SESSION['screen_name'];
		$info['oauth_token'] = $_SESSION['oauth_token'];
		$info['oauth_token_secret'] = $_SESSION['oauth_token_secret'];

		return $info;
	}
}
