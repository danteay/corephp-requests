<?php

namespace CorePHP\Requests;

use Psr\Http\Message\UriInterface;

class RequestUri implements UriInterface
{
    private $scheme;
    private $authority;
    private $userInfo;
    private $host;
    private $port;
    private $path;
    private $query;
    private $fragment;

    /**
     * Create and instance of RequestUri
     *
     * @param string $url
     * @return RequestUri
     */
    private static function getInstance($url=null)
    {
        if (empty($url)) {
            return new RequestUri();
        }

        $parsed = self::parseUri($url);

        $instance = self::getInstance()
            ->withScheme($parsed['scheme'])
            ->withUserInfo(
                $parsed['user_info']['user'],
                $parsed['user_info']['pass']
            )
            ->withHost($parsed['host'])
            ->withPort($parsed['path'])
            ->withQuery($parsed['query'])
            ->withFragment($parsed['fragment']);

        return $instance;
    }

    /**
     * Parse initial Uri
     *
     * @param string $uri
     * @return array
     */
    private static function parseUri($uri)
    {
        $parts = [
            'scheme' => '',
            'user_info' => [
                'user' => '',
                'pass' => null
            ],
            'host' => '',
            'path' => '',
            'query' => '',
            'fragment' => ''
        ];

        if (preg_match('^[a-z]{3}:\/\/', $uri)) {
            $scheme = explode('://', $uri);
            $parts['scheme'] = $scheme[0];
            $uri = $scheme[1];
        }


    }
}