<?php

namespace CorePHP\Requests\Test\Coverage;

use PHPUnit\Framework\TestCase;
use CorePHP\Requests\RequestBody;

class RequestBodyTest extends TestCase
{
    /**
     * Test instance without params
     *
     * @return RequestBody
     */
    public function testCreateInstanceWithoutParams()
    {
        $object = RequestBody::getInstance();
        $this->assertInstanceOf(RequestBody::class, $object);

        return $object;
    }

    /**
     * test write
     *
     * @depends testCreateInstanceWithoutParams
     * @param RequestBody $object Body object
     * @return RequestBody
     */
    public function testWrite(RequestBody $object)
    {
        $content = 'hola mundo';

        $resp = $object->write($content);
        $this->assertTrue(is_integer($resp));

        return $object;
    }

    /**
     * Test get contebts
     *
     * @depends testWrite
     * @param RequestBody $object Body object
     * @return RequestBody
     */
    public function testGetContents(RequestBody $object)
    {
        $content = $object->getContents();
        $this->assertTrue(is_string($content));

        return $object;
    }

    /**
     * test original contents
     *
     * @depends testGetContents
     * @param RequestBody $object Body object
     * @return RequestBody
     */
    public function testGetOriginalContents(RequestBody $object)
    {
        $content = $object->getOriginalContents();
        $this->assertEquals('hola mundo', $content);

        return $object;
    }
}