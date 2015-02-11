<?php

namespace Ray\WebContextParam;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\ArrayCache;
use Ray\Aop\Arguments;
use Ray\Aop\ReflectiveMethodInvocation;
use Ray\WebContextParam\Annotation\CookieParam;
use Ray\WebContextParam\Annotation\EnvParam;
use Ray\WebContextParam\Annotation\FormParam;
use Ray\WebContextParam\Annotation\QueryParam;
use Ray\WebContextParam\Annotation\ServerParam;
use Ray\WebContextParam\Exception\NotFoundArgumentException;

class WebParamInjectInterceptorTest extends \PHPUnit_Framework_TestCase
{
    private function factory($obj, $method, array $get, $annotation, $args = [])
    {
        $invocation = new ReflectiveMethodInvocation(
            $obj,
            new \ReflectionMethod($obj, $method),
            new Arguments($args),
            [
                new WebContextParamInterceptor(
                    new AnnotationReader,
                    new ArrayCache,
                    new WebContext(['_GET' => $get]),
                    $annotation
                )
            ]
        );

        return $invocation;
    }

    public function testInject()
    {
        $obj = new FakeConsumer;
        $invocation = $this->factory($obj, 'getId', ['id' => 'bear'], new QueryParam, [null]);
        $invocation->proceed();
        $expected = 'bear';
        $this->assertSame($expected, $obj->id);
    }

    public function testNotFound()
    {
        $this->setExpectedException(NotFoundArgumentException::class);
        $obj = new FakeConsumer;
        $invocation = $this->factory($obj, 'notFound', ['id' => 'bear'], new QueryParam);
        $invocation->proceed();
    }

    public function testDefault()
    {
        $obj = new FakeConsumer;
        $invocation = $this->factory($obj, 'getId', [], new QueryParam, []);
        $invocation->proceed();
        $expected = 'default';
        $this->assertSame($expected, $obj->id);
    }

    public function testArgsHasPriority()
    {
        $obj = new FakeConsumer;
        $invocation = $this->factory($obj, 'getId', ['id' => 'bear'], new QueryParam, ['arg']);
        $invocation->proceed();
        $expected = 'arg';
        $this->assertSame($expected, $obj->id);
    }

    public function testKeyOnly()
    {
        $obj = new FakeConsumer;
        $invocation = $this->factory($obj, 'keyOnly', ['id' => 'bear'], new QueryParam, ['arg']);
        $invocation->proceed();
        $expected = 'arg';
        $this->assertSame($expected, $obj->id);
    }

    public function testCookieParam()
    {
        $this->assertSame('_COOKIE', CookieParam::GLOBAL_KEY);
        $this->assertSame('_ENV', EnvParam::GLOBAL_KEY);
        $this->assertSame('_POST', FormParam::GLOBAL_KEY);
        $this->assertSame('_SERVER', ServerParam::GLOBAL_KEY);
        $this->assertSame('_COOKIE', CookieParam::GLOBAL_KEY);
    }
}
