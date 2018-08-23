<?php
/**
 * This is the main faile of the corephp-request package
 *
 * PHP Version 7.1
 *
 * @category  CorePHP-Module
 * @package   CorePHP\Requests
 * @author    Eduardo Aguilar <dante.aguilar41@gmail.com>
 * @copyright 2018 Eduardo Aguilar
 * @license   https://github.com/danteay/corephp-requests/LICENSE Apache-2
 * @link      https://github.com/danteay/corephp-requests
 */

namespace CorePHP\Requests;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Request
 *
 * @category  Class
 * @package   CorePHP\Requests
 * @author    Eduardo Aguilar <dante.aguilar41@gmail.com>
 * @copyright 2018 Eduardo Aguilar
 * @license   https://github.com/danteay/corephp-requests/LICENSE Apache-2
 * @link      https://github.com/danteay/corephp-requests
 */
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
    private $_target;

    /**
     * Flat to keep the origin url set in the first instanciation
     *
     * @var bool
     */
    private $_preserveHost;

    /**
     * Request Constructor
     */
    public function __construct()
    {
        $this->_target = RequestTarget::getInstanace();
    }

    /**
     * Make and instace of Request
     *
     * @param string $type    Request method that will be used
     * @param string $url     Target URL of the request
     * @param mixed  $body    Content of the request
     * @param array  $headers Headers of the request
     * @param array  $auth    An array that contains the user and password
     *                        for basicauth in format
     *                        ["user" => ... , "pass" => ... ]
     *
     * @return Request
     *
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
            $instance->_target->withUri(new RequestUri($url));
        }

        if (!empty($type)) {
            if (!in_array($type, self::ALLOW_METHODS)) {
                throw new \Exception(
                    "Invalid method {$type} to create an instance of Request"
                );
            }

            $instance->_target->withMethod($type);
        }

        if (!empty($headers) && is_array($headers)) {
            $instance->_target->withHeaders($headers);
        }

        if (!empty($headers) && is_array($auth)) {
            $instance->_target->withBasicAuth($auth['user'], $auth['pass']);
        }

        if (!empty($data)) {
            $instance->_target->withBody($body);
        }

        return $instance;
    }

    /**
     * Create an instance of GET Request
     *
     * @param string $url     The target URL for the request
     * @param array  $headers Headers of the request
     * @param array  $auth    Basicath data if needed
     *
     * @return Request
     *
     * @throws \Exception
     */
    public static function get($url, $headers=[], $auth=[])
    {
        return self::getInstance(self::GET, $url, null, $headers, $auth);
    }

    /**
     * Create instanace of POST Request
     *
     * @param string $url     The target URL for the request
     * @param mixed  $data    Request body information (string, array or object)
     * @param array  $headers Headers of the request
     * @param array  $auth    Basicath data if needed
     *
     * @return Request
     *
     * @throws \Exception
     */
    public static function post($url, $data=null, $headers=[], $auth=[])
    {
        return self::getInstance(self::POST, $url, $data, $headers, $auth);
    }

    /**
     * Create Instance of PUT Request
     *
     * @param string $url     The target URL for the request
     * @param mixed  $data    Request body information (string, array or object)
     * @param array  $headers Headers of the request
     * @param array  $auth    Basicath data if needed
     *
     * @return Request
     *
     * @throws \Exception
     */
    public static function put($url, $data=null, $headers=[], $auth=[])
    {
        return self::getInstance(self::PUT, $url, $data, $headers, $auth);
    }

    /**
     * Create Instance of DELETE Request
     *
     * @param string $url     The target URL for the request
     * @param mixed  $data    Request body information (string, array or object)
     * @param array  $headers Headers of the request
     * @param array  $auth    Basicath data if needed
     *
     * @return Request
     *
     * @throws \Exception
     */
    public static function delete($url, $data=null, $headers=[], $auth=[])
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
     * @param string $method Request method (GET, POST, PUT, DELETE)
     *
     * @return Request
     */
    public function withMethod($method)
    {
        $this->target->withMethod($method);
        return $this;
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
     * @param RequestTarget $requestTarget Object with all the request config
     *
     * @return Request
     */
    public function withRequestTarget(RequestTarget $requestTarget)
    {
        $this->target = $requestTarget;
        return $this;
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
     * @param UriInterface $uri          Instance of UriInterface for the
     *                                   request
     * @param boolean      $preserveHost Falg to keep the host trougth the
     *                                   targets
     *
     * @return Request
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
     * @param StreamInterface $body Body StreamInterface with the request body
     *
     * @return Request
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
     * @param string $name Name of a header
     *
     * @return boolean
     */
    public function hasHeader($name)
    {
        return array_key_exists($name, $this->getHeaders());
    }

    /**
     * Retrive the value of a header
     *
     * @param string $name Name of the header
     *
     * @return null|array
     */
    public function getHeader($name)
    {
        return $this->getHeaders[$name] ?? null;
    }

    /**
     * Retrive all values of a header into a string
     *
     * @param string $name Name of a header
     *
     * @return null|string
     */
    public function getHeaderLine($name)
    {
        return $this->hasHeader($name) ?
            implode(', ', $this->getHeader($name)) : null;
    }

    /**
     * Set header to the request
     *
     * @param string $name  Name of the header
     * @param string $value Value of the header
     *
     * @return Request
     */
    public function withHeader($name, $value)
    {
        $headers = $this->getHeaders();
        $headers[$name] = [$value];
        $this->target->withHeaders($headers);

        return $this;
    }

    /**
     * Add value to a header
     *
     * @param string $name  Name of the header
     * @param string $value Value of the header
     *
     * @return Request
     */
    public function withAddedHeader($name, $value)
    {
        $headers = $this->getHeaders();
        $headers[$name][] = $value;
        $this->target->withHeaders($headers);

        return $this;
    }

    /**
     * Remove a specific header
     *
     * @param string $name name of the header
     *
     * @return Request
     */
    public function withoutHeader($name)
    {
        $headers = $this->getHeaders();
        unset($headers[$name]);
        $this->target->withHeaders($headers);

        return $this;
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

    /**
     * Not implemented
     *
     * @return string
     *
     * @throws \BadMethodCallException
     */
    public function getProtocolVersion()
    {
        throw new \BadMethodCallException("Not Implemented Method");
    }

    /**
     * Not implemented
     *
     * @param string $version Not implemented
     *
     * @return void
     *
     * @throws \BadMethodCallException
     */
    public function withProtocolVersion($version)
    {
        throw new \BadMethodCallException("Not Implemented Method");
    }
}