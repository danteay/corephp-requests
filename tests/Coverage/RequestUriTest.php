<?php

namespace CorePHP\Requests\Test\Coverage;

use PHPUnit\Framework\TestCase;
use CorePHP\Requests\RequestUri;

/**
 * RequestUriTest
 *
 * @group coverage
 */
class RequestUriTest extends TestCase
{
    const URL = "https://user:pass@localhost:1234/host/path?query=value#fragment";
    const USER = 'user';
    const PASS = 'pass';
    const HOST = 'localhost';
    const PORT = 1234;
    const PATH = '/host/path';
    const QUERY = 'query=value';
    const SCHEME = 'https';
    const FRAGMENT = 'fragment';
    const USERINFO = 'user:pass';
    const AUTHORITY = "user:pass@localhost:1234";

    /**
     * Create plain instance of RequestUri
     *
     * @return RequestUri
     */
    public function testCreateInstanceWithoutParams()
    {
        $object = RequestUri::getInstance();
        $this->assertInstanceOf(RequestUri::class, $object);

        return $object;
    }

    /**
     * Create instance with a base url
     *
     * @return RequestUri
     */
    public function testCreateInstanceWithParams()
    {
        $object = RequestUri::getInstance(RequestUriTest::URL);
        $this->assertInstanceOf(RequestUri::class, $object);

        return $object;
    }

    /**
     * Validate Scheme of Uri object
     *
     * @depends testCreateInstanceWithParams
     * @param RequestUri $object
     * @return RequestUri
     */
    public function testGetScheme(RequestUri $object)
    {
        $scheme = $object->getScheme();
        $this->assertEquals(RequestUriTest::SCHEME, $scheme);
    }

    /**
     * Validate Authority of Uri object
     *
     * @depends testCreateInstanceWithParams
     * @param RequestUri $object
     * @return RequestUri
     */
    public function testGetAuthority(RequestUri $object)
    {
        $authority = $object->getAuthority();
        $this->assertEquals(RequestUriTest::AUTHORITY, $authority);
    }

    /**
     * Validate User information of Uri object
     *
     * @depends testCreateInstanceWithParams
     * @param RequestUri $object
     * @return RequestUri
     */
    public function testGetUserInfo(RequestUri $object)
    {
        $userinfo = $object->getUserInfo();
        $this->assertEquals(RequestUriTest::USERINFO, $userinfo);
    }

    /**
     * Validate Host of Uri object
     *
     * @depends testCreateInstanceWithParams
     * @param RequestUri $object
     * @return RequestUri
     */
    public function testGetHost(RequestUri $object)
    {
        $host = $object->getHost();
        $this->assertEquals(RequestUriTest::HOST, $host);
    }

    /**
     * Validate Port of Uri object
     *
     * @depends testCreateInstanceWithParams
     * @param RequestUri $object
     * @return RequestUri
     */
    public function testGetPort(RequestUri $object)
    {
        $port = $object->getPort();
        $this->assertEquals(RequestUriTest::PORT, $port);
    }

    /**
     * Validate Path of Uri object
     *
     * @depends testCreateInstanceWithParams
     * @param RequestUri $object
     * @return RequestUri
     */
    public function testGetPath(RequestUri $object)
    {
        $path = $object->getPath();
        $this->assertEquals(RequestUriTest::PATH, $path);
    }

    /**
     * Validate Query of Uri object
     *
     * @depends testCreateInstanceWithParams
     * @param RequestUri $object
     * @return RequestUri
     */
    public function testGetQuery(RequestUri $object)
    {
        $query = $object->getQuery();
        $this->assertEquals(RequestUriTest::QUERY, $query);
    }

    /**
     * Validate Fragment of Uri object
     *
     * @depends testCreateInstanceWithParams
     * @param RequestUri $object
     * @return RequestUri
     */
    public function testGetFragment(RequestUri $object)
    {
        $fragment = $object->getFragment();
        $this->assertEquals(RequestUriTest::FRAGMENT, $fragment);
    }
}