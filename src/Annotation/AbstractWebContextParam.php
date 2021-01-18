<?php

namespace Ray\WebContextParam\Annotation;

use Doctrine\Common\Annotations\NamedArgumentConstructorAnnotation;

abstract class AbstractWebContextParam implements NamedArgumentConstructorAnnotation
{
    /**
     * Key of Super global value
     */
    const GLOBAL_KEY = '';

    /**
     * Key of query parameter
     *
     * @var string
     */
    public $key;

    /**
     * Parameter(Variable) name
     *
     * This parameter is used to specify a parameter from a method in PHP7,
     * and is not needed when attributing to a parameter in PHP8.
     *
     * @var string
     */
    public $param;

    public function __construct(string $key, string $param = '')
    {
        $this->key = $key;
        $this->param = $param;
    }
}
