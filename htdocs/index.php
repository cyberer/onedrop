<?php
/**
 * Created by PhpStorm.
 * User: soenke
 * Date: 22.11.14
 * Time: 14:45
 */
ini_set('error_reporting', E_ALL);
$appDir = __DIR__ . "/../app/";

require_once "{$appDir}PHPTAL/PHPTAL.php";
require_once "{$appDir}app.php";


$app = new App($_SERVER['REDIRECT_URL']);


echo $app->render();

//phpinfo();