<?php
require dirname(__FILE__)."/service/common/config.php";
require dirname(__FILE__)."/service/common/auth.php";
require dirname(__FILE__)."/service/common/view.php";
require dirname(__FILE__)."/service/models/model.php";
require dirname(__FILE__)."/service/vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

$auth = new Auth();
if (!$auth->checkLogin()) {
	$layout = 'login';
	$view = new View();
	$view->render($layout);
	exit;
}

$twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $auth->info['oauth_token'], $auth->info['oauth_token_secret']);
$settings = $twitter->get('account/verify_credentials');
$user = array(
	'session_id' => $settings->id,
	'auth_type' => 'twitter',
	'name' => $settings->screen_name,
);

$model = new Model();
$isRegistration = Model::get_user(array('session_id' => $user['session_id']));

$spending = array();
if (0 < count($isRegistration)) {
	$spending = Model::get_spending(array('user_id' => $isRegistration[0]['user_id']));
} else {
	Model::add_user($user);
}


$layout = 'index';
$view = new View();
$view->setData('spending', $spending);
$view->render($layout);
