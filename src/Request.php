<?php

namespace CorePHP\Requests;

use Psr\Http\Message\RequestInterface;

class Request implements RequestInterface
{
    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';

    const ALLOW_METHODS = [
        self::GET,
        self::POST,
        self::PUT,
        self::DELETE
    ];

    /**
     * Target information of the request
     *
     * @var \CorePHP\Requests\RequestTarget
     */
    private $target;

    /**
     * Http object
     *
     * @var \CorePHP\Requests\Http
     */
    private $http;

    /**
     * Request Constructor
     */
    public function __construct()
    {
        $this->target = new RequestTarget();
        $this->http = new Http();
    }

    /**
     * Make and instace of Request
     *
     * @param string $type
     * @param string $url
     * @param mixed $body
     * @param array $headers
     * @param array $auth
     * @return Request
     * @throws \Exception
     */
    public static function getInstance($type=null, $url=null, $body=null, $headers=array(), $auth=array())
    {
        $instance = new Request();

        if (!empty($url)) {
            $instance->target->setUri($url);
        }

        if (!empty($type)) {
            if (!in_array($type, self::ALLOW_METHODS)) {
                throw new \Exception("Invalid method {$type} to create an instance of Request");
            }

            $instance->target->setMethod($type);
        }

        if (!empty($headers) && is_array($headers)) {
            $instance->target->setHeaders($headers);
        }

        if (!empty($headers) && is_array($auth)) {
            $instance->target->setBasicAuth($auth['user'], $auth['pass']);
        }

        if (!empty($data)) {
            $instance->target->setBody($body);
        }

        return $instance;
    }

    /**
     * Create an instance of GET Request
     *
     * @param string $url
     * @param array $auth
     * @param array $headers
     * @return Request
     * @throws \Exception
     */
    public static function get($url, $headers=array(), $auth=array())
    {
        return self::getInstance(self::GET, $url, null, $headers, $auth);
    }

    /**
     * Create instanace of POST Request
     *
     * @param string $url
     * @param mixed $data
     * @param array $auth
     * @param array $headers
     * @return Request
     * @throws \Exception
     */
    public static function post($url, $data=null, $headers=array(), $auth=array())
    {
        return self::getInstance(self::POST, $url, $data, $headers, $auth);
    }

    /**
     * Create Instance of PUT Request
     *
     * @param string $url
     * @param mixed $data
     * @param array $auth
     * @param array $headers
     * @return Request
     * @throws \Exception
     */
    public static function put($url, $data=array(), $headers=array(), $auth=array())
    {
        return self::getInstance(self::PUT, $url, $data, $headers, $auth);
    }

    /**
     * Create Instance of DELETE Request
     *
     * @param string $url
     * @param mixed $data
     * @param array $auth
     * @param array $headers
     * @return Request
     * @throws \Exception
     */
    public static function delete($url, $data=array(), $headers=array(), $auth=array())
    {
        return self::getInstance(self::DELETE, $url, $data, $headers, $auth);
    }

    /**
     * Return the target method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->target->getMethod();
    }

    /**
     * Set target MEthod
     *
     * @param string $method
     */
    public function withMethod($method)
    {
        $this->target->setMethod($method);
    }

    /**
     * Return the Target object
     *
     * @return \CorePHP\Requests\RequestTarget
     */
    public function getRequestTarget()
    {
        return $this->target;
    }

    /**
     * Set Target to the current Request
     *
     * @param RequestTarget $requestTarget
     */
    public function withRequestTarget(RequestTarget $requestTarget)
    {
        $this->target = $requestTarget;
    }

    public function getUri()
    {
        return $this->target->getUri();
    }

    public function getBody()
    {
        return $this->target->getBody();
    }

    public function withBody(\Psr\Http\Message\StreamInterface $body)
    {
        throw new \BadMethodCallException("Not Implemented Method");
    }

    public function withUri(\Psr\Http\Message\UriInterface $uri, $preserveHost = false)
    {
        throw new \BadMethodCallException("Not Implemented Method");
    }

    public function getProtocolVersion()
    {
        throw new \BadMethodCallException("Not Implemented Method");
    }

    public function withProtocolVersion($version)
    {
        throw new \BadMethodCallException("Not Implemented Method");
    }

    public function getHeaders()
    {
        throw new \BadMethodCallException("Not Implemented Method");
    }

    public function hasHeader($name)
    {
        throw new \BadMethodCallException("Not Implemented Method");
    }

    public function getHeader($name)
    {
        throw new \BadMethodCallException("Not Implemented Method");
    }

    public function getHeaderLine($name)
    {
        throw new \BadMethodCallException("Not Implemented Method");
    }

    public function withHeader($name, $value)
    {
        throw new \BadMethodCallException("Not Implemented Method");
    }

    public function withAddedHeader($name, $value)
    {
        throw new \BadMethodCallException("Not Implemented Method");
    }

    public function withoutHeader($name)
    {
        throw new \BadMethodCallException("Not Implemented Method");
    }

    public function execute()
    {

    }
}