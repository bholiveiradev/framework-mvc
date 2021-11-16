<?php

namespace App\Controllers\Pages;

use App\Utils\view;
use App\Models\Entity\Organization;

class AboutController extends PageController
{    
    /**
     * Método responsável por retornar o conteúdo (view) da sobre
     * 
     * @return string
     */
    public static function getAbout(): string
    {
        $organization = new Organization();

        // Retorna a view da home
        $content = view::render('pages/about', [
            'name' => $organization->name,
            'description' => $organization->description,
            'site' => $organization->site
        ]);

        // Retorna a view da página
        return parent::getPage('BH OLIVEIRA DEV', $content);
    }
}
