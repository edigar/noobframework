<?php
declare(strict_types=1);

ob_start();

require __DIR__ . "/vendor/autoload.php";
require __DIR__ . '/core/support/config.php';
require __DIR__ . "/route/router.php";

$dispatcher = FastRoute\simpleDispatcher('routes');

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        header('HTTP/1.1 404 Not Found');
        include 'public/404.html';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        header('HTTP/1.1 405 Method Not Allowed');
        include 'public/404.html';
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = explode('::', $routeInfo[1]);
        $vars = $routeInfo[2];
        $method = $handler[1];

        $app = new $handler[0];

        if($httpMethod == 'POST' || $httpMethod == 'PUT' || $httpMethod == 'PATCH') {
            $param = [
                'var' => $vars,
                'query' => filter_input_array(INPUT_GET, FILTER_SANITIZE_SPECIAL_CHARS),
                'body' => filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS)
            ];
        } else {
            $param = [
                'var' => $vars,
                'query' => filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS)
            ];
        }

        $app->$method(new Core\Request($param));

        break;
}

ob_end_flush();


//NoobFramework ANTIGO:
//require __DIR__ . '/Core/support/config.php';
//require __DIR__ . '/load.php';
//
//$_GET['key'] = (isset($_GET['key']) ? $_GET['key'] . '/' : 'index/');
//$key = $_GET['key'];
//$separator = explode('/', $key);
//$controller = '\app\Controllers\\' . $separator[0] . "Controller";
//$action = ($separator[1] == null ? 'index' : $separator[1]);
//$param = isset($separator[2]) && !empty($separator[2]) ? $separator[2] : null;
//
//$app = new $controller;
//
//if($_SERVER['REQUEST_METHOD'] == 'POST') {
//    $param = ['get' => $param, 'post' => filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS)];
//} else {
//    $param = $param == null ? [] : ['get' => $param];
//}
//
//if(!method_exists($app, $action)) loadNotFound();
//spl_autoload_unregister('loadNotFound');
//
//$app->$action(new \Core\Request($param));
