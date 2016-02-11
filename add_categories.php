<?php
require dirname(__FILE__)."/service/common/config.php";
require dirname(__FILE__)."/service/common/auth.php";
require dirname(__FILE__)."/service/common/view.php";
require dirname(__FILE__)."/service/models/model.php";
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
$user_data = $model->get_user(array('session_id' => $user['session_id']));

$CATEGORIES_PARAMS = array(
	'type',
	'category',
);

$params = array();
foreach($CATEGORIES_PARAMS as $param) {
	if(isset($_POST[$param]) && $_POST[$param] != '') {
		$params[$param] = $_POST[$param];
	} else {
		$params[$param] = '';
	}
}

$categories = Model::get_categories(array(
	'user_id' => $user_data[0]['user_id'],
	'type' => $params['type'],
));

$categories_json = json_decode($categories[0]['categories_json'], true);
$categories_json[] = $params['category'];
$result = Model::add_categories(array(
	'user_id' => $user_data[0]['user_id'],
	'type' => $params['type'],
	'categories_json' => $categories_json,
));

header("Content-Type: application/json; charset=utf-8");
echo json_encode($result);
