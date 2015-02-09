# ray/web-context-param

Binds the value(s) of a web context (Superglobals) to method parameter.

## Installation

### Composer install

    $ composer require ray/web-context-param
 
### Module install

```php
use Ray\WebContextParam\WebContextParamModule;
use Ray\Di\AbstractModule;

class AppModule extends AbstractModule
{
    protected function configure()
    {
        $this->install(new WebContextParamModule);
    }
}
```
### Usage

Simple usage:

```php
use Ray\WebContextParam\Annotation\QueryParam;

class Foo
{
    /**
     * @QueryParam("id")
     */
    public function getUser($id = null)
    {
      // $id = $_GET['id'];
```

More contexts with properties:

```php
use Ray\WebContextParam\Annotation\QueryParam;
use Ray\WebContextParam\Annotation\CookieParam;
use Ray\WebContextParam\Annotation\EnvParam;
use Ray\WebContextParam\Annotation\FormParam;
use Ray\WebContextParam\Annotation\ServerParam;

class Foo
{
    /**
     * @QueryParam(key="id", var="userID")
     * @CookieParam(key="id", var="tokenId", default="0")
     * @EnvParam("app_mode")
     * @FormParam("token")
     * @ServerParam(key="SERVER_NAME", var="server")
     */
    public function foo($userId = null, $tokenId = null, $app_mode, $token, $server)
    {
       // $userId   = $_GET['id'];
       // $tokenId  = $_COOKIE['id'] or "0" when unset;
       // $app_mode = $_ENV['app_mode'];
       // $token    = $_POST['token'];
       // $server   = $_SERVER['SERVER_NAME'];
```

Web context value are **not** injected when parameters are passed.

### Requirements

 * PHP 5.4+
 * hhvm
