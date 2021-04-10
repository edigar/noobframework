<?php

require __DIR__ . '/core/support/config.php';
require __DIR__ . '/load.php';

$_GET['key'] = (isset($_GET['key']) ? $_GET['key'] . '/' : 'index/');
$key = $_GET['key'];
$separator = explode('/', $key);
$controller = '\App\Controllers\\' . $separator[0] . "Controller";
$action = ($separator[1] == null ? 'index' : $separator[1]);
$param = isset($separator[2]) && !empty($separator[2]) ? $separator[2] : null;

$app = new $controller;

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $param = ['get' => $param, 'post' => filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS)];
} else {
    $param = $param == null ? [] : ['get' => $param];
}

if(!method_exists($app, $action)) loadNotFound();
spl_autoload_unregister('loadNotFound');

$app->$action(new \Core\Request($param));
