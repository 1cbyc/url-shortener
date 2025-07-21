<?php

header('Content-Security-Policy: default-src \'self\'; style-src \'self\' https://fonts.googleapis.com https://cdnjs.cloudflare.com; font-src \'self\' https://fonts.gstatic.com; script-src \'self\' https://cdnjs.cloudflare.com');
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: no-referrer-when-downgrade');
header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'on') {
    $httpsUrl = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('Location: ' . $httpsUrl, true, 301);
    exit;
}

ini_set('session.cookie_secure', '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Strict');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/bootstrap.php';

use Dotenv\Dotenv;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

session_start();

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$dispatcher = simpleDispatcher(function(RouteCollector $r) {
    $r->addRoute('GET', '/', ['UrlShortener\\Controller\\HomeController', 'index']);
    $r->addRoute('GET', '/admin', ['UrlShortener\\Controller\\AdminController', 'dashboard']);
    $r->addRoute('POST', '/shorten', ['UrlShortener\\Controller\\ShortenController', 'shorten']);
    $r->addRoute('POST', '/register', ['UrlShortener\\Controller\\AuthController', 'register']);
    $r->addRoute('POST', '/login', ['UrlShortener\\Controller\\AuthController', 'login']);
    $r->addRoute('POST', '/logout', ['UrlShortener\\Controller\\AuthController', 'logout']);
    $r->addRoute('POST', '/api/shorten', ['UrlShortener\\Controller\\ApiController', 'shorten']);
    $r->addRoute('GET', '/api/analytics/{code}', ['UrlShortener\\Controller\\ApiController', 'analytics']);
    $r->addRoute('GET', '/{code}', ['UrlShortener\\Controller\\RedirectController', 'redirect']);
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo 'Not Found';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo 'Method Not Allowed';
        break;
    case FastRoute\Dispatcher::FOUND:
        [$class, $method] = $routeInfo[1];
        $vars = $routeInfo[2];
        (new $class)->$method($vars);
        break;
} 