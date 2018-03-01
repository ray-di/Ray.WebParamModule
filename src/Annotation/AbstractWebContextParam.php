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
}
