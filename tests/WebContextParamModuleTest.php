<?php

namespace Ray\WebContextParam;

use Ray\Di\Injector;

class WebContextParamModuleTest extends \PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $injector = new Injector(new WebContextParamModule(), $_ENV['TMP_DIR']);
        $expected = 10;
        $_GET['id'] = $expected;
        /** @var $consumer FakeConsumer */
        $consumer = $injector->getInstance(FakeConsumer::class);
        $consumer->getId();
        $this->assertSame($expected, $consumer->id);
    }
}
