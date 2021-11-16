<?php

namespace App\Controllers\Pages;

use App\Utils\view;

class PageController
{
    /**
     * Método responsável por renderizar o header da página
     * 
     * @return string
     */
    private static function getHeader()
    {
        return View::render('pages/header');
    }

    /**
     * Método responsável por renderizar o footer da página
     * 
     * @return string
     */
    private static function getFooter()
    {
        return View::render('pages/footer');
    }

    /**
     * Método responsável por retornar o conteúdo (view) da page genérica
     * @var string $title
     * @var mixed $content [array|string]
     * @return string
     */
    public static function getPage($title, $content): string
    {
        return view::render('pages/page', [
            'title' => $title,
            'header' => self::getHeader(),
            'footer' => self::getFooter(),
            'content' => $content
        ]);
    }
}
