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
$user_data = Model::get_user(array('session_id' => $user['session_id']));

$SPEND_PARAMS = array(
	'amount',
	'created',
	'type',
	'category',
	'memo',
);

$params = array();
foreach($SPEND_PARAMS as $param) {
	if(isset($_POST[$param]) && $_POST[$param] != '') {
		$params[$param] = $_POST[$param];
	} else {
		$params[$param] = '';
	}
}

// TODO validation

$params['user_id'] = $user_data[0]['user_id'];
$result = Model::add_spending($params);

header("Content-Type: application/json; charset=utf-8");
echo json_encode($result);
