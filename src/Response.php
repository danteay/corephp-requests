<?php

namespace CorePHP\Requests;

use Psr\Http\Message\ResponseInterface;

class Response implements ResponseInterface
{
    private $statusCode;
    private $reasonPhrase;
    private $headers;
    private $protocol;
    private $body;
    private $uri;

    public function __construct()
    {

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
        return $this->headers;
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
     * Return a parsed xml string
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
     * @return array
     */
    public function getParsedBody()
    {
        $type = $this->headers['Content-Type'];

        $type = explode(';', $type);
        $type = trim($type[0]);

        if (!empty($this->bodyParsed)) {
            return $this->bodyParsed;
        }

        switch ($type) {
            case 'application/xml':
            case 'text/xml':
                $this->bodyParsed = $this->getXmlBody($this->body);
                break;
            case 'application/json':
                $this->bodyParsed = $this->getJsonBody($this->body);
                break;
            case 'application/x-www-form-urlencoded':
                $this->bodyParsed = $this->getUrlencodedBody($this->body);
                break;
            default:
                throw new \Exception(
                    "Error Parsing Request Body: not supported type {$type}"
                );
                break;
        }

        return $this->bodyParsed;
    }

    public function withHeaders($headers)
    {
        $this->headers = $headers;
    }

    public function withStatus($code, $reasonPhrase = '')
    {
        $this->statusCode = $code;
        $this->reasonPhrase = ReasonPhrases::getPhrase($code);
    }

    public function withUri(\Psr\Http\Message\UriInterface $uri, $preserveHost = false)
    {
        $this->uri = $uri;
    }

    public function getProtocolVersion()
    {
        return $this->protocol;
    }

    public function withProtocolVersion($version)
    {
        $this->protocol = $version;
    }

    public function getHeaderLine($name)
    {
        return $this->headers[$name];
    }

    public function withAddedHeader($name, $value)
    {
        if (array_key_exists($name, $this->headers)) {
            $this->headers[$name] .= ", {$value}";
        } else {
            $this->headers[$name] = $value;
        }
    }

    public function withoutHeader($name)
    {
        unset($this->headers[$name]);
    }

    public function withHeader($name, $value)
    {
        $this->headers[$name] = $value;
    }

    public function withBody(\Psr\Http\Message\StreamInterface $body)
    {
        throw new \BadMethodCallException("Not Implemented Method");

    }
}