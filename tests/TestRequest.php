<?php

namespace CorePHP\Requests\Test;

use PHPUnit\Framework\TestCase;
use CorePHP\Requests\Request as HttpReq;

class TestRequest extends TestCase
{
    public function testGET()
    {
        try {
            $url = 'https://api.compropago.com/v1/providers/true';
            $res = HttpReq::get($url);

            if (!empty($res->getBody()) && !empty($res->getHeaders()) && is_numeric($res->getStatusCode())){
                $this->assertTrue(true);
                var_dump($res->getParsedBody());
                return;
            }

            $this->assertTrue(false);
            return;
        } catch(\Exception $e) {
            echo "\n====>> GET: {$e->getMessage()}\n";
            echo $e->getTraceAsString();
            $this->assertTrue(false);
        }

        return;
    }

    public function testPOST()
    {
        $url = 'https://api.compropago.com/v1/charges';

        $auth = [
            'user' => 'sk_test_56e31883637446b1b',
            'pass' => 'pk_test_8781245a88240f9cf'
        ];

        $data = [
            'order_id' => 12,
            'order_name' => "M4 sdk php",
            'order_price' => 123.45,
            'customer_name' => "Eduardo Aguilar",
            'customer_email' => "asdr@compropago.com",
            'payment_type' => 'OXXO'
        ];

        try {
            $res = HttpReq::post($url, $data, array(), $auth);

            if (!empty($res->getBody()) && !empty($res->getHeaders()) && is_numeric($res->getStatusCode())){
                $this->assertTrue(true);
                return;
            }

            $this->assertTrue(false);
            return;
        } catch(\Exception $e) {
            echo "\n====>> POST: {$e->getMessage()}\n";
            echo $e->getTraceAsString();
            $this->assertTrue(false);
        }

        return;
    }
}