<?php
require dirname(__FILE__)."/app/common/config.php";
require dirname(__FILE__)."/app/common/auth.php";

$auth = new Auth();
$auth->goToLoginPage();
exit;
