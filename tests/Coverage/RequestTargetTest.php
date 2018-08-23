<?php

namespace CorePHP\Requests\Test\Coverage;

use PHPUnit\Framework\TestCase;
use CorePHP\Requests\RequestTarget;

/**
 * RequestTargetTest
 *
 * @group coverage
 */
class RequestTargetTest extends TestCase
{
    /**
     * Request target instance
     *
     * @var RequestTarget
     */
    private $target;

    /**
     * Create Instance Without Params
     *
     * @return void
     */
    public function testCreateInstanceWithoutParams()
    {
        $this->target = RequestTarget::getInstanace();

        $this->assertInstanceOf(
            RequestTarget::class,
            $this->target
        );
    }
}