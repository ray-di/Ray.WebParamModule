<?php
/**
 * This file is part of the BEAR.Resource package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\WebContextParam\Annotation;

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
     * @param array $values{key?: string, param?: string}
     * @param string $key
     * @param string $param
     */
    public function __construct(array $values = [], $key = '', $param = '')
    {
        $this->key = isset($values['key']) ? $values['key'] : $key;
        $this->param = isset($values['param']) ? $values['param'] : $param;
    }
}
