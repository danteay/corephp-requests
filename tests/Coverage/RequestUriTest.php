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
    const pass = 'pass';
    const host = 'localhost';
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
     * @return void
     */
    public function testCreateInstanceWithoutParams()
    {
        $object = RequestUri::getInstance();
        $this->assertInstanceOf(RequestUri::class, $object);
    }

    /**
     * Create instance with a base url
     *
     * @return RequestUri
     */
    public function testCreateInstanceWithParams()
    {
        $object = RequestUri::getInstance(self::URL);
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
        $this->assertEquals(self::SCHEME, $scheme);

        return $object;
    }

    /**
     * Validate Authority of Uri object
     *
     * @param RequestUri $object
     * @return RequestUri
     */
    public function testGetAuthority(RequestUri $object)
    {
        $authority = $object->getAuthority();
        $this->assertEquals(self::AUTHORITY, $authority);

        return $object;
    }

    /**
     * VAlidate User information of Uri object
     *
     * @param RequestUri $object
     * @return RequestUri
     */
    public function testGetUserInfo(RequestUri $object)
    {
        $userinfo = $object->getUserInfo();
        $this->assertEquals(self::USERINFO, $userinfo);

        return $object;
    }
}