<?php

// アプリケーション設定
define('BASE_URL', 'http://sudo.mkdir.me/budget');
define('CONSUMER_KEY', 'RBH0TfRMdPY5S8duq6DlYg');
define('CONSUMER_SECRET', '3KlgWbSpcbkPzvWrSQPUJJHK4OWOPEmvViA9Het4');
define('CALLBACK_URL', BASE_URL.'/callback.php');

// DATABASEの設定
define('DATABASE_HOST', 'loaclhost');
define('DATABASE_NAME', 'budget');
define('DATABASE_USER', 'budget');
define('DATABASE_PASSWARD', 'budget');

// pdo
define('PDO_DNS', 'mysql:dbname=' . DATABASE_NAME .';host=' . DATABASE_HOST);

