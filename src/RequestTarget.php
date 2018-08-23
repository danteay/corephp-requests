<?php

namespace CorePHP\Requests;

use \Psr\Http\Message\UriInterface;
use \Psr\Http\Message\StreamInterface;

class RequestTarget
{
    /**
     * Request Uri
     *
     * @var UriInterface
     */
    private $uri;

    /**
     * Request body
     *
     * @var StreamInterface
     */
    private $body;

    /**
     * Request Method
     *
     * @var string
     */
    private $method;

    /**
     * Request Headers
     *
     * @var array
     */
    private $headers;

    /**
     * Request Basic Autentication
     *
     * @var array
     */
    private $basicAuth;

    public function __construct()
    {
        $this->uri = RequestUri::getInstance();
        $this->body = RequestBody::getInstance();
    }

    /**
     * Retrive an instance of Request target
     *
     * @param UriInterface $uri
     * @param string $method
     * @param StreamInterface $body
     * @param array $headers
     * @param array $basicAuth
     * @return RequestTarget
     */
    public static function getInstanace(
        UriInterface $uri=null,
        $method=null,
        StreamInterface $body=null,
        $headers=null,
        $basicAuth=null
    )
    {
        $instanace = new RequestTarget();

        if (!empty($uri)) {
            $instanace->withUri($uri);
        }

        if (!empty($body)) {
            $instanace->withBody($body);
        }

        if (!empty($method)) {
            $instanace->withMethod($method);
        }

        if (!empty($headers)) {
            $instanace->withHeaders($headers);
        }

        if (!empty($basicAuth)) {
            $instanace->withBasicAuth($basicAuth);
        }

        return $instanace;
    }

    /**
     * Register the Uri of the request target
     *
     * @param UriInterface $uri The Uri interface that will be associated with the request
     */
    public function withUri(UriInterface $uri)
    {
        $this->uri = $uri;
    }

    /**
     * Retrive the associted Uri interface
     *
     * @return UriInterface
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Register Request body
     *
     * @param StreamInterface $body The request body. This could be a string, array or object
     */
    public function withBody($body)
    {
        $this->body = $body;
    }

    /**
     * Retrive Request body
     *
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set request method
     *
     * @param string $method
     */
    public function withMethod($method)
    {
        $this->method = $method;
    }

    /**
     * Retrive request method
     *
     * @return string
     */
    public function geMethod()
    {
        return $this->method;
    }

    /**
     * Add headers
     *
     * @param array $headers
     */
    public function withHeaders($headers)
    {
        $this->headers = $headers;
    }

    /**
     * Retrive the request headers
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Save the user and password for BasicAuth
     *
     * @param string $user
     * @param null|string $password
     */
    public function withBasicAuth($user, $password=null)
    {
        $this->basicAuth = "{$user}";

        $this->basicAuth .= !empty($password) ? ":{$password}" : "";

        return $this->basicAuth;
    }

    /**
     * Retrive the basic auth information
     *
     * @return null|array
     */
    public function getBasicAuth()
    {
        return $this->basicAuth;
    }
}