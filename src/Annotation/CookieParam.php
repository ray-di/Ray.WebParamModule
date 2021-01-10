<?php

namespace Ray\WebContextParam\Annotation;

use Attribute;

/**
 * @Annotation
 * @Target("METHOD")
 */
#[Attribute(Attribute::TARGET_PARAMETER)]
final class CookieParam extends AbstractWebContextParam
{
    const GLOBAL_KEY = '_COOKIE';
}
