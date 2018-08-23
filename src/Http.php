<?php

namespace CorePHP\Requests;

class Http
{
    /**
     * Request Target
     *
     * @var \CorePHP\Requests\RequestTarget
     */
    private $target;

    /**
     * CRUL resoruce
     *
     * @var resource
     */
    private $handler;

    /**
     * Http Constructor
     */
    public function __construct()
    {
        $this->target = RequestTarget::getInstanace();

    }

    /**
     * Retrive an instace of Http object
     *
     * @param RequestTarget $target
     * @return Http
     */
    public static function getInstance(RequestTarget $target = null)
    {
        if (empty($target)) {
            return new Http();
        }

        return self::getInstance()
            ->withRequestTarget($target)
            ->createHandler();
    }

    /**
     * Create instance of CURL handler
     *
     * @return static
     * @throws \Exception
     */
    public function createHandler()
    {
        $this->headers = [];
        $this->handler = curl_init($this->target->getUri());

        curl_setopt($this->handler, CURLOPT_HEADER, true);
        curl_setopt($this->handler, CURLOPT_COOKIE, true);
        curl_setopt($this->handler, CURLOPT_RETURNTRANSFER, true);

        $this->setMethod();
        $this->setAuth();
        $this->setHeaders();
        $this->setBody();

        return self;
    }

    /**
     * Set the target that will be requested
     *
     * @param RequestTarget $target
     * @return static
     */
    public function withRequestTarget(RequestTarget $target)
    {
        $this->target = $target;
        return self;
    }

    /**
     * Set HTTP Method for the request
     *
     * @throws \Exception
     */
    private function setMethod()
    {
        $method = $this->target->geMethod();

        switch ($method) {
            case 'GET':
            case 'POST':
            case 'PUT':
            case 'DELETE':
                $this->method = $method;
                break;
            default:
                throw new \Exception('Not supported method');
        }

        curl_setopt($this->handler, CURLOPT_CUSTOMREQUEST, $this->method);
    }

    /**
     * Set basic auth params for the request
     *
     * @throws \Exception
     */
    private function setAuth()
    {
        $auth = $this->target->getBasicAuth();

        if (!empty($auth)) {
            curl_setopt($this->handler, CURLOPT_USERPWD, $auth);
        }
    }

    /**
     * Set json data for the request
     */
    private function setBody()
    {
        $body = $this->target->getBody()->getOriginalContents();

        if (!array_key_exists('Content-Type', $this->headers)) {
            $this->headers['Content-Type'] = "text/plain; charset=utf-8";
        }

        $contentType = explode(';', $this->headers['Content-Type']);
        $contentType = trim($contentType[0]);

        if (is_string($body)) {
            curl_setopt($this->handler, CURLOPT_POSTFIELDS, $body);
            return;
        }

        if (is_array($body) || is_object($body)) {
            switch ($contentType) {
                case 'application/json':
                case 'text/json':
                    $body = json_encode($body);
                    break;

                case 'application/x-www-form-urlencoded':
                    $body = http_build_query($body);
                    break;
            }
        } else {
            throw new \Exception("Invalid request body content");
        }

        curl_setopt($this->handler, CURLOPT_POSTFIELDS, $body);
    }

    /**
     * Set request headers
     */
    private function setHeaders()
    {
        $headers = $this->target->getHeaders();

        if (!empty($headers)) {
            $this->headers = array_merge($this->headers, $headers);
        }
    }

    /**
     * Execute request from previous configuration
     *
     * @return Response
     * @throws \Exception
     */
    public function executeRequest()
    {
        $aux = array();

        foreach ($this->headers as $header => $value) {
            $aux[] = "$header: $value";
        }

        curl_setopt($this->handler, CURLOPT_HTTPHEADER, $aux);

        $body = curl_exec($this->handler);

        if(empty($body)){
            $error = curl_error($this->handler);
            $code = curl_errno($this->handler);
            throw new \Exception($error, $code);
        }

        $statusCode = curl_getinfo($this->handler, CURLINFO_HTTP_CODE);

        $response = new Response();
        $response->withStatus($statusCode);
        $this->getBodyAndHeaders($body, $response);

        curl_close($this->handler);

        return $response;
    }

    /**
     * Extract all headers from curl response
     * @param $body
     * @param Response $response
     * @return void
     */
    private function getBodyAndHeaders($body, Response &$response)
    {
        $headers = array();
        $data = explode("\n",$body);

        $flag = 1;

        foreach ($data as $part) {
            if ($flag == 1) {
                $protocol = explode(' ', $part)[0];
                $response->withProtocolVersion($protocol);
            } else if(count($data) == $flag) {
                $stream = RequestBody::getInstance(trim($part));
                $response->withBody($stream);
                break;
            } else {
                $aux = explode(': ', $part);

                if (count($aux) >= 2) {
                    $headers[$aux[0]] = trim($aux[1]);
                }
            }

            $flag++;
        }

        $response->withHeaders($headers);
    }
}