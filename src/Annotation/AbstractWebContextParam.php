<?php

namespace Ray\WebContextParam\Annotation;

use Doctrine\Common\Annotations\NamedArgumentConstructorAnnotation;
use function is_array;
use function is_string;

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
     * @var string
     */
    public $param;

    /**
     * @param string|array{key?: string, param?: string} $values
     */
    public function __construct(string $key = '', string $param = '')
    {
        $this->key = $key;
        $this->param = $param;
    }
}
