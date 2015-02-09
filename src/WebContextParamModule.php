<?php
/**
 * This file is part of the Ray.AuraSqlModule package
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace Ray\WebContextParam;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\Cache;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;
use Ray\WebContextParam\Annotation\CookieParam;
use Ray\WebContextParam\Annotation\EnvParam;
use Ray\WebContextParam\Annotation\FormParam;
use Ray\WebContextParam\Annotation\QueryParam;
use Ray\WebContextParam\Annotation\ServerParam;

class WebContextParamModule extends AbstractModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $matchWebContextAnnotation = $this->matcher->logicalOr(
            $this->matcher->annotatedWith(QueryParam::class),
            $this->matcher->logicalOr(
                $this->matcher->annotatedWith(CookieParam::class),
                $this->matcher->logicalOr(
                    $this->matcher->annotatedWith(FormParam::class),
                    $this->matcher->logicalOr(
                        $this->matcher->annotatedWith(EnvParam::class),
                        $this->matcher->annotatedWith(ServerParam::class)
                    )
                )
            )
        );

        $this->bindInterceptor(
            $this->matcher->any(),
            $matchWebContextAnnotation,
            [WebContextParamInterceptor::class]
        );
        $this->bind(Reader::class)->to(AnnotationReader::class)->in(Scope::SINGLETON);
        $this->bind(Cache::class)->to(ArrayCache::class)->in(Scope::SINGLETON);
    }
}
