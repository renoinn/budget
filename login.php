<?php
require dirname(__FILE__)."/service/common/config.php";
require dirname(__FILE__)."/service/common/auth.php";

$auth = new Auth();
$auth->goToLoginPage();
exit;
