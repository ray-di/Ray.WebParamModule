<?php
/**
 * This file is part of the Ray.WebContextParam.
 *
 * @license http://opensource.org/licenses/bsd-license.php MIT
 */
namespace Ray\WebContextParam;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Cache\Cache;
use Ray\Aop\Arguments;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;
use Ray\WebContextParam\Annotation\AbstractWebContextParam;
use Ray\WebContextParam\Exception\NotFoundArgumentException;

class WebContextParamInterceptor implements MethodInterceptor
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var Cache
     */
    private $cache;

    /**
     * @var
     */
    private $webContext;

    public function __construct(
        Reader $reader,
        Cache $cache,
        WebContext $webContext
    ) {
        $this->reader = $reader;
        $this->cache = $cache;
        $this->webContext = $webContext;
    }

    /**
     * {@inheritdoc}
     */
    public function invoke(MethodInvocation $invocation)
    {
        $method = $invocation->getMethod();
        $args = $invocation->getArguments();
        $id = __CLASS__ . $invocation->getMethod();
        $meta = $this->cache->fetch($id);
        if (! $meta) {
            $meta = $this->getMeta($method);
            $this->cache->save($id, $meta);
        }
        $parameters = $invocation->getMethod()->getParameters();
        $cnt = count($parameters);
        for ($i = 0; $i < $cnt; $i++) {
            $this->setArg($args, $meta, $i);
        }

        return $invocation->proceed();
    }

    private function getMeta(\ReflectionMethod $method)
    {
        $meta = [];
        $annotations = $this->reader->getMethodAnnotations($method);
        /* @var $annotation AbstractWebContextParam */
        foreach ($annotations as $annotation) {
            if ($annotation instanceof AbstractWebContextParam) {
                $pos = $this->getPos($annotation, $method);
                $meta[$pos] = [$annotation::GLOBAL_KEY, $annotation->key];
            }
        }

        return $meta;
    }

    /**
     * @param array $meta
     * @param int   $i
     *
     * @return array
     */
    private function getParam(array $meta, $i)
    {
        list($globalKey, $key) = $meta[$i];
        $webContext = $this->webContext->get($globalKey);
        if (isset($webContext[$key])) {
            return [true, $webContext[$key]];
        }

        return [false, null];
    }

    /**
     * @param AbstractWebContextParam $annotation
     * @param \ReflectionMethod       $method
     *
     * @return int
     */
    private function getPos(AbstractWebContextParam $annotation, \ReflectionMethod $method)
    {
        $parameters = $method->getParameters();
        $param = $annotation->param ? $annotation->param : $annotation->key;
        foreach ($parameters as $parameter) {
            if ($parameter->name == $param) {
                $pos = $parameter->getPosition();

                return $pos;
            }
        }
        $msg = sprintf("parameter %s of method %s in %s Not Found", $param, $method->name, $method->getFileName());
        throw new NotFoundArgumentException($msg);
    }

    /**
     * @param Arguments $args
     * @param array     $meta
     * @param in        $i
     */
    private function setArg(Arguments $args, array $meta, $i)
    {
        if (isset($meta[$i]) && (! isset($args[$i]))) {
            list($hasParam, $param) = $this->getParam($meta, $i);
            if ($hasParam) {
                $args[$i] = $param;
            }
        }
    }
}
