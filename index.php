<?php
require "app/common/config.php";
require "app/common/login.php";
require "app/common/view.php";
require "app/model.php";
require "app/vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

$layout = 'index';
$login = new Login();
if (!$login->checkLogin()) {
	$layout = 'login';
} else {
	// 取得したアクセストークンでインスタンス作り直し
	$twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $login->info['oauth_token'], $login->info['oauth_token_secret']);
	$settings = $twitter->get('account/verify_credentials');
	$user = array(
		'session_id' => $settings->id,
		'auth_type' => 'twitter',
		'name' => $settings->screen_name,
	);

	$model = new Model();
	$isRegistration = $model->get_user(array('session_id' => $user['session_id']));

	$bookmarks = array();
	if (0 < count($isRegistration)) {
		//$bookmarks = $model->get_site(array('user_id' => $isRegistration[0]['user_id']));
	} else {
		$model->add_user($user);
	}
}

$view = new View();
//$view->setData('bookmarks', $bookmarks);
$view->render($layout);
