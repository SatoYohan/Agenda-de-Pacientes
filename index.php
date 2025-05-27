<?php
/*
 * index.php - Front Controller
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1); //
ini_set('log_errors', 1); //
ini_set('error_log', __DIR__ . '/php-error.log'); //
error_reporting(E_ALL); //

// 1. Inclua o autoloader do Composer (ESSENCIAL)
require_once __DIR__ . '/vendor/autoload.php';

// 2. Inclua seu arquivo de configuração global (onde DB_HOST, BASE_URL, etc. são definidos)
require_once __DIR__ . '/config/config.php'; //

// 3. Remova os require_once manuais para Controller.php e Model.php
// O Composer cuidará disso se eles estiverem namespaced (Core\Controller, Core\Model)
// require_once __DIR__ . '/core/Controller.php'; // REMOVA ESTA LINHA
// require_once __DIR__ . '/core/Model.php';      // REMOVA ESTA LINHA

// 4. Inicie a sessão DEPOIS de carregar o autoloader e config
session_start(); //

// Lógica de Roteamento
$url = $_GET['url'] ?? 'login/index'; // Rota padrão
// Limpa a URL e a divide em partes
$url = explode('/', filter_var(rtrim($url, '/'), FILTER_SANITIZE_URL)); //

// Define o nome do controller e o método
// Adiciona o namespace App\Controllers ao nome da classe do controller
$controllerNamePart = ucfirst($url[0] ?? 'Login'); // Nome base do controller, ex: Login, Paciente
$controllerClass = 'App\\Controllers\\' . $controllerNamePart . 'Controller'; // Nome completo da classe com namespace

$method = $url[1] ?? 'index'; // Método a ser chamado, padrão 'index'
$params = array_slice($url, 2); // Parâmetros para o método

// Não precisamos mais do $controllerPath manual se o Composer está funcionando
// $controllerPath = __DIR__ . '/app/Controllers/' . $controllerName . '.php'; // REMOVA OU COMENTE ESTA LINHA

// Verifica se a classe do controller existe (o autoloader do Composer tentará carregá-la)
if (class_exists($controllerClass)) {
    $controllerInstance = new $controllerClass(); // Instancia o controller usando o nome completo da classe

    if (method_exists($controllerInstance, $method)) {
        call_user_func_array([$controllerInstance, $method], $params); // Chama o método
        // exit; // O exit aqui pode ser desnecessário se o método do controller já faz exit (ex: após um redirect)
    } else {
        // Controller existe, mas método não
        echo "<br><strong>Erro:</strong> Método '{$method}' não encontrado no controller '{$controllerClass}'.<br>";
        // Considerar mostrar uma página de erro 404 mais amigável
    }
} else {
    // Controller não foi encontrado pelo autoloader
    echo "<br><strong>Erro:</strong> Controlador '{$controllerClass}' não encontrado.<br>"; //
    echo "Verifique se o arquivo do controller existe no caminho esperado (ex: app/Controllers/{$controllerNamePart}Controller.php).<br>";
    echo "Verifique se o namespace 'App\\Controllers' está correto no arquivo do controller.<br>";
    echo "Certifique-se de ter executado 'composer dump-autoload -o' no terminal.<br>";
    // Considerar mostrar uma página de erro 404 mais amigável
}

?>