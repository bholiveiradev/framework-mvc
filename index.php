<?php

require __DIR__ . '/vendor/autoload.php';

use \App\Http\Router;
use App\Utils\View;

define('URL', 'http://localhost/mvc');

// Define os valores padrões das variáveis comuns
View::init([
        'URL' => URL
]);

// Instacia o Router
$router = new Router(URL);

// Inclui as rotas de páginas
include __DIR__ . '/routes/pages.php';

// Executa a rota e imprime o response
$router->run()
        ->sendResponse();
