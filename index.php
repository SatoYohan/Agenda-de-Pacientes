<?php
/*
 * index.php - Front Controller
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php-error.log');
error_reporting(E_ALL);

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Model.php';

session_start();

$url = $_GET['url'] ?? 'login/index';
$url = explode('/', filter_var($url, FILTER_SANITIZE_URL));

$controllerName = ucfirst($url[0]) . 'Controller';
$method = $url[1] ?? 'index';
$params = array_slice($url, 2);

$controllerPath = __DIR__ . '/app/Controllers/' . $controllerName . '.php';

if (file_exists($controllerPath)) {
    require_once $controllerPath;
    $controller = new $controllerName();

    if (method_exists($controller, $method)) {
        call_user_func_array([$controller, $method], $params);
        exit;
    }
}

echo "<br><strong>Erro:</strong> Controlador ou método não encontrado.<br>";
echo "Verifique se o arquivo existe: {$controllerPath}<br>";
