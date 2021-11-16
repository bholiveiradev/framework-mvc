<?php

use App\Http\Response;
use \App\Controllers\Pages;

// Rota Home GET
$router->get('/', [
    function () {
        return new Response(Pages\HomeController::getHome());
    }
]);

// Rota Sobre GET
$router->get('/sobre', [
    function () {
        return new Response(Pages\AboutController::getAbout());
    }
]);

// Rota Sobre GET
$router->get('/pagina/{id}/{detalhes}', [
    function ($id, $detalhes) {
        return new Response('Página ' . $id . ' - Detalhes: ' . $detalhes);
    }
]);
