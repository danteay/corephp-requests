<?php

namespace CorePHP\Requests;

use Psr\Http\Message\RequestInterface;

class Request implements RequestInterface
{
    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';

    private $target;

    public function __construct()
    {
        $this->target = null;
    }

    /**
     * Execute Get request
     * @param string $url
     * @param array $auth
     * @param array $headers
     * @return Response
     * @throws \Exception
     */
    public static function get($url, $headers=array(), $auth=array())
    {
        $http = new Http($url);
        $http->setAuth($auth);
        $http->setMethod(self::GET);
        $http->setHeaders($headers);
        $res = $http->executeRequest();

        return $res;
    }

    /**
     * Execute Post request
     * @param string $url
     * @param array $data
     * @param array $auth
     * @param array $headers
     * @return Response
     * @throws \Exception
     */
    public static function post($url, $data=array(), $headers=array(), $auth=array())
    {
        $http = new Http($url);
        $http->setAuth($auth);
        $http->setJsonData($data);
        $http->setMethod(self::POST);
        $http->setHeaders($headers);
        $res = $http->executeRequest();

        return $res;
    }

    /**
     * Execute Put request
     * @param string $url
     * @param array $data
     * @param array $auth
     * @param array $headers
     * @return Response
     * @throws \Exception
     */
    public static function put($url, $data=array(), $headers=array(), $auth=array())
    {
        $http = new Http($url);
        $http->setAuth($auth);
        $http->setJsonData($data);
        $http->setMethod(self::PUT);
        $http->setHeaders($headers);
        $res = $http->executeRequest();

        return $res;
    }

    /**
     * Execute Delete request
     * @param string $url
     * @param array $data
     * @param array $auth
     * @param array $headers
     * @return Response
     * @throws \Exception
     */
    public static function delete($url, $data=array(), $headers=array(), $auth=array())
    {
        $http = new Http($url);
        $http->setAuth($auth);
        $http->setJsonData($data);
        $http->setMethod(self::DELETE);
        $http->setHeaders($headers);
        $res = $http->executeRequest();

        return $res;
    }

    public function getMethod()
    {
        return $this->target->getMethod();
    }

    public function withMethod($method)
    {
        $this->target->setMethod($method);
    }

    public function getRequestTarget()
    {
        return $this->target;
    }

    public function withRequestTarget($requestTarget)
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
}