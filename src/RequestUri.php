<?php

namespace CorePHP\Requests;

use Psr\Http\Message\UriInterface;

class RequestUri implements UriInterface
{
    /**
     * Uri Scheme
     *
     * @var string
     */
    private $scheme;

    /**
     * User of the Uri
     *
     * @var string
     */
    private $user;

    /**
     * Password of the Uri
     *
     * @var string
     */
    private $pass;

    /**
     * Hostname of the Uri
     *
     * @var string
     */
    private $host;

    /**
     * Port of the Hostname
     *
     * @var int
     */
    private $port;

    /**
     * Path after the hostname
     *
     * @var string
     */
    private $path;

    /**
     * Querystring after the path
     *
     * @var string
     */
    private $query;

    /**
     * Fragment section after the querystring
     *
     * @var string
     */
    private $fragment;

    /**
     * Create and instance of RequestUri
     *
     * @param string $url
     * @return RequestUri
     */
    public static function getInstance($url=null)
    {
        if (empty($url)) {
            return new RequestUri();
        }

        $parsed = parse_url($url);

        $instance = RequestUri::getInstance()
            ->withScheme($parsed['scheme'])
            ->withUserInfo($parsed['user'], $parsed['pass'])
            ->withHost($parsed['host'])
            ->withPort($parsed['port'])
            ->withPath($parsed['path'])
            ->withQuery($parsed['query'])
            ->withFragment($parsed['fragment']);

        return $instance;
    }

    /**
     * Return an instance with the specified scheme.
     *
     * @param string $scheme The scheme to use with the new instance.
     * @return RequestUri A new instance with the specified scheme.
     */
    public function withScheme($scheme)
    {
        $this->scheme = $scheme;

        return $this;
    }

    /**
     * Return an instance with the specified user information.
     *
     * @param string $user The user name to use for authority.
     * @param null|string $password The password associated with $user.
     * @return RequestUri A new instance with the specified user information.
     */
    public function withUserInfo($user, $password = null)
    {
        $this->user = $user;
        $this->pass = $password;

        return $this;
    }

    /**
     * Return an instance with the specified host.
     *
     * @param string $host The hostname to use with the new instance.
     * @return RequestUri A new instance with the specified host.
     */
    public function withHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Return an instance with the specified port.
     *
     * @param null|int $port The port to use with the new instance; a null value
     *     removes the port information.
     * @return RequestUri A new instance with the specified port.
     */
    public function withPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Return an instance with the specified path.
     *
     * @param string $path The path to use with the new instance.
     * @return RequestUri A new instance with the specified path.
     */
    public function withPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Return an instance with the specified query string.
     *
     * @param string $query The query string to use with the new instance.
     * @return RequestUri A new instance with the specified query string.
     */
    public function withQuery($query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Return an instance with the specified URI fragment.
     *
     * @param string $fragment The fragment to use with the new instance.
     * @return RequestUri A new instance with the specified fragment.
     */
    public function withFragment($fragment)
    {
        $this->fragment = $fragment;

        return $this;
    }

    /**
     * Retrieve the scheme component of the URI.
     *
     * @return string The URI scheme.
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * Retrieve the authority component of the URI.
     *
     * @return string The URI authority, in "[user-info@]host[:port]" format.
     */
    public function getAuthority()
    {
        $authority = "";

        $authority .= !empty($this->user) ? $this->user : "";
        $authority .= !empty($this->pass) ? ":{$this->pass}" : "";
        $authority .= !empty($authority) ? "@" : "";
        $authority .= !empty($this->host) ? $this->host : "";
        $authority .= !empty($this->port) ? ":{$this->port}": "";

        return $authority;
    }

    /**
     * Retrieve the user information component of the URI.
     *
     * @return string The URI user information, in "username[:password]" format.
     */
    public function getUserInfo()
    {
        $userInfo = "";

        $userInfo .= !empty($this->user) ? $this->user : "";
        $userInfo .= !empty($this->pass) ? ":{$this->pass}" : "";

        return $userInfo;
    }

    /**
     * Retrieve the host component of the URI.
     *
     * @return string The URI host.
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Retrieve the port component of the URI.
     *
     * @return null|int The URI port.
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Retrieve the path component of the URI.
     *
     * @return string The URI path.
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Retrieve the query string of the URI.
     *
     * @return string The URI query string.
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Retrieve the fragment component of the URI.
     *
     * @return string The URI fragment.
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * Return the string representation as a URI reference.
     *
     * @return string
     */
    public function __toString()
    {
        $fullUri = "";
        $fullUri .= !empty($this->getScheme()) ? "{$this->getScheme()}:" : "";
        $fullUri .= !empty($this->getAuthority()) ? "//{$this->getAuthority()}" : "";
        $fullUri .= !empty($this->getPath()) ? "{$this->getPath()}" : "";
        $fullUri .= !empty($this->getQuery()) ? "?{$this->getQuery()}" : "";
        $fullUri .= !empty($this->getFragment()) ? "#{$this->getFragment()}" : "";

        return $fullUri;
    }
}