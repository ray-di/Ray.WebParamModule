<?php
/**
 * This file is part of the Ray.WebContextParam.
 *
 * @license http://opensource.org/licenses/bsd-license.php MIT
 */
namespace Ray\WebContextParam\Annotation;

abstract class AbstractWebContextParam
{
    /**
     * @var string
     */
    const GLOBAL_KEY = '';

    /**
     * Key of query parameter
     *
     * @var string
     */
    public $key;

    /**
     * @var string
     */
    public $param;
}
