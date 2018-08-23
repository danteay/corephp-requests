<?php

namespace CorePHP\Requests;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\StreamInterface;

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
     * @var RequestTarget
     */
    private $target;

    /**
     * Http object
     *
     * @var Http
     */
    private $http;

    /**
     * Flat to keep the origin url set in the first instanciation
     *
     * @var bool
     */
    private $preserveHost;

    /**
     * Request Constructor
     */
    public function __construct()
    {
        $this->target = RequestTarget::getInstanace();
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
    public static function getInstance(
        $type=null,
        $url=null,
        $body=null,
        array $headers=null,
        array $auth=null
    ) {
        $instance = new Request();

        if (!empty($url)) {
            $instance->target->withUri(new RequestUri($url));
        }

        if (!empty($type)) {
            if (!in_array($type, self::ALLOW_METHODS)) {
                throw new \Exception("Invalid method {$type} to create an instance of Request");
            }

            $instance->target->withMethod($type);
        }

        if (!empty($headers) && is_array($headers)) {
            $instance->target->withHeaders($headers);
        }

        if (!empty($headers) && is_array($auth)) {
            $instance->target->withBasicAuth($auth['user'], $auth['pass']);
        }

        if (!empty($data)) {
            $instance->target->withBody($body);
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
     * @return static
     */
    public function withMethod($method)
    {
        $this->target->withMethod($method);
        return self;
    }

    /**
     * Return the Target object
     *
     * @return RequestTarget
     */
    public function getRequestTarget()
    {
        return $this->target;
    }

    /**
     * Set Target to the current Request
     *
     * @param RequestTarget $requestTarget
     * @return static
     */
    public function withRequestTarget(RequestTarget $requestTarget)
    {
        $this->target = $requestTarget;
        return self;
    }

    /**
     * Return the Request URI of the target
     *
     * @return UriInterface
     */
    public function getUri()
    {
        return $this->target->getUri();
    }

    /**
     * Add a valid URI for the request
     *
     * @param UriInterface $uri
     * @param boolean $preserveHost
     * @return static
     */
    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        $lastUri = $this->target->getUri();
        $host = $lastUri->getHost();

        if ($this->preserveHost) {
            $uri->withHost($host);
            $this->uri = $uri;
            $this->preserveHost = $preserveHost;
        } else {
            $this->uri = $uri;
        }

        return self;
    }

    /**
     * Undocumented function
     *
     * @return StreamInterface
     */
    public function getBody()
    {
        return $this->target->getBody();
    }

    /**
     * Set the request body
     *
     * @param StreamInterface $body
     * @return static
     */
    public function withBody(StreamInterface $body)
    {
        $this->target->withBody($body);
        return self;
    }

    /**
     * Return the headers of the target
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->target->getHeaders();
    }

    /**
     * Validate if a specific header is set to the target
     *
     * @param string $name
     * @return boolean
     */
    public function hasHeader($name)
    {
        return array_key_exists($name, $this->getHeaders());
    }

    /**
     * Retrive the value of a header
     *
     * @param string $name
     * @return null|array
     */
    public function getHeader($name)
    {
        return $this->getHeaders[$name] ?? null;
    }

    /**
     * Retrive all values of a header into a string
     *
     * @param string $name
     * @return null|string
     */
    public function getHeaderLine($name)
    {
        return $this->hasHeader($name) ? implode(', ', $this->getHeader($name)) : null;
    }

    /**
     * Set header to the request
     *
     * @param string $name
     * @param string $value
     */
    public function withHeader($name, $value)
    {
        $headers = $this->getHeaders();
        $headers[$name] = [$value];
        $this->target->withHeaders($headers);
    }

    /**
     * Add value to a header
     *
     * @param string $name
     * @param string $value
     */
    public function withAddedHeader($name, $value)
    {
        $headers = $this->getHeaders();
        $headers[$name][] = $value;
        $this->target->withHeaders($headers);
    }

    /**
     * Remove a specific header
     *
     * @param string $name
     */
    public function withoutHeader($name)
    {
        $headers = $this->getHeaders();
        unset($headers[$name]);
        $this->target->withHeaders($headers);
    }

    /**
     * Execute Request
     *
     * @return Response
     */
    public function execute()
    {
        $http = Http::getInstance($this->tartget);
        return $http->executeRequest();
    }

    public function getProtocolVersion()
    {
        throw new \BadMethodCallException("Not Implemented Method");
    }

    public function withProtocolVersion($version)
    {
        throw new \BadMethodCallException("Not Implemented Method");
    }
}