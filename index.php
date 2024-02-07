<?php
declare(strict_types=1);

ob_start();

require __DIR__ . "/vendor/autoload.php";
require __DIR__ . "/core/support/config.php";
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

        $param = [
            'var' => $vars,
            'query' => filter_input_array(INPUT_GET, FILTER_SANITIZE_SPECIAL_CHARS)
        ];

        if($httpMethod == 'POST' || $httpMethod == 'PUT' || $httpMethod == 'PATCH') {
            $param['body'] = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
        }

        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        $app->$method(new Core\Request($param));
        break;
}

ob_end_flush();
