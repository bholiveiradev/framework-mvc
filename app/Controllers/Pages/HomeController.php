<?php

namespace App\Controllers\Pages;

use App\Utils\view;
use App\Models\Entity\Organization;

class HomeController extends PageController
{    
    /**
     * Método responsável por retornar o conteúdo (view) da home
     * 
     * @return string
     */
    public static function getHome(): string
    {
        $organization = new Organization();

        // Retorna a view da home
        $content = view::render('pages/home', [
            'name' => $organization->name,
        ]);

        // Retorna a view da página
        return parent::getPage('BH OLIVEIRA DEV', $content);
    }
}
