<?php

namespace App\Http;

class Request
{
    /**
     * Método HTTP da requisição
     * 
     * @var string
     */
    private $httpMethod;

    /**
     * URI da página
     * 
     * @var string
     */
    private $uri;

    /**
     * Parâmetros da URL ($_GET)
     * 
     * @var array
     */
    private $queryParams = [];

    /** 
     * Variáveis recebidas no POST da página ($_POST)
     * 
     * @var array
     */
    private $postVars = [];

    /**
     * Cabeçalhos da requisição
     *
     * @var array
     */
    private $headers = [];

    /**
     * Construtor da classe
     *
     * @return void
     */
    public function __construct()
    {
        $this->queryParams = $_GET ?? [];
        $this->postVars = $_POST ?? [];
        $this->headers = getallheaders();
        $this->httpMethod = $_SERVER['REQUEST_METHOD'];
        $this->uri = $_SERVER['REQUEST_URI'];
    }

    /**
     * Método responsável por retornar o método da requisição
     *
     * @return string
     */
    public function getHttpMethod(): string
    {
        return $this->httpMethod;
    }

    /**
     * Método responsável por retornar a URI da requisição
     *
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * Método responsável por retornar os headers da requisição
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Método responsável por retornar os parâmetros da URL da requisição
     *
     * @return array
     */
    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    /**
     * Método responsável por retornar as variáveis POST da requisição
     *
     * @return array
     */
    public function getPostVars(): array
    {
        return $this->postVars;
    }
}
