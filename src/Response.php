<?php

namespace CorePHP\Requests;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\StreamInterface;

class Response implements ResponseInterface
{
    /**
     * Response status code
     *
     * @var int
     */
    private $statusCode;

    /**
     * Status code Phrase
     *
     * @var string
     */
    private $reasonPhrase;

    /**
     * Headers collection
     *
     * @var array
     */
    private $headers;

    /**
     * Resquest protocol
     *
     * @var string
     */
    private $protocol;

    /**
     * Response body
     *
     * @var StreamInterface
     */
    private $body;

    /**
     * A parsed version of the body;
     *
     * @var mixed
     */
    private $bodyParsed;

    /**
     * Request uri
     *
     * @var UriInterface
     */
    private $uri;

    /**
     * flag to persist host in responses
     *
     * @var boolean
     */
    private $preserveHost;

    /**
     * Create an instance of Response object
     *
     * @param StreamInterface $body
     * @param int $statusCode
     * @param array $headers
     * @param UriInterface $uri
     * @param string $protocol
     * @return Response
     */
    public static function getInstance(
        StreamInterface $body = null,
        $statusCode = null,
        array $headers = null,
        UriInterface $uri = null,
        $protocol = null
    ) {
        $instance = new Response();

        if (!empty($body)) {
            $instance->withBody($body);
        }

        if (!empty($statusCode)) {
            $instance->withStatus($statusCode);
        } else {
            $instance->withStatus(200);
        }

        if (!empty($headers)) {
            $instance->withHeaders($headers);
        }

        if (!empty($uri)) {
            $instance->withUri($uri);
        }

        if (!empty($protocol)) {
            $instance->withProtocolVersion($protocol);
        }

        return $instance;
    }

    /**
     * Return the status code
     *
     * @return int
     */
    public function getStatusCode()
    {
        return (int) $this->statusCode;
    }

    /**
     * Return the status code Phrase
     *
     * @return string
     */
    public function getReasonPhrase()
    {
        return $this->reasonPhrase;
    }

    /**
     * Return response headers
     *
     * @return string[]
     */
    public function getHeaders()
    {
        return array_keys($this->headers);
    }

    /**
     * Validates if a heder exists in the response
     *
     * @param string $name
     * @return boolean
     */
    public function hasHeader($name)
    {
        return array_key_exists($name, $this->headers);
    }

    /**
     * Return a specific header value
     *
     * @param string $name
     * @return string
     */
    public function getHeader($name)
    {
        return $this->headers[$name];
    }

    /**
     * Return the plain response body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Return the parsed JSON body
     *
     * @param string $input
     * @return array
     */
    private function getJsonBody($input)
    {
        $result = json_decode($input, true);
        if (!is_array($result)) {
            return null;
        }
        return $result;
    }

    /**
     * Return a parsed xml string
     *
     * @param string $input
     * @return array
     */
    private function getXmlBody($input)
    {
        $backup = libxml_disable_entity_loader(true);
        $backup_errors = libxml_use_internal_errors(true);
        $result = simplexml_load_string($input);
        libxml_disable_entity_loader($backup);
        libxml_clear_errors();
        libxml_use_internal_errors($backup_errors);
        if ($result === false) {
            return null;
        }
        return $result;
    }

    /**
     * Return a parsed URL encoded string
     *
     * @param string $input
     * @return array
     */
    private function getUrlencodedBody($input)
    {
        parse_str($input, $data);
        return $data;
    }

    /**
     * Return a parsed body depending of the Content-Type header
     *
     * @return array
     * @throws \Exception
     */
    public function getParsedBody()
    {
        $type = $this->getHeader('Content-Type');

        $type = explode(';', $type);
        $type = trim($type[0]);

        if (!empty($this->bodyParsed)) {
            return $this->bodyParsed;
        }

        $body = $this->body->getContents();

        switch ($type) {
            case 'application/xml':
            case 'text/xml':
                $this->bodyParsed = $this->getXmlBody($body);
                break;
            case 'application/json':
                $this->bodyParsed = $this->getJsonBody($body);
                break;
            case 'application/x-www-form-urlencoded':
                $this->bodyParsed = $this->getUrlencodedBody($body);
                break;
            default:
                throw new \Exception("Error Parsing Request Body: not supported type {$type}");
        }

        return $this->bodyParsed;
    }

    /**
     * Set Response Headers
     *
     * @param array $headers
     * @return static
     */
    public function withHeaders(array $headers)
    {
        $this->headers = $headers;
        return self;
    }

    /**
     * Set status code an custom phrase if needed
     *
     * @param int $code
     * @param null|string $reasonPhrase
     * @return static
     */
    public function withStatus($code, $reasonPhrase = null)
    {
        $this->statusCode = $code;
        $this->reasonPhrase = $reasonPhrase;

        if (empty($reasonPhrase)) {
            $this->reasonPhrase = ReasonPhrases::getPhrase($code);
        }

        return self;
    }

    /**
     * Undocumented function
     *
     * @param UriInterface $uri
     * @param boolean $preserveHost
     * @return static
     */
    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        $host = $this->uri->getHost();

        if ($this->preserveHost) {
            $uri->withHost($host);
            $this->uri = $uri;
            $this->preserveHost = $preserveHost;
        } else {
            $this->uri = $uri;
        }
    }

    /**
     * Retrive the protocol version
     *
     * @return string
     */
    public function getProtocolVersion()
    {
        return $this->protocol;
    }

    /**
     * Set the protocol version
     *
     * @param string $version
     * @return static
     */
    public function withProtocolVersion($version)
    {
        $this->protocol = $version;
        return self;
    }

    /**
     * Retrive the value of a specific header
     *
     * @param string $name
     * @return string
     */
    public function getHeaderLine($name)
    {
        if (array_key_exists($name, $this->headers)) {
            return implode(', ', $this->headers[$name]);
        }

        return '';
    }

    /**
     * Add value to an existing header
     *
     * @param string $name
     * @param string $value
     * @return static
     */
    public function withAddedHeader($name, $value)
    {
        if (array_key_exists($name, $this->headers)) {
            $this->headers[$name][] = $value;
        } else {
            $this->headers[$name] = [$value];
        }
        return self;
    }

    /**
     * Remove a header of the response
     *
     * @param string $name
     * @return static
     */
    public function withoutHeader($name)
    {
        unset($this->headers[$name]);
        return self;
    }

    /**
     * Add header to the response
     *
     * @param string $name
     * @param string $value
     * @return static
     */
    public function withHeader($name, $value)
    {
        $this->headers[$name] = [$value];
        return self;
    }

    /**
     * Add the response body
     *
     * @param StreamInterface $body
     * @return static;
     */
    public function withBody(StreamInterface $body)
    {
        $this->body = $body;
        return self;
    }
}