<?php
header("Content-type: text/html; charset=utf-8");
error_reporting(E_ERROR);
require('system/core/App.php');

$start = microtime(true);

$app = new App();
$app->run();

echo (microtime(true) - $start) * 1000;