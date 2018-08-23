<?php
/**
 * Request URI Scheme of a request
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

use Psr\Http\Message\UriInterface;

/**
 * RequestUri
 *
 * @category  Class
 * @package   CorePHP\Requests
 * @author    Eduardo Aguilar <dante.aguilar41@gmail.com>
 * @copyright 2018 Eduardo Aguilar
 * @license   https://github.com/danteay/corephp-requests/LICENSE Apache-2
 * @link      https://github.com/danteay/corephp-requests
 */
class RequestUri implements UriInterface
{
    /**
     * Uri Scheme
     *
     * @var string
     */
    private $_scheme;

    /**
     * User of the Uri
     *
     * @var string
     */
    private $_user;

    /**
     * Password of the Uri
     *
     * @var string
     */
    private $_pass;

    /**
     * Hostname of the Uri
     *
     * @var string
     */
    private $_host;

    /**
     * Port of the Hostname
     *
     * @var int
     */
    private $_port;

    /**
     * Path after the hostname
     *
     * @var string
     */
    private $_path;

    /**
     * Querystring after the path
     *
     * @var string
     */
    private $_query;

    /**
     * Fragment section after the querystring
     *
     * @var string
     */
    private $_fragment;

    /**
     * Create and instance of RequestUri
     *
     * @param string $url Url that will be converted
     *
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
     *
     * @return RequestUri
     */
    public function withScheme($scheme)
    {
        $this->_scheme = $scheme;

        return $this;
    }

    /**
     * Return an instance with the specified user information.
     *
     * @param string      $user     The user name to use for authority.
     * @param null|string $password The password associated with $user.
     *
     * @return RequestUri
     */
    public function withUserInfo($user, $password = null)
    {
        $this->_user = $user;
        $this->_pass = $password;

        return $this;
    }

    /**
     * Return an instance with the specified host.
     *
     * @param string $host The hostname to use with the new instance.
     *
     * @return RequestUri
     */
    public function withHost($host)
    {
        $this->_host = $host;

        return $this;
    }

    /**
     * Return an instance with the specified port.
     *
     * @param null|int $port The port to use with the new instance; a null value
     *                       removes the port information.
     *
     * @return RequestUri
     */
    public function withPort($port)
    {
        $this->_port = $port;

        return $this;
    }

    /**
     * Return an instance with the specified path.
     *
     * @param string $path The path to use with the new instance.
     *
     * @return RequestUri
     */
    public function withPath($path)
    {
        $this->_path = $path;

        return $this;
    }

    /**
     * Return an instance with the specified query string.
     *
     * @param string $query The query string to use with the new instance.
     *
     * @return RequestUri
     */
    public function withQuery($query)
    {
        $this->_query = $query;

        return $this;
    }

    /**
     * Return an instance with the specified URI fragment.
     *
     * @param string $fragment The fragment to use with the new instance.
     *
     * @return RequestUri
     */
    public function withFragment($fragment)
    {
        $this->_fragment = $fragment;

        return $this;
    }

    /**
     * Retrieve the scheme component of the URI.
     *
     * @return string The URI scheme.
     */
    public function getScheme()
    {
        return $this->_scheme;
    }

    /**
     * Retrieve the authority component of the URI.
     *
     * @return string The URI authority, in "[user-info@]host[:port]" format.
     */
    public function getAuthority()
    {
        $authority = "";

        $authority .= !empty($this->_user) ? $this->_user : "";
        $authority .= !empty($this->_pass) ? ":{$this->_pass}" : "";
        $authority .= !empty($authority) ? "@" : "";
        $authority .= !empty($this->_host) ? $this->_host : "";
        $authority .= !empty($this->_port) ? ":{$this->_port}": "";

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

        $userInfo .= !empty($this->_user) ? $this->_user : "";
        $userInfo .= !empty($this->_pass) ? ":{$this->_pass}" : "";

        return $userInfo;
    }

    /**
     * Retrieve the host component of the URI.
     *
     * @return string The URI host.
     */
    public function getHost()
    {
        return $this->_host;
    }

    /**
     * Retrieve the port component of the URI.
     *
     * @return null|int The URI port.
     */
    public function getPort()
    {
        return $this->_port;
    }

    /**
     * Retrieve the path component of the URI.
     *
     * @return string The URI path.
     */
    public function getPath()
    {
        return $this->_path;
    }

    /**
     * Retrieve the query string of the URI.
     *
     * @return string The URI query string.
     */
    public function getQuery()
    {
        return $this->_query;
    }

    /**
     * Retrieve the fragment component of the URI.
     *
     * @return string The URI fragment.
     */
    public function getFragment()
    {
        return $this->_fragment;
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