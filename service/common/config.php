<?php

// アプリケーション設定
define('BASE_URL', 'http://sudo.mkdir.me/budget');
define('CONSUMER_KEY', '10dKUNZKQi8Ub7A9FgV0SsxNa');
define('CONSUMER_SECRET', '22wZ058CjUtZOO1H5eHnt1EObYc1oEVRT77mEl7zjWdcNcvSc7');
define('CALLBACK_URL', BASE_URL.'/callback.php');

// DATABASEの設定
define('DATABASE_HOST', 'localhost');
define('DATABASE_NAME', 'budget');
define('DATABASE_USER', 'budget');
define('DATABASE_PASSWARD', 'budget');

// pdo
define('PDO_DNS', 'mysql:dbname=' . DATABASE_NAME .';host=' . DATABASE_HOST);

