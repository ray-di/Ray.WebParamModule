<?php

namespace Ray\WebContextParam;

class WebContextTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var WebContext
     */
    private $webContext;

    public function setUp()
    {
        $_ENV[__METHOD__] = 1;
        $this->webContext = new WebContext;
        parent::setUp();
    }

    public function testGet()
    {
        $env = $this->webContext->get('_ENV');
        $this->assertSame($_ENV, $env);
    }

    public function testSerialize()
    {
        $key = md5(__CLASS__);
        $_ENV[$key] = true;
        $freeze = serialize(new WebContext);
        $_ENV[$key] = false;
        $env = unserialize($freeze)->get('_ENV');
        $this->assertFalse($env[$key]);
    }
}
