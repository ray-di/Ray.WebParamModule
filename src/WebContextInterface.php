<?php
/**
 * This file is part of the Ray.WebContextParam.
 *
 * @license http://opensource.org/licenses/bsd-license.php MIT
 */
namespace Ray\WebContextParam;

interface WebContextInterface
{
    /**
     * Return web context
     *
     * @param string $key
     *
     * @return array
     */
    public function get($key);
}
