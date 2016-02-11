<?php
require dirname(__FILE__)."/service/common/config.php";
require dirname(__FILE__)."/service/common/auth.php";
require dirname(__FILE__)."/service/common/view.php";
require dirname(__FILE__)."/service/models/model.php";

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
$user_data = $model->get_user(array('session_id' => $user['session_id']));

$spending = Model::get_spending(array('user_id' => $user_data[0]['user_id']));

header("Content-Type: application/json; charset=utf-8");
echo json_encode($spending);
