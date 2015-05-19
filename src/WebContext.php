<?php
/**
 * This file is part of the Ray.WebContextParam.
 *
 * @license http://opensource.org/licenses/bsd-license.php MIT
 */
namespace Ray\WebContextParam;

class WebContext
{
    private $globals;

    /**
     * @param array $globals
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @codeCoverageIgnore
     */
    public function __construct(
        array $globals = []
    ) {
        $this->globals = $globals ?: [
            '_ENV' => $_ENV,
            '_GET' => $_GET,
            '_POST' => $_POST,
            '_COOKIE' => $_COOKIE,
            '_SERVER' => $_SERVER
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        return isset($this->globals[$key]) ? $this->globals[$key] : [];
    }

    /**
     * @SuppressWarnings(PHPMD.Superglobals)
     * @codeCoverageIgnore
     */
    public function __wakeup()
    {
        $this->globals =
        [
            '_ENV' => $_ENV,
            '_GET' => $_GET,
            '_POST' => $_POST,
            '_COOKIE' => $_COOKIE,
            '_SERVER' => $_SERVER
        ];
    }
}
