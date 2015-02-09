<?php
/**
 * This file is part of the Ray.WebContextParam
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace Ray\WebContextParam;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Cache\Cache;
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
        $cnt = count($args);
        $parameters = $invocation->getMethod()->getParameters();
        $cnt =count($parameters);
        for ($i = 0; $i < $cnt; $i++) {
            if (isset($meta[$i]) && (! isset($args[$i]))) {
                $args[$i] = $this->getParam($meta, $i);
            }
        }

        return $invocation->proceed();
    }

    private function getMeta(\ReflectionMethod $method)
    {
        $meta = [];
        $annotations = $this->reader->getMethodAnnotations($method);
        /**
 * @var $annotation AbstractWebContextParam
*/
        foreach ($annotations as $annotation) {
            if ($annotation instanceof AbstractWebContextParam) {
                $pos = $this->getPos($annotation, $method);
                $meta[$pos] = [$annotation::GLOBAL_KEY, $annotation->key, $annotation->default];
            }
        }

        return $meta;
    }

    private function getParam(array $meta, $i)
    {
        list($globalKey, $key, $default) = $meta[$i];
        $webContext = $this->webContext->get($globalKey);

        return isset($webContext[$key]) ? $webContext[$key] : $default;
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
        $var = $annotation->var ? $annotation->var : $annotation->key;
        foreach ($parameters as $parameter) {
            if ($parameter->name == $var) {
                $pos = $parameter->getPosition();

                return $pos;
            }
        }
        $msg = sprintf("parameter %s of method %s in %s Not Found", $var, $method->name, $method->getFileName());
        throw new NotFoundArgumentException($msg);
    }
}
