<?php
/**
 * This file is part of the Ray.WebContextParam
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace Ray\WebContextParam;


use Ray\WebContextParam\Annotation\QueryParam;

class FakeConsumer
{
    public $id;

    /**
     * @QueryParam(var="id", key="id", default="default")
     */
    public function getId($id = null)
    {
        $this->id = $id;
    }

    /**
     * @QueryParam(key="id", var="id")
     */
    public function notFound($notListedInQueryParam)
    {
    }

    /**
     * @QueryParam("id")
     */
    public function keyOnly($id)
    {
        $this->id = $id;
    }
}
