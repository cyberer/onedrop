<?php
/**
 * Created by PhpStorm.
 * User: soenke
 * Date: 22.11.14
 * Time: 14:45
 */
//phpinfo();
ini_set('error_reporting', E_ALL);
$appDir = __DIR__ . "/../app/";
define('DATA_DIR', __DIR__ . '/data/');
define('DB_DIR', $appDir . 'data/DB');
define('DOC_DIR', __DIR__);

require_once "{$appDir}PHPTAL/PHPTAL.php";
require_once "{$appDir}file.php";
require_once "{$appDir}db.php";
require_once "{$appDir}app.php";


$app = new App($_SERVER['REDIRECT_URL']);


echo $app->render();

