<?php

namespace App\Http;

use App\Http\Response;
use \Closure;
use \Exception;
use \ReflectionFunction;

class Router
{
    /**
     * URL completa do projeto (raiz)
     *
     * @var string
     */
    private $url = '';

    /**
     * Prefixo de todas as rotas
     *
     * @var string
     */
    private $prefix = '';

    /**
     * Índice de rotas
     *
     * @var array
     */
    private $routes = [];

    /**
     * Instancia de Request
     *
     * @var \App\Http\Request
     */
    private $request;

    /**
     * Método responsável por iniciar a classe
     *
     * @param  string $url
     * @return void
     */
    public function __construct(string $url)
    {
        $this->request = new Request();
        $this->url = $url;
        $this->setPrefix();
    }

    /**
     * Método responsável por definir o prefixo das rotas
     *
     * @return void
     */
    private function setPrefix(): void
    {
        // Informações da URL atual
        $parseUrl = parse_url($this->url);

        // Define o prefixo
        $this->prefix = $parseUrl['path'] ?? '';
    }

    /**
     * Método responsável por adicionar uma rota na classe
     *
     * @param  string $method
     * @param  string $route
     * @param  array  $params
     * @return void
     */
    private function addRoute(string $method, string $route, array $params = []): void
    {
        // Validação dos parâmetros
        foreach ($params as $key => $value) {
            if ($value instanceof Closure) {
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }

        // Variáveis da rota
        $params['variables'] = [];

        // Padrão de validação das variáveis das rotas
        $patternVariable = '/{(.*?)}/';

        if (preg_match_all($patternVariable, $route, $matches)) {
            $route = preg_replace($patternVariable, '(.*?)', $route);
            $params['variables'] = $matches[1];
        }

        // Padrão de validação da URL
        $patternRoute = '/^' . str_replace('/', '\/', $route) . '$/';

        //Adiciona a rota dentro da classe
        $this->routes[$patternRoute][$method] = $params;
    }

    /**
     * Método responsável por definir uma rota GET
     *
     * @param  string $route
     * @param  array  $params
     */
    public function get(string $route, array $params = [])
    {
        return $this->addRoute('GET', $route, $params);
    }

    /**
     * Método responsável por definir uma rota POST
     *
     * @param  string $route
     * @param  array  $params
     */
    public function post(string $route, array $params = [])
    {
        return $this->addRoute('POST', $route, $params);
    }

    /**
     * Método responsável por definir uma rota PUT
     *
     * @param  string $route
     * @param  array  $params
     */
    public function put(string $route, array $params = [])
    {
        return $this->addRoute('PUT', $route, $params);
    }

    /**
     * Método responsável por definir uma rota DELETE
     *
     * @param  string $route
     * @param  array  $params
     */
    public function delete(string $route, array $params = [])
    {
        return $this->addRoute('DELETE', $route, $params);
    }

    /**
     * Método responsável por retornar a URI sem o prefixo
     *
     * @return string
     */
    private function getUri(): string
    {
        // URI da request
        $uri = $this->request->getUri();

        // Fatia a URI com o prefixo
        $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];

        // Retorna a URI sem o prefixo
        return end($xUri);
    }

    /**
     * Método responsável por retornar os dados da rota atual
     * 
     * @return array
     */
    private function getRoute(): array
    {
        // URI
        $uri = $this->getUri();

        // Method
        $httpMethod = $this->request->getHttpMethod();

        foreach ($this->routes as $patternRoute => $methods) {
            // Verifica se a URI bate com o padrão
            if (preg_match($patternRoute, $uri, $matches)) {

                // Verifica se existe o método
                if (isset($methods[$httpMethod])) {
                    
                    // Remove a primeira posição que contém a uri completa, deixando apenas os valores
                    unset($matches[0]);

                    // Variáveis procesadas
                    $keys = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
                    $methods[$httpMethod]['variables']['request'] = $this->request;

                    // Retorna os parâmetros da rota
                    return $methods[$httpMethod];
                }

                // Método não permitido/implementado
                throw new Exception('Método não permitido', 405);
            }
        }

        // URL não encontrada
        throw new Exception('HTTP não encontrado', 404);
    }

    /**
     * Método responsável por executar a rota atual
     *
     * @return \App\Http\Response
     */
    public function run(): Response
    {
        try {
            // Obtém a rota atual
            $route = $this->getRoute();

            if (!isset($route['controller'])) {
                throw new Exception('A URL não pôde ser processada', 500);
            }

            // Argumentos da função
            $args = [];

            // Reflection
            $reflection = new ReflectionFunction($route['controller']);
            foreach ($reflection->getParameters() as $parameter) {
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
            }

            // Retorna a execução da função
            return call_user_func_array($route['controller'], $args);
        } catch (\Throwable $e) {
            return new Response($e->getMessage(), $e->getCode());
        }
    }
}
