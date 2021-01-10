<?php

namespace Ray\WebContextParam\Annotation;

use function is_array;
use function is_string;

abstract class AbstractWebContextParam
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
    public function __construct($key)
    {
        if (is_array($key)) {
            $this->key = $key['key'];
            $this->param = $key['param'];

            return;
        }
        if (is_string($key)) {
            $this->key = $key;
        }
    }
}
