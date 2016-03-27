<?php
require dirname(__FILE__)."/service/common/config.php";
require dirname(__FILE__)."/service/common/util.php";
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

$query = array('user_id' => $user_data[0]['user_id']);
$spending = Model::get_spending($query, get_period_one_month());
$fixed_spending = Model::get_fixed_spending($query, get_period_one_month());
$income = Model::get_income($query, get_period_one_month());

$layout = 'sumup';
$view = new View();
$view->setData('spending', $spending);
$view->setData('fixed_spending', $fixed_spending);
$view->setData('income', $income);
$view->render($layout);
