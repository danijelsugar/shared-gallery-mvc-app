<?php


declare(strict_types=1);

namespace Gallery\Core;

use Gallery\Core\Request;
use Gallery\Core\Response;
use Gallery\Core\Router;

final class App
{
    public Request $request;
    public Response $response;
    public Router $router;

    public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
    }

    public function run()
    {
        echo $this->router->resolve();
    }
}
