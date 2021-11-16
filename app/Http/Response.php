<?php

namespace App\Http;

class Response
{
    /**
     * Código do Status HTTP
     *
     * @var integer
     */
    private $httpCode;

    /**
     * Cabeçalho do Response
     *
     * @var array
     */
    private $headers = [];

    /**
     * Tipo de conteúdo retornado
     *
     * @var string
     */
    private $contentType;

    /**
     * Conteúdo do response
     *
     * @var mixed
     */
    private $content;

    /**
     * Método responsável por iniciar a classe e definir os valores
     *
     * @param  mixed   $content
     * @param  integer $httpCode
     * @param  string  $contentType
     * @return void
     */
    public function __construct($content, int $httpCode = 200, string $contentType = 'text/html')
    {
        $this->httpCode = $httpCode;
        $this->content = $content;
        $this->setContentType($contentType);
    }

    /**
     * Método responsável por alterar o content type do response
     *
     * @param  string $contentType
     * @return void
     */
    public function setContentType(string $contentType): void
    {
        $this->contentType = $contentType;
        $this->addHeader('Content-Type', $contentType);
    }

    /**
     * Método responsável por adicionar um registro nos headers do response
     *
     * @param  string $key
     * @param  string $value
     * @return void
     */
    public function addHeader(string $key, string $value): void
    {
        $this->headers[$key] = $value;
    }
    
    /**
     * Método responsável por enviar os headers para o cliente (navegador)
     *
     * @return void
     */
    public function sendHeaders(): void
    {
        // Status do retorno
        http_response_code($this->httpCode);

        // Enviar headers
        foreach ($this->headers as $key => $value) {
            header($key . ': ' . $value);
        }
    }

    /**
     * Método responsável por enviar a responsta para o usuário
     *
     * @return void
     */
    public function sendResponse(): void
    {
        // Envia os headers
        $this->sendHeaders();

        // Imprime o conteúdo
        switch ($this->contentType) {
            case 'text/html':
                echo $this->content;
                exit;
        }
    }
}
