<?php

$config = file_exists('config/config.php') ? include_once 'config/config.php' : [];

require('load.php');

$_GET['key'] = (isset($_GET['key']) ? $_GET['key'].'/' : 'index/');
$key = $_GET['key'];
$separator = explode('/', $key);
$controller = '\App\Controllers\\' . $separator[0] . "Controller";
$action = ($separator[1] == null ? 'index' : $separator[1]);
$param = (isset($separator[2]) ? $separator[2] : null);

$app = new $controller;

if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)) {
    $param = $param == null ? $_POST : [$param, $_POST];
}

if($param == null) $app->$action();
else $app->$action($param);
