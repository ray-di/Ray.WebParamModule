<?php
/**
 * This file is part of the Ray.WebContextParam.
 *
 * @license http://opensource.org/licenses/bsd-license.php MIT
 */
namespace Ray\WebContextParam;

use Ray\WebContextParam\Annotation\QueryParam;

class FakeConsumer
{
    public $id;

    /**
     * @QueryParam(param="id", key="id")
     */
    public function getId($id = 'default')
    {
        $this->id = $id;
    }

    /**
     * @QueryParam(key="id", param="id")
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
