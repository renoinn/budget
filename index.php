<?php
require dirname(__FILE__)."/app/common/config.php";
require dirname(__FILE__)."/app/common/auth.php";
require dirname(__FILE__)."/app/common/view.php";
require dirname(__FILE__)."/app/models/model.php";
require dirname(__FILE__)."/app/vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

$auth = new Auth();
if (!$auth->checkLogin()) {
	$layout = 'login';
	$view = new View();
	$view->render($layout);
	exit;
}

// 取得したアクセストークンでインスタンス作り直し
$twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $auth->info['oauth_token'], $auth->info['oauth_token_secret']);
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


$layout = 'index';
$view = new View();
//$view->setData('bookmarks', $bookmarks);
$view->render($layout);
