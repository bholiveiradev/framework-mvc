<?php

namespace App\Utils;

class View
{
    /**
     * Variáveis padrões da view
     * 
     * @var array
     */
    private static $vars = [];
    
    /**
     * Método responsável por definir os dados iniciais da classe
     *
     * @param  array $vars
     * @return void
     */
    public static function init(array $vars = []): void
    {
        self::$vars = $vars;
    }

    /**
     * Método responsável por retornar o conteúdo de uma view
     *
     * @param  string $view
     * @return string
     */
    private static function getContentView(string $view): string
    {
        $file = __DIR__ . '/../../resources/views/' . $view . '.html';
        return file_exists($file) ? file_get_contents($file) : '';
    }

    /**
     * Método responsável por renderizar o conteúdo da view
     * 
     * @param string $view
     * @param array  $vars (string|numeric)
     * @return string
     */
    public static function render(string $view, array $vars = []): string
    {
        // Conteúdo da view
        $content = self::getContentView($view);

        // Merge de variáveis da view
        $vars = array_merge(self::$vars, $vars);

        // Chaves do array de variáveis
        $keys = array_keys($vars);
        $keys = array_map(function ($item) {
            return '{{ ' . $item . ' }}';
        }, $keys);

        // Retorna o conteúdo rederizado
        return str_replace($keys, array_values($vars), $content);
    }
}
