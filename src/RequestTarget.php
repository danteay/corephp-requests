<?php

namespace CorePHP\Requests;

class RequestTarget
{
    private $uri;
    private $body;
    private $method;
    private $headers;
    private $basicAuth;

    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setMethod($method)
    {
        $this->method = $method;
    }

    public function geMethod()
    {
        return $this->method;
    }

    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function setBasicAuth($user, $password)
    {
        $this->basicAuth = [
            'user' => $user,
            'pass' => $password
        ];
    }

    public function getBasicAuth()
    {
        return $this->basicAuth;
    }
}